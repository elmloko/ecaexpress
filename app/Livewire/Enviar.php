<?php

namespace App\Livewire;

use App\Models\Paquete;
use App\Models\Empresa;
use App\Models\Peso;
use App\Models\Tarifario;
use App\Models\Evento;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class Enviar extends Component
{
    use WithPagination;

    // Filtros y selección
    public $search = '';
    public $searchInput = '';
    public $selectAll = false;
    public $selected = [];

    // Modal API -> destino
    public $modalDestino = false;
    public $paqueteDestinoId = null;

    // Modal crear/editar paquete
    public $modal = false;
    public $paquete_id;

    // Campos de paquete
    public $codigo;
    public $destinatario;
    public $cuidad;
    public $destino;
    public $peso;
    public $observacion;
    public $certificacion = false;

    // Nuevo modal: cantidad + registro
    public $modalCantidad = false;
    public $cantidad = 1;

    protected $paginationTheme = 'bootstrap';

    protected $rules = [
        'codigo'        => 'required|string|max:50',
        'destinatario'  => 'required|string|max:100',
        'cuidad'        => 'nullable|string|max:50',
        'destino'       => 'required|string|max:50',
        'peso'          => 'nullable|numeric',
        'observacion'   => 'nullable|string|max:255',
        'certificacion' => 'boolean',
        'cantidad'      => 'required|integer|min:1',
    ];

    public function mount()
    {
        $this->searchInput = $this->search;
    }

    public function buscar()
    {
        $this->search = trim($this->searchInput);

        if (! $this->search) {
            session()->flash('message', 'Debe ingresar un código para buscar.');
            return;
        }

        $url = config('services.correos.url') . '/' . $this->search;

        $response = Http::withOptions([
            'verify' => false,
            'curl' => [
                CURLOPT_SSLVERSION   => CURL_SSLVERSION_TLSv1_2,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_IPRESOLVE    => CURL_IPRESOLVE_V4,
            ],
        ])
            ->withToken(config('services.correos.token'))
            ->acceptJson()
            ->get($url);

        if (! $response->successful()) {
            session()->flash('message', "Paquete no encontrado o error API ({$response->status()}).");
            return;
        }

        $data = $response->json();

        if (
            $data['VENTANILLA'] !== 'ECA' ||
            $data['ESTADO']     !== 'DESPACHO' ||
            strtoupper($data['CUIDAD']) !== strtoupper(Auth::user()->city)
        ) {
            session()->flash('message', 'El paquete no cumple los criterios de Ventanilla, Estado o Ciudad.');
            return;
        }

        // Crear o actualizar sin destino
        $paquete = Paquete::updateOrCreate(
            ['codigo' => $data['CODIGO']],
            [
                'destinatario' => strtoupper($data['DESTINATARIO']),
                'estado'       => 'ENVIANDO',
                'cuidad'       => strtoupper($data['CUIDAD']),
                'peso'         => floatval($data['PESO']),
                'user'         => Auth::user()->name,
            ]
        );

        Evento::create([
            'accion'      => 'ENCONTRADO',
            'descripcion' => 'Paquete Registrado',
            'user_id'     => Auth::user()->name,
            'codigo'      => $paquete->codigo,
        ]);

        $this->paqueteDestinoId = $paquete->id;
        $this->modalDestino     = true;
    }

    public function asignarDestino()
    {
        $this->validateOnly('destino');

        Paquete::findOrFail($this->paqueteDestinoId)
            ->update(['destino' => $this->destino]);

        session()->flash('message', "Destino asignado al paquete {$this->paqueteDestinoId}.");
        $this->reset(['destino', 'paqueteDestinoId', 'searchInput', 'search']);
        $this->modalDestino = false;
        $this->resetPage();
    }
    public function toggleSelectAll()
    {
        $this->selectAll = ! $this->selectAll;

        if ($this->selectAll) {
            $this->selected = Paquete::where('estado', 'ENVIANDO')
                ->where(function ($q) {
                    $q->where('codigo', 'like', '%' . $this->search . '%')
                        ->orWhere('cuidad', 'like', '%' . $this->search . '%')
                        ->orWhere('observacion', 'like', '%' . $this->search . '%');
                })
                ->orderBy('id', 'desc')
                ->paginate(10)
                ->pluck('id')
                ->toArray();
        } else {
            $this->selected = [];
        }
    }

    public function recibirSeleccionados()
    {
        if (empty($this->selected)) {
            session()->flash('message', 'No hay paquetes seleccionados.');
            return;
        }

        foreach ($this->selected as $id) {
            /** @var Paquete $paquete */
            $paquete = Paquete::find($id);

            // 1. Buscar empresa
            $empresaModel = Empresa::whereRaw('UPPER(nombre) = ?', [strtoupper($paquete->destinatario)])->first();

            // 2. Buscar categoría de peso
            $pesoCat = Peso::where('min', '<=', $paquete->peso)
                ->where('max', '>=', $paquete->peso)
                ->first();

            $precio = 0;

            if ($empresaModel && $pesoCat) {
                // 3. Obtener tarifa
                $tarifa = Tarifario::where('empresa', $empresaModel->id)
                    ->where('peso', $pesoCat->id)
                    ->first();

                if ($tarifa) {
                    // 4. Leer columna según destino
                    $col = strtolower($paquete->destino);
                    if (isset($tarifa->$col)) {
                        $precio = $tarifa->$col;
                    }
                }
            }

            if ($paquete->certificacion) {
                $precio += 8;
            }

            // 5. Actualizar paquete
            $paquete->update([
                'estado' => 'DESPACHADO',
                'precio' => $precio,
            ]);
        }

        Evento::create([
            'accion' => 'DESPACHADO',
            'descripcion' => 'Paquete Despachado a Destinatario',
            'user_id' => Auth::user()->name,
            'codigo' => $paquete->codigo,
        ]);

        $this->selected  = [];
        $this->selectAll = false;
        session()->flash('message', 'Paquetes recibidos y marcados como ALMACEN correctamente.');
        $this->resetPage();
    }

    public function eliminarPaquete($id)
    {
        $p = Paquete::findOrFail($id);
        $p->forceDelete();
        $this->resetPage();
        session()->flash('message', 'Paquete eliminado permanentemente.');

        Evento::create([
            'accion' => 'ELIMINADO',
            'descripcion' => 'Paquete Eliminado',
            'user_id' => Auth::user()->name,
            'codigo' => $p->codigo,
        ]);
    }

    public function abrirModal()
    {
        $this->reset(['paquete_id', 'codigo', 'destinatario', 'cuidad', 'peso', 'destino', 'observacion', 'certificacion']);
        $this->cantidad = 1;
        $this->modal = true;
    }

    public function cerrarModal()
    {
        $this->modal = false;
    }

    public function editar($id)
    {
        $p = Paquete::findOrFail($id);
        $this->paquete_id   = $p->id;
        $this->codigo       = $p->codigo;
        $this->destinatario = $p->destinatario;
        $this->cuidad       = $p->cuidad;
        $this->destino      = $p->destino;
        $this->peso         = $p->peso;
        $this->observacion  = $p->observacion;
        $this->certificacion = (bool) $p->certificacion;
        $this->modal        = true;
    }

    public function guardar()
    {
        $this->validate();

        $data = [
            'codigo'        => strtoupper($this->codigo),
            'destinatario'  => strtoupper($this->destinatario),
            'cuidad'        => strtoupper($this->cuidad),
            'destino'       => $this->destino,
            'peso'          => $this->peso,
            'observacion'   => strtoupper($this->observacion),
            'certificacion' => $this->certificacion ? 1 : 0,
            'cantidad'      => $this->cantidad,
        ];

        if ($this->paquete_id) {
            $p = Paquete::findOrFail($this->paquete_id);
            $p->update($data);

            session()->flash('message', 'Paquete actualizado.');
            Evento::create([
                'accion'      => 'EDICION',
                'descripcion' => 'Paquete Editado',
                'user_id'     => Auth::user()->name,
                'codigo'      => $p->codigo,
            ]);
        } else {
            $data['estado'] = 'ENVIANDO';
            $data['user']   = Auth::user()->name;
            Paquete::create($data);

            session()->flash('message', 'Paquete registrado como ENVIANDO.');
            Evento::create([
                'accion'      => 'ENVIANDO',
                'descripcion' => 'Paquete asignado para envio',
                'user_id'     => Auth::user()->name,
                'codigo'      => $data['codigo'],
            ]);
        }

        $this->cerrarModal();
        $this->reset(['paquete_id', 'codigo', 'destinatario', 'cuidad', 'peso', 'destino', 'observacion', 'certificacion', 'cantidad']);
    }

    public function abrirModalCantidad()
    {
        $this->modalCantidad = true;
    }

    public function cerrarModalCantidad()
    {
        $this->modalCantidad = false;
    }

    public function confirmarCantidad()
    {
        $this->validate();

        // 1) Crear el paquete y capturarlo en $paquete
        $paquete = Paquete::create([
            'codigo'        => strtoupper($this->codigo),
            'destinatario'  => strtoupper($this->destinatario),
            'cuidad'        => strtoupper($this->cuidad),
            'destino'       => $this->destino,
            'peso'          => $this->peso,
            'observacion'   => strtoupper($this->observacion),
            'certificacion' => $this->certificacion ? 1 : 0,
            'cantidad'      => $this->cantidad,
            'estado'        => 'ENVIANDO',
            'user'          => Auth::user()->name, // si realmente quieres guardar el nombre aquí
        ]);

        // 2) Crear el evento usando el ID de usuario y el código del paquete
        Evento::create([
            'accion'      => 'ENVIANDO',
            'descripcion' => "Se enviaron {$this->cantidad} paquetes",
            'user_id'     => Auth::id(),           // ahora es un entero
            'codigo'      => $paquete->codigo,     // tomamos el código del paquete creado
        ]);

        // 3) Mensaje flash y restablecer propiedades
        session()->flash('message', 'Paquete creado y despachado correctamente.');

        $this->reset([
            'codigo',
            'destinatario',
            'cuidad',
            'destino',
            'peso',
            'observacion',
            'certificacion',
            'cantidad',
        ]);

        // 4) Cerrar modal y resetear paginación
        $this->modalCantidad = false;
        $this->resetPage();
    }

    public function render()
    {
        $paquetes = Paquete::where('estado', 'ENVIANDO')
            ->where(function ($q) {
                $q->where('codigo', 'like', '%' . $this->search . '%')
                    ->orWhere('cuidad', 'like', '%' . $this->search . '%')
                    ->orWhere('observacion', 'like', '%' . $this->search . '%');
            })
            ->orderBy('id', 'desc')
            ->paginate(10);

        $empresas = Empresa::orderBy('nombre')->get();

        return view('livewire.enviar', compact('paquetes', 'empresas'));
    }
}

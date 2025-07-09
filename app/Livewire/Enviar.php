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
use PDF;

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
        // 1. Tomamos el valor ingresado
        $this->search = trim($this->searchInput);

        // 2. Si está vacío, mostramos mensaje y salimos
        if (! $this->search) {
            session()->flash('message', 'Debe ingresar un código o término para buscar.');
            return;
        }

        // 3. Reiniciamos la paginación para que la búsqueda empiece desde la página 1
        $this->resetPage();

        // ¡Listo! El método render() se encargará de filtrar los resultados
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

        // 1) Obtener los paquetes a procesar
        $packages = Paquete::whereIn('id', $this->selected)->get();

        // 2) Calcular precio y actualizar estado de cada paquete
        foreach ($packages as $paquete) {
            // 2.1 Buscar empresa
            $empresaModel = Empresa::whereRaw('UPPER(nombre) = ?', [strtoupper($paquete->destinatario)])->first();

            // 2.2 Buscar categoría de peso
            $pesoCat = Peso::where('min', '<=', $paquete->peso)
                ->where('max', '>=', $paquete->peso)
                ->first();

            $precioUnitario = 0;
            if ($empresaModel && $pesoCat) {
                // 2.3 Obtener tarifa
                $tarifa = Tarifario::where('empresa', $empresaModel->id)
                    ->where('peso', $pesoCat->id)
                    ->first();

                if ($tarifa) {
                    $col = strtolower($paquete->destino);
                    if (isset($tarifa->$col)) {
                        $precioUnitario = $tarifa->$col;
                    }
                }
            }

            // 2.4 Extra certificación
            if ($paquete->certificacion) {
                $precioUnitario += 8;
            }

            // 2.5 Multiplicar por la cantidad
            $precioFinal = $precioUnitario * $paquete->cantidad;

            // 2.6 Actualizar paquete con precio total y estado
            $paquete->update([
                'estado' => 'DESPACHADO',
                'precio' => $precioFinal,
            ]);
        }

        // 3) Registrar un evento por cada paquete
        foreach ($packages as $pkg) {
            Evento::create([
                'accion'      => 'DESPACHADO',
                'descripcion' => 'Paquete Despachado a Destinatario',
                'user_id'     => Auth::user()->name,
                'codigo'      => $pkg->codigo,
            ]);
        }

        // 4) Reiniciar selección y paginación
        $this->selected  = [];
        $this->selectAll = false;
        $this->resetPage();

        // 5) Generar y forzar descarga de PDF
        //    Asegúrate de tener configurada la vista 'pdf.despacho' (o crea una nueva como 'pdf.recepcion')
        $pdf = PDF::loadView('pdf.despacho', ['packages' => $packages]);
        return response()->streamDownload(
            fn() => print($pdf->output()),
            'recepcion_' . now()->format('Ymd_His') . '.pdf'
        );
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
        $this->cantidad     = $p->cantidad;
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

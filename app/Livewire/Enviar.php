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
use PDF;

class Enviar extends Component
{
    use WithPagination;

    public $search = '';
    public $searchInput = '';
    public $selectAll = false;
    public $selected = [];

    // Modal único
    public $modal = false;
    public $paquete_id = null;

    // Campos del formulario
    public $codigo;
    public $destinatario;
    public $cuidad;
    public $destino;
    public $peso;
    public $cantidad = 1;
    public $observacion;
    public $certificacion = false;
    public $grupo = false;
    public $almacenaje = false;
    public $pda;


    protected $paginationTheme = 'bootstrap';

    protected $rules = [
        'codigo'        => 'required|string|max:50',
        'destinatario'  => 'required|string|max:100',
        'cuidad'        => 'nullable|string|max:50',
        'destino'       => 'required|string|max:50',
        'peso'          => 'nullable|numeric',
        'cantidad'      => 'required|integer|min:1',
        'observacion'   => 'nullable|string|max:255',
        'pda'           => 'nullable|string|max:100',
        'certificacion' => 'boolean',
        'grupo'         => 'boolean',
        'almacenaje'    => 'boolean',
    ];

    public function mount()
    {
        $this->searchInput = $this->search;
    }

    public function buscar()
    {
        $this->search = trim($this->searchInput);
        if (!$this->search) {
            session()->flash('message', 'Debe ingresar un código o término para buscar.');
            return;
        }
        $this->resetPage();
    }

    public function toggleSelectAll()
    {
        $this->selectAll = !$this->selectAll;
        if ($this->selectAll) {
            $this->selected = Paquete::where('estado', 'ENVIANDO')
                ->where(function ($q) {
                    $q->where('codigo', 'like', "%{$this->search}%")
                        ->orWhere('cuidad', 'like', "%{$this->search}%")
                        ->orWhere('observacion', 'like', "%{$this->search}%");
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

        $packages = Paquete::whereIn('id', $this->selected)->get();

        foreach ($packages as $p) {
            // 1) Buscamos empresa y categoría de peso
            $empresa = Empresa::whereRaw('UPPER(nombre)=?', [strtoupper($p->destinatario)])->first();
            $pesoCat = Peso::where('min', '<=', $p->peso)
                ->where('max', '>=', $p->peso)
                ->first();

            // 2) Obtenemos tarifa base
            $unit = 0;
            if ($empresa && $pesoCat) {
                $tarifa = Tarifario::where('empresa', $empresa->id)
                    ->where('peso', $pesoCat->id)
                    ->first();

                if ($tarifa && isset($tarifa->{strtolower($p->destino)})) {
                    $unit = $tarifa->{strtolower($p->destino)};
                }
            }

            // 3) Extra 
            if ($p->certificacion) {
                $unit += 8;
            }

            if ($p->almacenaje) {
                $unit += 15;
            }

            // 4) Determinamos cuántas unidades multiplicar:
            //    - Si grupo == 1: multiplicamos por la cantidad real
            //    - Si grupo == 0: multiplicamos solo por 1
            $multiplier = $p->grupo ? $p->cantidad : 1;

            // 5) Cálculo final
            $total = $unit * $multiplier;

            // 6) Actualizamos el paquete y registramos evento
            $p->update([
                'estado' => 'DESPACHADO',
                'precio' => $total,
            ]);

            Evento::create([
                'accion'      => 'DESPACHADO',
                'descripcion' => 'Paquete Despachado',
                'user_id'     => Auth::user()->name,
                'codigo'      => $p->codigo,
            ]);
        }

        // 7) Reset de selección y paginación
        $this->selected  = [];
        $this->selectAll = false;
        $this->resetPage();

        // 8) Generación de PDF
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
        Evento::create([
            'accion'      => 'ELIMINADO',
            'descripcion' => 'Paquete Eliminado',
            'user_id'     => Auth::user()->name,
            'codigo'      => $p->codigo,
        ]);
        session()->flash('message', 'Paquete eliminado permanentemente.');
        $this->resetPage();
    }

    public function abrirModal()
    {
        $this->reset(['paquete_id', 'codigo', 'destinatario', 'cuidad', 'destino', 'peso', 'cantidad', 'observacion', 'grupo', 'certificacion', 'almacenaje', 'pda']);
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
        $this->cantidad     = $p->cantidad;
        $this->observacion  = $p->observacion;
        $this->pda          = $p->pda;
        $this->certificacion = (bool)$p->certificacion;
        $this->grupo         = (bool) $p->grupo;
        $this->almacenaje   = (bool) $p->almacenaje;
        $this->modal        = true;
    }

    public function guardar()
    {
        $this->validate();
        $data = [
            'codigo'       => strtoupper($this->codigo),
            'destinatario' => strtoupper($this->destinatario),
            'cuidad'       => strtoupper($this->cuidad),
            'destino'      => $this->destino,
            'peso'         => $this->peso,
            'cantidad'     => $this->cantidad,
            'pda'           => $this->pda,
            'observacion'  => strtoupper($this->observacion),
            'certificacion' => $this->certificacion ? 1 : 0,
            'grupo'         => $this->grupo ? 1 : 0,
            'almacenaje'    => $this->almacenaje ? 1 : 0,
        ];

        if ($this->paquete_id) {
            $p = Paquete::findOrFail($this->paquete_id);
            $p->update($data);
            Evento::create([
                'accion'      => 'EDICION',
                'descripcion' => 'Paquete Editado',
                'user_id'     => Auth::user()->name,
                'codigo'      => $p->codigo,
            ]);
            session()->flash('message', 'Paquete actualizado.');
        } else {
            $data['estado'] = 'ENVIANDO';
            $data['user']   = Auth::user()->name;
            $p = Paquete::create($data);
            Evento::create([
                'accion'      => 'ENVIANDO',
                'descripcion' => 'Paquete asignado para envío',
                'user_id'     => Auth::user()->name,
                'codigo'      => $p->codigo,
            ]);
            session()->flash('message', 'Paquete registrado como ENVIANDO.');
        }

        $this->cerrarModal();
    }

    public function render()
    {
        $paquetes = Paquete::where('estado', 'ENVIANDO')
            ->where(
                fn($q) =>
                $q->where('codigo', 'like', "%{$this->search}%")
                    ->orWhere('cuidad', 'like', "%{$this->search}%")
                    ->orWhere('observacion', 'like', "%{$this->search}%")
            )
            ->orderBy('id', 'desc')
            ->paginate(10);

        $empresas = Empresa::orderBy('nombre')->get();

        return view('livewire.enviar', compact('paquetes', 'empresas'));
    }
}

<?php

namespace App\Livewire;

use App\Models\Paquete;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Exports\AlmacenExport;
use Maatwebsite\Excel\Facades\Excel;
use PDF; 
use Carbon\Carbon;

class Almacen extends Component
{
    use WithPagination;

    public $search = '';
    public $searchInput = '';

    public $dateFrom;
    public $dateTo;

    public $modal = false;
    public $paquete_id, $codigo, $destinatario, $estado, $cuidad, $peso, $user, $observacion;

    // checkbox
    public $selectAll = false;
    public $selected = [];

    protected $paginationTheme = 'bootstrap';

    protected $rules = [
        'codigo'       => 'required|string|max:50',
        'destinatario' => 'required|string|max:100',
        'cuidad'       => 'nullable|string|max:50',
        'peso'         => 'nullable|numeric',
        'observacion'  => 'nullable|string|max:255',
    ];

    public function mount()
    {
        $this->searchInput = $this->search;
        // Por defecto, rango: primeros y últimos días del mes actual
        $this->dateFrom = Carbon::now()->startOfMonth()->toDateString();
        $this->dateTo   = Carbon::now()->endOfMonth()->toDateString();
    }

    public function buscar()
    {
        $this->search = $this->searchInput;
        $this->resetPage();
    }

    public function exportarExcel()
    {
        $from = Carbon::parse($this->dateFrom)->startOfDay();
        $to   = Carbon::parse($this->dateTo)->endOfDay();

        return Excel::download(
            new AlmacenExport($this->search, $from, $to),
            "paquetes_{$this->dateFrom}_a_{$this->dateTo}.xlsx"
        );
    }

    public function abrirModal()
    {
        $this->reset([
            'paquete_id',
            'codigo',
            'destinatario',
            'estado',
            'cuidad',
            'peso',
            'user',
            'observacion'
        ]);
        $this->modal = true;
    }

    public function cerrarModal()
    {
        $this->modal = false;
    }

    public function guardar()
    {
        $this->validate();

        $data = [
            'codigo'       => strtoupper($this->codigo),
            'destinatario' => strtoupper($this->destinatario),
            'cuidad'       => strtoupper($this->cuidad),
            'peso'         => $this->peso,
            'observacion'  => strtoupper($this->observacion),
        ];

        if ($this->paquete_id) {
            $data['estado'] = strtoupper($this->estado);
            $data['user']   = strtoupper($this->user);
        } else {
            $data['estado'] = 'ALMACEN';
            $data['user']   = Auth::user()->name;
        }

        Paquete::updateOrCreate(
            ['id' => $this->paquete_id],
            $data
        );

        session()->flash(
            'message',
            $this->paquete_id ? 'Paquete actualizado.' : 'Paquete registrado.'
        );

        $this->cerrarModal();
        $this->reset([
            'paquete_id',
            'codigo',
            'destinatario',
            'estado',
            'cuidad',
            'peso',
            'user',
            'observacion'
        ]);
    }

    public function editar($id)
    {
        $p = Paquete::findOrFail($id);
        $this->paquete_id   = $p->id;
        $this->codigo       = $p->codigo;
        $this->destinatario = $p->destinatario;
        $this->estado       = $p->estado;
        $this->cuidad       = $p->cuidad;
        $this->peso         = $p->peso;
        $this->user         = $p->user;
        $this->observacion  = $p->observacion;
        $this->modal        = true;
    }

    public function toggleSelectAll()
    {
        $this->selectAll = ! $this->selectAll;

        if ($this->selectAll) {
            $this->selected = Paquete::where('estado', 'ALMACEN')
                ->where(function ($q) {
                    $q->where('codigo', 'like', "%{$this->search}%")
                        ->orWhere('cuidad', 'like', "%{$this->search}%")
                        ->orWhere('observacion', 'like', "%{$this->search}%");
                })
                ->pluck('id')->toArray();
        } else {
            $this->selected = [];
        }
    }

    public function darBajaSeleccionados()
    {
        if (empty($this->selected)) {
            session()->flash('message', 'No hay paquetes seleccionados.');
            return;
        }

        // 1) Obtener los paquetes a procesar
        $packages = Paquete::whereIn('id', $this->selected)->get();

        // 2) Marcar como INVENTARIO y soft-delete
        Paquete::whereIn('id', $this->selected)->update(['estado' => 'INVENTARIO']);
        Paquete::whereIn('id', $this->selected)->delete();

        // 3) Reiniciar selección
        $this->selected  = [];
        $this->selectAll = false;

        // 4) Generar y forzar descarga de PDF
        $pdf = PDF::loadView('pdf.despacho', ['packages' => $packages]);
        return response()->streamDownload(
            fn() => print($pdf->output()),
            'despacho_' . now()->format('Ymd_His') . '.pdf'
        );
    }

    public function render()
    {
        $from = Carbon::parse($this->dateFrom)->startOfDay();
        $to   = Carbon::parse($this->dateTo)->endOfDay();

        $paquetes = Paquete::where('estado', 'ALMACEN')
            ->where(function ($q) {
                $q->where('codigo', 'like', "%{$this->search}%")
                    ->orWhere('cuidad', 'like', "%{$this->search}%")
                    ->orWhere('observacion', 'like', "%{$this->search}%");
            })
            ->whereBetween('created_at', [$from, $to])
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.almacen', compact('paquetes'));
    }
}

<?php

namespace App\Livewire;

use App\Models\Paquete;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class Inventario extends Component
{
    use WithPagination;

    public $search = '';
    public $searchInput = '';
    public $modal = false;
    public $paquete_id;
    public $codigo;
    public $destinatario;
    public $cuidad;
    public $peso;
    public $observacion;

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
    }

    public function buscar()
    {
        $this->search = $this->searchInput;
        $this->resetPage();
    }

    public function abrirModal()
    {
        $this->reset(['paquete_id', 'codigo', 'destinatario', 'cuidad', 'peso', 'observacion']);
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
            'estado'       => 'INVENTARIO',
            'user'         => Auth::user()->name,
        ];

        Paquete::updateOrCreate(
            ['id' => $this->paquete_id],
            $data
        );

        session()->flash(
            'message',
            $this->paquete_id
                ? 'Paquete actualizado en Inventario.'
                : 'Paquete agregado a Inventario.'
        );

        $this->cerrarModal();
    }

    public function editar($id)
    {
        $p = Paquete::findOrFail($id);

        $this->paquete_id   = $p->id;
        $this->codigo       = $p->codigo;
        $this->destinatario = $p->destinatario;
        $this->cuidad       = $p->cuidad;
        $this->peso         = $p->peso;
        $this->observacion  = $p->observacion;
        $this->modal        = true;
    }

    public function restaurar($id)
    {
        $p = Paquete::withTrashed()->findOrFail($id);
        $p->restore();
        $p->estado = 'ALMACEN';
        $p->save();

        session()->flash('message', 'Paquete restaurado y dado de alta.');
        $this->resetPage();
    }

    public function render()
    {
        // Mostrar solo paquetes eliminados (soft deletes) en Inventario
        $paquetes = Paquete::onlyTrashed()
            ->where('estado', 'INVENTARIO')
            ->where(function ($query) {
                $query->where('codigo', 'like', "%{$this->search}%")
                    ->orWhere('destinatario', 'like', "%{$this->search}%")
                    ->orWhere('cuidad', 'like', "%{$this->search}%");
            })
            ->orderBy('deleted_at', 'desc')
            ->paginate(10);

        return view('livewire.inventario', compact('paquetes'));
    }
}

<?php

namespace App\Livewire;

use App\Models\Paquete;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class Almacen extends Component
{
    use WithPagination;

    public $search = '';
    public $searchInput = '';
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
    }

    public function buscar()
    {
        $this->search = $this->searchInput;
        $this->resetPage();
    }

    public function abrirModal()
    {
        $this->reset(['paquete_id', 'codigo', 'destinatario', 'estado', 'cuidad', 'peso', 'user', 'observacion']);
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

        session()->flash('message', $this->paquete_id ? 'Paquete actualizado.' : 'Paquete registrado.');
        $this->cerrarModal();
        $this->reset(['paquete_id', 'codigo', 'destinatario', 'estado', 'cuidad', 'peso', 'user', 'observacion']);
    }

    public function editar($id)
    {
        $p = Paquete::findOrFail($id);
        $this->paquete_id  = $p->id;
        $this->codigo      = $p->codigo;
        $this->destinatario = $p->destinatario;
        $this->estado      = $p->estado;
        $this->cuidad      = $p->cuidad;
        $this->peso        = $p->peso;
        $this->user        = $p->user;
        $this->observacion = $p->observacion;
        $this->modal       = true;
    }

    public function toggleSelectAll()
    {
        $this->selectAll = ! $this->selectAll;

        if ($this->selectAll) {
            $this->selected = Paquete::where(function ($q) {
                $q->where('codigo', 'like', '%' . $this->search . '%')
                    ->orWhere('estado', 'like', '%' . $this->search . '%');
            })
                ->orderBy('id', 'desc')
                ->paginate(10)
                ->pluck('id')
                ->toArray();
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

        // Primero marcamos como INVENTARIO
        Paquete::whereIn('id', $this->selected)
            ->update(['estado' => 'INVENTARIO']);

        // Después los eliminamos definitivamente
        Paquete::whereIn('id', $this->selected)
            ->delete();

        // Limpiamos selección y estado de "select all"
        $this->selected  = [];
        $this->selectAll = false;

        session()->flash('message', 'Paquetes movidos a INVENTARIO y eliminados correctamente.');
    }

    public function render()
    {
        $paquetes = Paquete::where('estado', 'ALMACEN')               // solo paquetes en ALMACEN
            ->where(function ($query) {
                $query->where('codigo', 'like', '%' . $this->search . '%')
                    ->orWhere('cuidad', 'like', '%' . $this->search . '%')
                    ->orWhere('observacion', 'like', '%' . $this->search . '%');
            })
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.almacen', compact('paquetes'));
    }
}

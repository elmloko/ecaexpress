<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Tarifario;

class Tarifas extends Component
{
    use WithPagination;

    public $search = '';
    public $searchInput = '';
    public $modal = false;
    public $tarifario_id;
    public $peso, $empresa, $local, $nacional, $camiri, $sud, $norte, $centro, $euro, $asia;

    protected $paginationTheme = 'bootstrap';
    protected $updatesQueryString = ['search'];

    protected $rules = [
        'empresa'  => 'required|string|max:50',
        'peso'     => 'required|numeric|min:0',
        'local'    => 'nullable|numeric|min:0',
        'nacional' => 'nullable|numeric|min:0',
        'camiri'   => 'nullable|numeric|min:0',
        'sud'      => 'nullable|numeric|min:0',
        'norte'    => 'nullable|numeric|min:0',
        'centro'   => 'nullable|numeric|min:0',
        'euro'     => 'nullable|numeric|min:0',
        'asia'     => 'nullable|numeric|min:0',
    ];

    public function mount() { $this->searchInput = $this->search; }

    public function buscar() { $this->search = $this->searchInput; $this->resetPage(); }

    public function abrirModal()
    {
        $this->reset(['tarifario_id', 'peso', 'empresa', 'local', 'nacional', 'camiri', 'sud', 'norte', 'centro', 'euro', 'asia']);
        $this->modal = true;
    }

    public function cerrarModal() { $this->modal = false; }

    public function guardar()
    {
        $this->validate();

        Tarifario::updateOrCreate(
            ['id' => $this->tarifario_id],
            [
                'peso'     => $this->peso,
                'empresa'  => strtoupper($this->empresa),
                'local'    => $this->local,
                'nacional' => $this->nacional,
                'camiri'   => $this->camiri,
                'sud'      => $this->sud,
                'norte'    => $this->norte,
                'centro'   => $this->centro,
                'euro'     => $this->euro,
                'asia'     => $this->asia,
            ]
        );

        session()->flash('message', $this->tarifario_id ? 'Tarifario actualizado.' : 'Tarifario registrado.');
        $this->cerrarModal();
    }

    public function editar($id)
    {
        $registro = Tarifario::findOrFail($id);
        $this->fill($registro->toArray());
        $this->modal = true;
    }

    public function eliminar($id)
    {
        Tarifario::findOrFail($id)->delete();
        session()->flash('message', 'Tarifario eliminado correctamente.');
    }

    public function render()
    {
        $tarifarios = Tarifario::where('empresa', 'like', '%' . $this->search . '%')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.tarifas', compact('tarifarios'));
    }
}

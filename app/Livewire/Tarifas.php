<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Tarifario;
use App\Models\Empresa;
use App\Models\Peso;

class Tarifas extends Component
{
    use WithPagination;

    public $search = '';
    public $searchInput = '';
    public $modal = false;
    public $tarifario_id;
    public $empresa, $peso, $local, $nacional, $camiri, $sud, $norte, $centro, $euro, $asia;
    public $activeEmpresaId;

    protected $paginationTheme = 'bootstrap';
    protected $updatesQueryString = ['search'];

    protected $rules = [
        'empresa'  => 'required|numeric|exists:empresa,id',
        'peso'     => 'required|numeric|exists:peso,id',
        'local'    => 'nullable|numeric|min:0',
        'nacional' => 'nullable|numeric|min:0',
        'camiri'   => 'nullable|numeric|min:0',
        'sud'      => 'nullable|numeric|min:0',
        'norte'    => 'nullable|numeric|min:0',
        'centro'   => 'nullable|numeric|min:0',
        'euro'     => 'nullable|numeric|min:0',
        'asia'     => 'nullable|numeric|min:0',
    ];

    public function mount()
    {
        $this->searchInput = $this->search;
        $this->activeEmpresaId = Empresa::first()?->id;
    }

    public function selectEmpresa($empresaId)
    {
        $this->activeEmpresaId = $empresaId;
        $this->resetPage();
    }

    public function buscar()
    {
        $this->search = $this->searchInput;
        $this->resetPage();
    }

    public function abrirModal()
    {
        $this->reset(['tarifario_id', 'peso', 'empresa', 'local', 'nacional', 'camiri', 'sud', 'norte', 'centro', 'euro', 'asia']);
        $this->empresa = $this->activeEmpresaId;
        $this->modal = true;
    }

    public function cerrarModal()
    {
        $this->modal = false;
    }

    public function guardar()
    {
        $this->validate();

        Tarifario::updateOrCreate(
            ['id' => $this->tarifario_id],
            [
                'peso'     => $this->peso,
                'empresa'  => $this->empresa,
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
        $empresas = Empresa::all();
        $pesos = Peso::all();

        $tarifarios = Tarifario::with(['empresaDatos', 'pesoRango'])
            ->where('empresa', $this->activeEmpresaId)
            ->whereHas('empresaDatos', fn($q) => $q->where('nombre', 'like', '%' . $this->search . '%'))
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.tarifas', compact('tarifarios', 'empresas', 'pesos'));
    }
}

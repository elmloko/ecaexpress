<?php
// app/Livewire/Recibir.php

namespace App\Livewire;

use App\Models\Paquete;
use App\Models\Empresa;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class Recibir extends Component
{
    use WithPagination;

    public $search = '';
    public $searchInput = '';
    public $selectAll = false;
    public $selected = [];

    // Para el modal de Crear/Editar
    public $modal = false;
    public $paquete_id;
    public $codigo;
    public $destinatario;
    public $cuidad;
    public $peso;
    public $destino;
    public $observacion;

    protected $paginationTheme = 'bootstrap';

    protected $rules = [
        'codigo'       => 'required|string|max:50',
        'destinatario' => 'required|string|max:100',
        'cuidad'       => 'nullable|string|max:50',
        'peso'         => 'nullable|numeric',
        'destino'      => 'required|string|max:50',
        'observacion'  => 'nullable|string|max:255',
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
            'verify'           => false,
            'curl'             => [
                CURLOPT_SSLVERSION   => CURL_SSLVERSION_TLSv1_2,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_IPRESOLVE    => CURL_IPRESOLVE_V4,
            ],
        ])
            ->withToken(config('services.correos.token'))  // ← aquí
            ->acceptJson()
            ->get($url);

        if (! $response->successful()) {
            session()->flash('message', "Error API HTTP {$response->status()}");
            return;
        }

        $data = $response->json();

        $paquete = \App\Models\Paquete::updateOrCreate(
            ['codigo' => $data['CODIGO']],
            [
                'destinatario' => strtoupper($data['DESTINATARIO']),
                'estado'       => 'RECIBIDO',                  // <— forzamos RECIBIDO
                'cuidad'       => strtoupper($data['CUIDAD']),
                'peso'         => floatval($data['PESO']),
                'user'         => Auth::user()->name,          // <— el usuario logueado
            ]
        );

        session()->flash('message', "Paquete {$paquete->codigo} guardado o actualizado.");
        $this->reset(['searchInput', 'search']);
        $this->resetPage();
    }

    public function toggleSelectAll()
    {
        $this->selectAll = ! $this->selectAll;
        if ($this->selectAll) {
            $this->selected = Paquete::where('estado', 'RECIBIDO')
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

        Paquete::whereIn('id', $this->selected)
            ->update(['estado' => 'ALMACEN']);

        $this->selected  = [];
        $this->selectAll = false;

        session()->flash('message', 'Paquetes recibidos y marcados como ALMACEN correctamente.');
    }

    public function eliminarPaquete($id)
    {
        $p = Paquete::findOrFail($id);
        $p->forceDelete();
        $this->resetPage();
        session()->flash('message', 'Paquete eliminado permanentemente.');
    }

    // --- Lógica Crear / Editar ---
    public function abrirModal()
    {
        $this->reset(['paquete_id', 'codigo', 'destinatario', 'cuidad', 'peso', 'destino', 'observacion']);
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
            'destino'       => strtoupper($this->destino),
            'peso'         => $this->peso,
            'observacion'  => strtoupper($this->observacion),
        ];

        if ($this->paquete_id) {
            // Edición: mantenemos estado y usuario actuales
            $model = Paquete::findOrFail($this->paquete_id);
            $model->update($data);
            session()->flash('message', 'Paquete actualizado.');
        } else {
            // Nuevo: por defecto lo marcamos como RECIBIDO
            $data['estado'] = 'RECIBIDO';
            $data['user']   = Auth::user()->name;
            Paquete::create($data);
            session()->flash('message', 'Paquete registrado como RECIBIDO.');
        }

        $this->cerrarModal();
        $this->reset(['paquete_id', 'codigo', 'destinatario', 'cuidad', 'peso', 'observacion']);
    }

    public function editar($id)
    {
        $p = Paquete::findOrFail($id);
        $this->paquete_id  = $p->id;
        $this->codigo      = $p->codigo;
        $this->destinatario = $p->destinatario;
        $this->cuidad      = $p->cuidad;
        $this->destino      = $p->destino;
        $this->peso        = $p->peso;
        $this->observacion = $p->observacion;
        $this->modal       = true;
    }

    public function render()
    {
        $paquetes = Paquete::where('estado', 'RECIBIDO')
            ->where(function ($query) {
                $query->where('codigo', 'like', '%' . $this->search . '%')
                    ->orWhere('cuidad', 'like', '%' . $this->search . '%')
                    ->orWhere('observacion', 'like', '%' . $this->search . '%');
            })
            ->orderBy('id', 'desc')
            ->paginate(10);

        // Carga todas las empresas ordenadas por nombre
        $empresas = Empresa::orderBy('nombre')->get();

        return view('livewire.recibir', compact('paquetes', 'empresas'));
    }
}

<div class="container-fluid">
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Tarifario Registrado</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                    <li class="breadcrumb-item active">Tarifario</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <button class="btn btn-success" wire:click="abrirModal">
                    <i class="fas fa-plus-circle"></i> Crear Tarifario
                </button>
                <div class="input-group" style="max-width: 400px;">
                    <input type="text" class="form-control" placeholder="Buscar por empresa..." wire:model.defer="searchInput">
                    <div class="input-group-append">
                        <button class="btn btn-primary" wire:click="buscar"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </div>

            <div class="card-body p-0">
                @if (session()->has('message'))
                    <div class="alert alert-success m-3">{{ session('message') }}</div>
                @endif

                <table class="table table-striped table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Empresa</th>
                            <th>Peso</th>
                            <th>Local</th>
                            <th>Nacional</th>
                            <th>Camiri</th>
                            <th>Sud</th>
                            <th>Norte</th>
                            <th>Centro</th>
                            <th>Euro</th>
                            <th>Asia</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tarifarios as $tarifa)
                            <tr>
                                <td>{{ $tarifa->empresa }}</td>
                                <td>{{ $tarifa->peso }}</td>
                                <td>{{ $tarifa->local }}</td>
                                <td>{{ $tarifa->nacional }}</td>
                                <td>{{ $tarifa->camiri }}</td>
                                <td>{{ $tarifa->sud }}</td>
                                <td>{{ $tarifa->norte }}</td>
                                <td>{{ $tarifa->centro }}</td>
                                <td>{{ $tarifa->euro }}</td>
                                <td>{{ $tarifa->asia }}</td>
                                <td>
                                    <button class="btn btn-sm btn-warning" wire:click="editar({{ $tarifa->id }})"><i class="fas fa-edit"></i></button>
                                    <button class="btn btn-sm btn-danger" wire:click="eliminar({{ $tarifa->id }})"
                                        onclick="return confirm('¿Deseas eliminar este tarifario?')"><i class="fas fa-trash-alt"></i></button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="11" class="text-center">No se encontraron registros.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="card-footer">
                {{ $tarifarios->links() }}
            </div>
        </div>
    </section>

    <!-- Modal -->
    <div class="modal fade @if ($modal) show d-block @endif" style="background: rgba(0,0,0,0.5);" role="dialog">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $tarifario_id ? 'Editar Tarifario' : 'Crear Tarifario' }}</h5>
                    <button type="button" class="close" wire:click="cerrarModal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        @foreach (['empresa', 'peso', 'local', 'nacional', 'camiri', 'sud', 'norte', 'centro', 'euro', 'asia'] as $campo)
                            <div class="col-md-4 mb-3">
                                <label class="text-capitalize">{{ ucfirst($campo) }}</label>
                                <input 
                                    type="{{ $campo === 'empresa' ? 'text' : 'number' }}"
                                    step="0.01" 
                                    wire:model.defer="{{ $campo }}"
                                    class="form-control"
                                    placeholder="{{ ucfirst($campo) }}"
                                    style="{{ $campo === 'empresa' ? 'text-transform: uppercase;' : '' }}"
                                >
                                @error($campo)
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer">
                    <button wire:click="guardar" class="btn btn-primary">{{ $tarifario_id ? 'Actualizar' : 'Guardar' }}</button>
                    <button type="button" class="btn btn-secondary" wire:click="cerrarModal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
</div>

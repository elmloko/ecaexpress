<!-- resources/views/livewire/recibir.blade.php -->
<div class="container-fluid">
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Paquetes por Enviar</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                    <li class="breadcrumb-item active">Recibir</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="card">
            <div class="card-header row align-items-center">
                <div class="col-md-6 d-flex">
                    <!-- Botón para crear nuevo paquete -->
                    <button class="btn btn-success ml-2" wire:click="abrirModal">
                        <i class="fas fa-plus-circle"></i> Crear Paquete
                    </button>
                </div>
                <div class="col-md-6 d-flex justify-content-end">
                    <div class="input-group" style="max-width: 400px;">
                        <input type="text" class="form-control" placeholder="Buscar Codigos..."
                            wire:model.defer="searchInput">
                        <div class="input-group-append">
                            <button class="btn btn-primary btn-flat" wire:click="buscar">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body p-0">
                @if (session()->has('message'))
                    <div class="alert alert-success m-3">{{ session('message') }}</div>
                @endif

                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" wire:click="toggleSelectAll"
                                    @if ($selectAll) checked @endif>
                            </th>
                            <th>Código</th>
                            <th>Empresa</th>
                            <th>Peso</th>
                            <th>Destino</th>
                            <th>Estado</th>
                            <th>Ciudad</th>
                            <th>Observación</th>
                            <th>Fecha envio</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($paquetes as $p)
                            <tr>
                                <td>
                                    <input type="checkbox" wire:model="selected" value="{{ $p->id }}">
                                </td>
                                <td>{{ $p->codigo }}</td>
                                <td>{{ $p->destinatario }}</td>
                                <td>{{ $p->peso }} kg</td>
                                <td>{{ strtoupper($p->destino) }}</td>
                                <td>{{ $p->estado }}</td>
                                <td>{{ $p->cuidad }}</td>
                                <td>{{ $p->created_at }}</td>
                                <td>{{ $p->observacion }}</td>
                                <td class="d-flex">
                                    <button class="btn btn-sm btn-warning mr-1"
                                        wire:click="editar({{ $p->id }})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    @hasrole('Administrador')
                                        <button class="btn btn-sm btn-danger"
                                            wire:click="eliminarPaquete({{ $p->id }})"
                                            onclick="return confirm('¿Eliminar este paquete de forma permanente?')">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    @endhasrole
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No hay resultados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <button class="btn btn-primary" wire:click="recibirSeleccionados"
                onclick="return confirm('¿Estás seguro de enviar los paquetes seleccionados?')">
                <i class="fas fa-inbox"></i> Enviar paquetes
            </button>
            <div class="card-footer clearfix">
                {{ $paquetes->links() }}
            </div>
        </div>
    </section>

    <!-- Modal Crear/Editar -->
    <div class="modal fade @if ($modal) show d-block @endif" tabindex="-1"
        style="background: rgba(0,0,0,0.5);" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $paquete_id ? 'Editar Paquete' : 'Crear Paquete' }}</h5>
                    <button type="button" class="close" wire:click="cerrarModal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- Columna izquierda -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Código</label>
                                <input type="text" wire:model.defer="codigo" class="form-control"
                                    style="text-transform: uppercase;">
                                @error('codigo')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Empresa</label>
                                <select wire:model.defer="destinatario" class="form-control"
                                    style="text-transform: uppercase;">
                                    <option value="">SELECCIONE...</option>
                                    @foreach ($empresas as $empresa)
                                        <option value="{{ strtoupper($empresa->nombre) }}">
                                            {{ strtoupper($empresa->nombre) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('destinatario')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Departamento</label>
                                <select wire:model.defer="cuidad" class="form-control"
                                    style="text-transform: uppercase;">
                                    <option value="">SELECCIONE...</option>
                                    <option value="BENI">BENI</option>
                                    <option value="CHUQUISACA">CHUQUISACA</option>
                                    <option value="COCHABAMBA">COCHABAMBA</option>
                                    <option value="LA PAZ">LA PAZ</option>
                                    <option value="ORURO">ORURO</option>
                                    <option value="PANDO">PANDO</option>
                                    <option value="POTOSI">POTOSI</option>
                                    <option value="SANTA CRUZ">SANTA CRUZ</option>
                                    <option value="TARIJA">TARIJA</option>

                                </select>
                            </div>
                            <div class="form-group">
                                <label>Destino</label>
                                <select wire:model.defer="destino" class="form-control"
                                    style="text-transform: uppercase;">
                                    <option value="">SELECCIONE...</option>
                                    <option value="local">LOCAL</option>
                                    <option value="nacional">NACIONAL</option>
                                    <option value="camiri">CAMIRI</option>
                                    <option value="sud">SUD AMERICA</option>
                                    <option value="centro">CENTRO AMERICA Y CARIBE</option>
                                    <option value="norte">NORTE AMERICA</option>
                                    <option value="euro">EUROPA Y AFRICA</option>
                                    <option value="asia">ASIA Y OCEANIA</option>
                                    <option value="Afganistán">Afganistán</option>
                                    <option value="Albania">Albania</option>
                                    <option value="Argelia">Argelia</option>
                                    <option value="Andorra">Andorra</option>
                                    <option value="Angola">Angola</option>
                                    <option value="Antigua y Barbuda">Antigua y Barbuda</option>
                                    <option value="Argentina">Argentina</option>
                                    <option value="Armenia">Armenia</option>
                                    <option value="Australia">Australia</option>
                                    <option value="Austria">Austria</option>
                                    <option value="Azerbaiyán">Azerbaiyán</option>
                                    <option value="Bahamas">Bahamas</option>
                                    <option value="Baréin">Baréin</option>
                                    <option value="Bangladés">Bangladés</option>
                                    <option value="Barbados">Barbados</option>
                                    <option value="Belarús">Belarús</option>
                                    <option value="Bélgica">Bélgica</option>
                                    <option value="Belice">Belice</option>
                                    <option value="Benín">Benín</option>
                                    <option value="Bután">Bután</option>
                                    <option value="Bolivia">Bolivia</option>
                                    <option value="Bosnia y Herzegovina">Bosnia y Herzegovina</option>
                                    <option value="Botsuana">Botsuana</option>
                                    <option value="Brasil">Brasil</option>
                                    <option value="Brunéi Darussalam">Brunéi Darussalam</option>
                                    <option value="Bulgaria">Bulgaria</option>
                                    <option value="Burkina Faso">Burkina Faso</option>
                                    <option value="Burundi">Burundi</option>
                                    <option value="Cabo Verde">Cabo Verde</option>
                                    <option value="Camboya">Camboya</option>
                                    <option value="Camerún">Camerún</option>
                                    <option value="Canadá">Canadá</option>
                                    <option value="República Centroafricana">República Centroafricana</option>
                                    <option value="Chad">Chad</option>
                                    <option value="Chile">Chile</option>
                                    <option value="China">China</option>
                                    <option value="Colombia">Colombia</option>
                                    <option value="Comoras">Comoras</option>
                                    <option value="República del Congo">República del Congo</option>
                                    <option value="República Democrática del Congo">República Democrática del Congo
                                    </option>
                                    <option value="Costa Rica">Costa Rica</option>
                                    <option value="Croacia">Croacia</option>
                                    <option value="Cuba">Cuba</option>
                                    <option value="Chipre">Chipre</option>
                                    <option value="República Checa">República Checa</option>
                                    <option value="Corea del Norte">Corea del Norte</option>
                                    <option value="Corea del Sur">Corea del Sur</option>
                                    <option value="Dinamarca">Dinamarca</option>
                                    <option value="Yibuti">Yibuti</option>
                                    <option value="Dominica">Dominica</option>
                                    <option value="República Dominicana">República Dominicana</option>
                                    <option value="Ecuador">Ecuador</option>
                                    <option value="Egipto">Egipto</option>
                                    <option value="El Salvador">El Salvador</option>
                                    <option value="Guinea Ecuatorial">Guinea Ecuatorial</option>
                                    <option value="Eritrea">Eritrea</option>
                                    <option value="Estonia">Estonia</option>
                                    <option value="Esuatini">Esuatini</option>
                                    <option value="Etiopía">Etiopía</option>
                                    <option value="Fiyi">Fiyi</option>
                                    <option value="Finlandia">Finlandia</option>
                                    <option value="Francia">Francia</option>
                                    <option value="Gabón">Gabón</option>
                                    <option value="Gambia">Gambia</option>
                                    <option value="Georgia">Georgia</option>
                                    <option value="Alemania">Alemania</option>
                                    <option value="Ghana">Ghana</option>
                                    <option value="Grecia">Grecia</option>
                                    <option value="Granada">Granada</option>
                                    <option value="Guatemala">Guatemala</option>
                                    <option value="Guinea">Guinea</option>
                                    <option value="Guinea-Bisáu">Guinea-Bisáu</option>
                                    <option value="Guyana">Guyana</option>
                                    <option value="Haití">Haití</option>
                                    <option value="Honduras">Honduras</option>
                                    <option value="Hungría">Hungría</option>
                                    <option value="Islandia">Islandia</option>
                                    <option value="India">India</option>
                                    <option value="Indonesia">Indonesia</option>
                                    <option value="Irán">Irán</option>
                                    <option value="Irak">Irak</option>
                                    <option value="Irlanda">Irlanda</option>
                                    <option value="Israel">Israel</option>
                                    <option value="Italia">Italia</option>
                                    <option value="Jamaica">Jamaica</option>
                                    <option value="Japón">Japón</option>
                                    <option value="Jordania">Jordania</option>
                                    <option value="Kazajistán">Kazajistán</option>
                                    <option value="Kenia">Kenia</option>
                                    <option value="Kiribati">Kiribati</option>
                                    <option value="Kuwait">Kuwait</option>
                                    <option value="Kirguistán">Kirguistán</option>
                                    <option value="Laos">Laos</option>
                                    <option value="Letonia">Letonia</option>
                                    <option value="Líbano">Líbano</option>
                                    <option value="Lesoto">Lesoto</option>
                                    <option value="Liberia">Liberia</option>
                                    <option value="Libia">Libia</option>
                                    <option value="Liechtenstein">Liechtenstein</option>
                                    <option value="Lituania">Lituania</option>
                                    <option value="Luxemburgo">Luxemburgo</option>
                                    <option value="Madagascar">Madagascar</option>
                                    <option value="Malaui">Malaui</option>
                                    <option value="Malasia">Malasia</option>
                                    <option value="Maldivas">Maldivas</option>
                                    <option value="Malí">Malí</option>
                                    <option value="Malta">Malta</option>
                                    <option value="Islas Marshall">Islas Marshall</option>
                                    <option value="Mauritania">Mauritania</option>
                                    <option value="Mauricio">Mauricio</option>
                                    <option value="México">México</option>
                                    <option value="Estados Federados de Micronesia">Estados Federados de Micronesia
                                    </option>
                                    <option value="Moldavia">Moldavia</option>
                                    <option value="Mónaco">Mónaco</option>
                                    <option value="Mongolia">Mongolia</option>
                                    <option value="Montenegro">Montenegro</option>
                                    <option value="Marruecos">Marruecos</option>
                                    <option value="Mozambique">Mozambique</option>
                                    <option value="Myanmar">Myanmar</option>
                                    <option value="Namibia">Namibia</option>
                                    <option value="Nauru">Nauru</option>
                                    <option value="Nepal">Nepal</option>
                                    <option value="Países Bajos">Países Bajos</option>
                                    <option value="Nueva Zelanda">Nueva Zelanda</option>
                                    <option value="Nicaragua">Nicaragua</option>
                                    <option value="Níger">Níger</option>
                                    <option value="Nigeria">Nigeria</option>
                                    <option value="Macedonia del Norte">Macedonia del Norte</option>
                                    <option value="Noruega">Noruega</option>
                                    <option value="Omán">Omán</option>
                                    <option value="Pakistán">Pakistán</option>
                                    <option value="Palaos">Palaos</option>
                                    <option value="Panamá">Panamá</option>
                                    <option value="Papúa Nueva Guinea">Papúa Nueva Guinea</option>
                                    <option value="Paraguay">Paraguay</option>
                                    <option value="Perú">Perú</option>
                                    <option value="Filipinas">Filipinas</option>
                                    <option value="Polonia">Polonia</option>
                                    <option value="Portugal">Portugal</option>
                                    <option value="Catar">Catar</option>
                                    <option value="Rumania">Rumania</option>
                                    <option value="Federación de Rusia">Federación de Rusia</option>
                                    <option value="Ruanda">Ruanda</option>
                                    <option value="San Cristóbal y Nieves">San Cristóbal y Nieves</option>
                                    <option value="Santa Lucía">Santa Lucía</option>
                                    <option value="San Vicente y las Granadinas">San Vicente y las Granadinas</option>
                                    <option value="Samoa">Samoa</option>
                                    <option value="San Marino">San Marino</option>
                                    <option value="Santo Tomé y Príncipe">Santo Tomé y Príncipe</option>
                                    <option value="Arabia Saudita">Arabia Saudita</option>
                                    <option value="Senegal">Senegal</option>
                                    <option value="Serbia">Serbia</option>
                                    <option value="Seychelles">Seychelles</option>
                                    <option value="Sierra Leona">Sierra Leona</option>
                                    <option value="Singapur">Singapur</option>
                                    <option value="Eslovaquia">Eslovaquia</option>
                                    <option value="Eslovenia">Eslovenia</option>
                                    <option value="Islas Salomón">Islas Salomón</option>
                                    <option value="Somalia">Somalia</option>
                                    <option value="Sudáfrica">Sudáfrica</option>
                                    <option value="Sudán del Sur">Sudán del Sur</option>
                                    <option value="España">España</option>
                                    <option value="Sri Lanka">Sri Lanka</option>
                                    <option value="Sudán">Sudán</option>
                                    <option value="Surinam">Surinam</option>
                                    <option value="Suecia">Suecia</option>
                                    <option value="Suiza">Suiza</option>
                                    <option value="República Árabe Siria">República Árabe Siria</option>
                                    <option value="Tayikistán">Tayikistán</option>
                                    <option value="Tanzania">Tanzania</option>
                                    <option value="Tailandia">Tailandia</option>
                                    <option value="Timor-Leste">Timor-Leste</option>
                                    <option value="Togo">Togo</option>
                                    <option value="Tonga">Tonga</option>
                                    <option value="Trinidad y Tobago">Trinidad y Tobago</option>
                                    <option value="Túnez">Túnez</option>
                                    <option value="Turquía">Turquía</option>
                                    <option value="Turkmenistán">Turkmenistán</option>
                                    <option value="Tuvalu">Tuvalu</option>
                                    <option value="Uganda">Uganda</option>
                                    <option value="Ucrania">Ucrania</option>
                                    <option value="Emiratos Árabes Unidos">Emiratos Árabes Unidos</option>
                                    <option value="Reino Unido de Gran Bretaña e Irlanda del Norte">Reino Unido de Gran
                                        Bretaña e Irlanda del Norte</option>
                                    <option value="Tanzania">Tanzania</option>
                                    <option value="Estados Unidos de América">Estados Unidos de América</option>
                                    <option value="Uruguay">Uruguay</option>
                                    <option value="Uzbekistán">Uzbekistán</option>
                                    <option value="Vanuatu">Vanuatu</option>
                                    <option value="Venezuela">Venezuela</option>
                                    <option value="Viet Nam">Viet Nam</option>
                                    <option value="Yemen">Yemen</option>
                                    <option value="Zambia">Zambia</option>
                                    <option value="Zimbabue">Zimbabue</option>
                                    <option value="Palestina">Palestina</option>
                                    <option value="Santa Sede">Santa Sede</option>
                                </select>
                            </div>
                        </div>
                        <!-- Columna derecha -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Peso (kg)</label>
                                <input type="number" wire:model.defer="peso" step="0.01" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Observación</label>
                                <textarea wire:model.defer="observacion" class="form-control" rows="4" style="text-transform: uppercase;"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button wire:click="guardar" class="btn btn-primary">
                        {{ $paquete_id ? 'Actualizar' : 'Guardar' }}
                    </button>
                    <button type="button" class="btn btn-secondary" wire:click="cerrarModal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Asignar Destino -->
    <div class="modal fade @if ($modalDestino) show d-block @endif" tabindex="-1"
        style="background: rgba(0,0,0,0.5);" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Asignar Destino</h5>
                    <button type="button" class="close" wire:click="$set('modalDestino', false)">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Destino</label>
                        <select wire:model.defer="destino" class="form-control" style="text-transform: uppercase;">
                            <option value="">SELECCIONE...</option>
                            <option value="local">LOCAL</option>
                            <option value="nacional">NACIONAL</option>
                            <option value="camiri">CAMIRI</option>
                            <option value="sud">SUD AMÉRICA</option>
                            <option value="centro">CENTRO AMÉRICA Y CARIBE</option>
                            <option value="norte">NORTE AMÉRICA</option>
                            <option value="euro">EUROPA Y ÁFRICA</option>
                            <option value="asia">ASIA Y OCEANÍA</option>
                        </select>
                        @error('destino')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button wire:click="asignarDestino" class="btn btn-primary">
                        Guardar Destino
                    </button>
                    <button type="button" class="btn btn-secondary" wire:click="$set('modalDestino', false)">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

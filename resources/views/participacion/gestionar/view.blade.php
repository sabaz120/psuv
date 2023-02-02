@extends("layouts.main")

@section("content")


    <div class="d-flex flex-column-fluid" id="content" v-cloak>

        <!--begin::Container-->
        <div class="container" v-cloak>
            <!--begin::Card-->
            <div class="card card-custom">
                <!--begin::Header-->
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title">
                        <h3 class="card-label">Registro de participación</h3>
                    </div>
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>Nivel participación</label>
                                <select class="form-control" v-model="selectedTipo" @change="changeType()">
                                    <option value="UBCH">UBCH</option>
                                    <option value="Comunidad">Comunidad</option>
                                    <option value="Calle">Calle</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3" >
                            <div class="form-group">
                                <label>Municipio</label>
                                <select class="form-control" v-model="selectedMunicipio" @change="getParroquias()" :disabled="authMunicipio != 0">
                                    <option value="0">Todos los municipios</option>
                                    <option v-for="municipio in municipios" :value="municipio.id">@{{ municipio.nombre }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>Parroquia</label>
                                <select class="form-control" v-model="selectedParroquia" @change="selectedTipo=='UBCH' ? getCentroVotacion() : getComunidades()">
                                    <option value="0">Todas las parroquias</option>
                                    <option v-for="parroquia in parroquias" :value="parroquia.id">@{{ parroquia.nombre }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3" v-if="selectedTipo=='UBCH'" @change="getPersons()">
                            <div class="form-group">
                                <label>Centro de Votación</label>
                                <select class="form-control" v-model="selectedCentroVotacion">
                                    <option value="0">Todos los centros de votación</option>
                                    <option v-for="centroVotacion in centrosVotacion" :value="centroVotacion.id">@{{ centroVotacion.nombre }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3" v-if="selectedTipo!='UBCH'">
                            <div class="form-group">
                                <label>Comunidad</label>
                                <select class="form-control" v-model="selectedComunidad" @change="getCalles()">
                                    <option value="0">Todos las comunidades</option>
                                    <option v-for="comunidad in comunidades" :value="comunidad.id">@{{ comunidad.nombre }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3" v-if="selectedTipo!='UBCH' && selectedTipo!='Comunidad'">
                            <div class="form-group">
                                <label>Calle</label>
                                <select class="form-control" v-model="selectedCalle" @change="getPersons()">
                                    <option value="0">Todos las calles</option>
                                    <option v-for="calle in calles" :value="calle.id">@{{ calle.nombre }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                    <div class="col-lg-3">
                            <div class="form-group">
                                <label>Cédula</label>
                                <div class="d-flex">
                                    <div>
                                        <input type="tel" class="form-control" id="cedula" v-model="elector.cedula"  maxlength="8" @keypress="isNumber($event)">
                                    </div>
                                    <div >
                                        <button class="btn btn-primary" @click="obtenerElector()" v-if="!loading">
                                            <i class="fas fa-search"></i>
                                        </button>
                                        <div class="spinner spinner-primary ml-1 mr-13 mt-5" v-if="loading"></div>
                                    </div>      
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>Nombre</label>
                                <input type="text" class="form-control" v-model="elector.full_name" disabled>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>Teléfono</label>
                                <input type="text" class="form-control" v-model="elector.telefono_principal">
                            </div>
                        </div>
                        <div class="col-lg-3 text-center">
                            <button class="btn btn-primary" @click="assign()" v-if="loading == false">Asignar participación</button>
                            <p class="text-center">
                                <div class="spinner spinner-primary ml-1 mr-13 mt-5" v-if="loading"></div>
                            </p>
                        </div>
                        <div class="col-lg-12 text-center">
                            <h3>Listado de participación</h3>
                        </div>
                        <div class="col-lg-12 text-center">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Cédula</th>
                                            <th>Nombre</th>
                                            <th>Teléfono</th>
                                            <th>Centro de votación</th>
                                            <th>Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(person,index) in assignedPersons">
                                            <td>@{{person.cedula}}</td>
                                            <td>@{{person.full_name}}</td>
                                            <td>@{{person.telefono_principal}}</td>
                                            <td>@{{person.centro_votacion_nombre}}</td>
                                            <td>
                                                <button type="button" class="btn btn-primary font-weight-bold"  @click="deleteAssignPerson(index,person.id)" v-if="!loading">Eliminar</button>
                                            </td>
                                        </tr>
                                        <tr v-if="assignedPersons.length==0">
                                            <td colspan="4">No hay participantes que mostrar</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Body-->
            </div>
            <!--end::Card-->
        </div>
        <!--end::Container-->

    </div>


@endsection

@push("scripts")
    @include("participacion.gestionar.scripts")
@endpush
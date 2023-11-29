@extends('layouts.main')

@section('content')
    <div class="d-flex flex-column-fluid" id="content" v-cloak>

        <div class="loader-cover-custom" v-if="loading == true">
            <div class="loader-custom"></div>
        </div>

        <!--begin::Container-->
        <div class="container">
            <!--begin::Card-->
            <div class="card card-custom">
                <!--begin::Header-->
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title">
                        <h3 class="card-label">Gestionar 1xcalle</h3>
                    </div>
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="cedula">Cédula Jefe de Calle</label>
                                <div class="d-flex">
                                    <div>
                                        <input type="tel" class="form-control" id="cedula" v-model="cedula_jefe_calle" maxlength="8" @keypress="isNumber($event)" :readonly="entityId">
                                        <small class="text-danger" v-if="cedula_jefe_calle_error">@{{ cedula_jefe_calle_error }}</small>
                                    </div>
                                    <div >
                                        <button class="btn btn-primary" @click="obtenerJefeCalle()" v-if="!loading" :disabled="entityId">
                                            <i class="fas fa-search"></i>
                                        </button>
                                        <div class="spinner spinner-primary ml-1 mr-13 mt-5" v-if="loading"></div>
                                    </div>      
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="nombre">Nombre</label>
                                <input type="text" class="form-control" v-if="jefe_calle" v-model="jefe_calle.personal_caracterizacion.full_name" readonly>
                                <input type="text" class="form-control" v-else readonly>
                            </div>
                        </div>

                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Calles</label>
                                <select class="form-control" v-model="form.jefe_calle_id" v-if="jefe_calle" :disabled="entityId">
                                    <option v-for="jefeCalle in calles" :value="jefeCalle.id">@{{jefeCalle.calle.nombre}}</option>
                                </select>
                                <select class="form-control" v-else disabled>
                                    <option value="" selected>Seleccione</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="cedula">Cédula 1*calle</label>
                                <div class="d-flex">
                                    <div>
                                        <input type="tel" class="form-control" id="cedula" v-model="cedula_jefe" maxlength="8" @keypress="isNumber($event)">
                                        <small class="text-danger" v-if="cedula_jefe_error">@{{ cedula_jefe_error }}</small>
                                    </div>
                                    <div >
                                        <button class="btn btn-primary" @click="obtenerJefe()" v-if="!loading">
                                            <i class="fas fa-search"></i>
                                        </button>
                                        <div class="spinner spinner-primary ml-1 mr-13 mt-5" v-if="loading"></div>
                                    </div>      
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="nombre">Nombre </label>
                                <input type="text" class="form-control" v-if="form.personal_caracterizacion" v-model="form.personal_caracterizacion.full_name" readonly>
                                <input type="text" class="form-control" v-else readonly>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label >Teléfono principal</label>
                                <input type="text" class="form-control" maxlength="11" v-if="form.personal_caracterizacion" v-model="form.telefono_principal"  @keypress="isNumber($event)">
                                <input type="tel" class="form-control" v-else disabled>
                            </div>
                        </div>

                        <div class="col-md-12 text-center">
                            <button type="button" class="btn btn-light-warning font-weight-bold" @click="clearForm()" v-if="action == 'create' && !loading">Limpiar</button>
                            <button type="button" class="btn btn-primary font-weight-bold"  @click="store()" v-if="action == 'create' && !loading">Crear</button>
                            <button type="button" class="btn btn-primary font-weight-bold"  @click="update()" v-if="action == 'edit' && !loading">Actualizar</button>
                            <button type="button" class="btn btn-primary font-weight-bold"  @click="suspend()" v-if="action == 'suspend' && !loading">Suspender</button>
                            <a v-if="jefe_calle?.personal_caracterizacion?.jefe_familia_owner && !loading" :href="urlReporteJefeFamilia+'/'+jefe_calle.personal_caracterizacion.jefe_familia_owner.id" target="_blank" class="btn btn-success font-weight-bold">Generar Reporte 1xCalle</a>
                            <div class="spinner spinner-primary ml-1 mr-13 mt-2" v-if="loading"></div>
                        </div>
                        <!--init: list-->
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>

                                        <th class="datatable-cell datatable-cell-sort">
                                            <span>Jefe de Familia</span>
                                        </th>

                                        <th class="datatable-cell datatable-cell-sort">
                                            <span>Teléfono</span>
                                        </th>

                                        <th class="datatable-cell datatable-cell-sort">
                                            <span>Tipo voto</span>
                                        </th>

                                        <th class="datatable-cell datatable-cell-sort">
                                            <span>N° Familiares</span>
                                        </th>

                                        <th class="datatable-cell datatable-cell-sort">
                                            <span>¿Movilización?</span>
                                        </th>

                                        <th class="datatable-cell datatable-cell-sort">
                                            <span>Acción</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="result in results">
                                        <td>@{{ result.personal_caracterizacion.full_name }}</td>
                                        <td>@{{ result.personal_caracterizacion.telefono_principal }}</td>
                                        <td>@{{ result.personal_caracterizacion.tipo_voto }}</td>
                                        <td>@{{ result.familiares_count }}</td>
                                        <td>@{{ result.personal_caracterizacion.movilizacion.nombre }}</td>
                                        <td>
                                            <button title="Editar" class="btn btn-success" @click="edit(result)">
                                                <i class="far fa-edit"></i>
                                            </button>
                                            <button title="Suspender" class="btn btn-secondary"  @click="suspend(result.id)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!--end: list-->
                    </div>
                </div>
                <!--end::Body-->
            </div>
            <!--end::Card-->
        </div>
        <!--end::Container-->

    </div>
@endsection

@push('scripts')
    @include('RAAS.jefeFamilia.partials.hard_vote_scripts')
@endpush

@push('styles')
    <style>
        .active-link {
            background-color: #c0392b !important;
        }
    </style>
@endpush

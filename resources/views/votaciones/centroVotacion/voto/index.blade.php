@extends("layouts.main")

@section("content")

    <div class="d-flex flex-column-fluid" id="dev-ubch" v-cloak>

        <!--begin::Container-->
        <div class="container" v-cloak>
            <!--begin::Card-->
            <div class="card card-custom">
                <!--begin::Header-->
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title w-100">
                        <div class="row w-100">
                            <div class="col-6">
                                <h3 class="card-label">Registrar movilización</h3>
                            </div>
                            <div class="col-6">
                                <h3 class="card-label">{{ $centro_votacion->nombre }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-toolbar">
                       

                    </div>
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body">
                    <!--begin: Datatable-->
                    <div class="datatable datatable-bordered datatable-head-custom datatable-default datatable-primary datatable-loaded" id="kt_datatable" style="">
                        
                        <div class="container">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group d-flex">
                                        <input id="puntorojo" class="form-control custom-radio" type="radio" v-model="registerType" value="puntorojo" @change="resetName()">
                                        <label for="puntorojo" class="mt-3 ml-3">Punto rojo</label>
                                        
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group d-flex">
                                        <input id="instituciones" class="form-control custom-radio" type="radio" v-model="registerType" value="instituciones" @change="resetName()">
                                        <label for="instituciones" class="mt-3 ml-3">Instituciones</label>
                                        
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group d-flex">
                                        <input id="movilizacion" class="form-control custom-radio" type="radio" v-model="registerType" value="movilizacion" @change="resetName()">
                                        <label for="movilizacion" class="mt-3 ml-3">Movimiento</label>
                                        
                                    </div>
                                </div>
                            </div>

                            <div class="row" v-if="registerType == 'puntorojo'">
                                <div class="col-md-4">

                                    <label for="codigo-cuadernillo">Código cuadernillo</label>
                                    <div class="form-group d-flex">
                                        
                                        <input id="codigo-cuadernillo" class="form-control" type="text" v-model="searchCodigoCuadernillo" @keypress="isNumber($event)"  @keyup="isNumber($event)">

                                        <button class="btn btn-primary" @click="searchByCodigoCuadernillo()" v-if="!searchLoading">
                                            <i class="fas fa-search"></i>
                                        </button>
                                        <div class="spinner spinner-primary ml-1 mr-13" v-if="searchLoading"></div>
                                        
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    
                                    <div class="form-group">
                                        <label for="nombreelector">Nombre elector</label>
                                        <input id="nombreelector" class="form-control" type="text" v-model="nombreElector" disabled="true">
                                    </div>


                                </div>
                                <div class="col-md-4">

                                    <div class="form-group">
                                        <label for="movimiento" style="visibility:hidden;">Movimiento</label>
                                        <button class="btn btn-primary mt-8" v-if="!votoLoading" @click="ejercerVoto()" :disabled="disableVoto">VOTO</button>
                                        <div class="spinner spinner-primary ml-1 mr-13 mt-4" v-if="votoLoading"></div>
                                    </div>

                                </div>
                            </div>

                            <div class="row" v-if="registerType == 'instituciones'">
                                <div class="col-md-3">

                                    <label for="codigo-cuadernillo">Cédula</label>
                                    <div class="form-group d-flex">
                                        
                                        <input id="codigo-cuadernillo" class="form-control" type="text" v-model="cedula" @keypress="isNumber($event)"  @keyup="isNumber($event)">

                                        <button class="btn btn-primary" @click="searchByCedula()" v-if="!searchLoading">
                                            <i class="fas fa-search"></i>
                                        </button>
                                        <div class="spinner spinner-primary ml-1 mr-13" v-if="searchLoading"></div>
                                        
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    
                                    <div class="form-group">
                                        <label for="nombreelector">Nombre elector</label>
                                        <input id="nombreelector" class="form-control" type="text" v-model="nombreElector" disabled="true">
                                    </div>


                                </div>
                                <div class="col-md-3">
                                    
                                    <div class="form-group">
                                        <label for="institucion">Institución</label>
                                        <select id="institution" v-model="selectedInstitucion" class="form-control">
                                            <option value="">Seleccione</option>
                                            <option :value="institucion.nombre" v-for="institucion in instituciones">@{{ institucion.nombre }}</option>
                                        </select>
                                    </div>


                                </div>
                                <div class="col-md-3">

                                    <div class="form-group">
                                        <label for="movimiento" style="visibility:hidden;">Movimiento</label>
                                        <button class="btn btn-primary mt-8" v-if="!votoLoading" @click="ejercerVotoInstitucion()" :disabled="disableVoto">VOTO</button>
                                        <div class="spinner spinner-primary ml-1 mr-13 mt-4" v-if="votoLoading"></div>
                                    </div>

                                </div>
                            </div>

                            <div class="row" v-if="registerType == 'movilizacion'">
                                <div class="col-md-3">

                                    <label for="codigo-cuadernillo">Cédula</label>
                                    <div class="form-group d-flex">
                                        
                                        <input id="codigo-cuadernillo" class="form-control" type="text" v-model="cedula" @keypress="isNumber($event)"  @keyup="isNumber($event)">

                                        <button class="btn btn-primary" @click="searchByCedula()" v-if="!searchLoading">
                                            <i class="fas fa-search"></i>
                                        </button>
                                        <div class="spinner spinner-primary ml-1 mr-13" v-if="searchLoading"></div>
                                        
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    
                                    <div class="form-group">
                                        <label for="nombreelector">Nombre elector</label>
                                        <input id="nombreelector" class="form-control" type="text" v-model="nombreElector" disabled="true">
                                    </div>


                                </div>
                                <div class="col-md-3">
                                    
                                    <div class="form-group">
                                        <label for="movimiento">Movimiento</label>
                                        <select id="movimiento" v-model="selectedMovimiento" class="form-control">
                                            <option value="">Seleccione</option>
                                            <option :value="movimiento.nombre" v-for="movimiento in movimientos">@{{ movimiento.nombre }}</option>
                                        </select>
                                    </div>


                                </div>
                                <div class="col-md-3">

                                    <div class="form-group">
                                        <label for="movimiento" style="visibility:hidden;">Movimiento</label>
                                        <button class="btn btn-primary mt-8" v-if="!votoLoading" @click="ejercerVotoMovimiento()" :disabled="disableVoto">VOTO</button>
                                        <div class="spinner spinner-primary ml-1 mr-13 mt-4" v-if="votoLoading"></div>
                                    </div>

                                </div>
                            </div>

                        </div>

                
                        <div class="row">
                            <div class="col-12">
                                <div class="float-right">
                                    <div class="form-group">
                                        <label>Buscar</label>
                                        <div class="d-flex">
                                            <input class="form-control" placeholder="Código" v-model="searchText">
                                            <button class="btn btn-primary" v-if="!searchTextLoading" @click="searchVotanteCentro()">
                                                <i class="fas fa-search"></i>
                                            </button>
                                            <div class="spinner spinner-primary ml-1 mr-13" v-if="searchTextLoading"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
           

                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>

                                        <th class="datatable-cell datatable-cell-sort">
                                            <span>Código</span>
                                        </th>

                                        <th class="datatable-cell datatable-cell-sort">
                                            <span>Nombre</span>
                                        </th>

                                   

                                        <th class="datatable-cell datatable-cell-sort">
                                            <span>Hora</span>
                                        </th>



                                        <th class="datatable-cell datatable-cell-sort">
                                            <span>Acción</span>
                                        </th>
                                    </tr>vot
                                </thead>
                                <tbody>
                                    <tr v-for="votante in votantes">
                                        <td>@{{ votante.codigo_cuadernillo }}</td>
                                        <td>@{{ votante.elector.primer_nombre }} @{{ votante.elector.primer_apellido }}</td>
 
                                        <td>@{{ votante.hora }}</td>
                                        <td>
                                            <button class="btn btn-secondary" @click="remove(votante.id)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="row">
                            <div class="col-sm-12 col-md-5">
                                <div class="dataTables_info" id="kt_datatable_info" role="status" aria-live="polite">Mostrando página @{{ currentPage }} de @{{ totalPages }}</div>
                            </div>
                            <div class="col-sm-12 col-md-7">
                                <div class="dataTables_paginate paging_full_numbers" id="kt_datatable_paginate">
                                    <ul class="pagination">
                                        
                                        <li class="paginate_button page-item active" v-for="(link, index) in links">
                                            <a style="cursor: pointer" aria-controls="kt_datatable" tabindex="0" :class="link.active == false ? linkClass : activeLinkClass":key="index" @click="fetch(link)" v-html="link.label.replace('Previous', 'Anterior').replace('Next', 'Siguiente')"></a>
                                        </li>
                                        
                                        
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end: Datatable-->
                </div>
                <!--end::Body-->
            </div>
            <!--end::Card-->
        </div>
        <!--end::Container-->


    </div>

@endsection

@push("scripts")

    @include("votaciones.centroVotacion.voto.script")

@endpush

@push("styles")

    <style>

        .active-link{
            background-color:#c0392b !important;
        }

        .custom-radio{
            width: 20px;
        }


    </style

@endpush
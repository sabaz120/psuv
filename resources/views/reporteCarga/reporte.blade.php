@extends("layouts.main")

@section("content")

    <style>

        .round-circle{
            width: 20px; 
            height: 20px;
            border-radius: 50%;
        }

    </style>

    <div class="d-flex flex-column-fluid" id="content" v-cloak>

        <!--begin::Container-->
        <div class="container" v-cloak>
            <!--begin::Card-->
            <div class="card card-custom">
                <!--begin::Header-->
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title">
                        <h3 class="card-label">Reporte de carga</h3>
                    </div>
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-3">
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
                                <select class="form-control" v-model="selectedParroquia" @change="getCentroVotacion()">
                                    <option value="0">Todas las parroquias</option>
                                    <option v-for="parroquia in parroquias" :value="parroquia.id">@{{ parroquia.nombre }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Centro de Votación</label>
                                <select class="form-control" v-model="selectedCentroVotacion">
                                    <option value="0">Todos los centros de votación</option>
                                    <option v-for="centroVotacion in centrosVotacion" :value="centroVotacion.id">@{{ centroVotacion.nombre }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <label style="visibility: hidden;">Centro de Votación</label>
                            <button class="btn btn-primary" @click="generate()" v-if="loading == false">Generar gráficas</button>
                            <p class="text-center">
                                <div class="spinner spinner-primary ml-1 mr-13 mt-5" v-if="loading"></div>
                            </p>
                        </div>
                        
                    </div>

                    <div class="row" v-show="metaGeneral > 0">
                        <div class="col-12">
                            <p><div id="chart_12" class="d-flex justify-content-center"></div></p>
                            <p class="text-center"> @{{ cargados }} / @{{ metaGeneral }}</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <h2>@{{ type }}</h2>
                        </div>

                        <div v-for="(info, index) in secondaryInfo" class="col-md-4 col-sm-6 mt-10 pt-2 pb-2" >
                            <h6>@{{ info.nombre }}</h6>
                            <div class="d-flex">
                                <div class="w-50">
                                    <span style="color: red">@{{ info.cargados }}</span> / <span style="color: blue">@{{ info.meta }}</span> 
                                </div>
                                <div>
                                    <span title="menor al 50%" v-if="(info.cargados) <=  Math.ceil(info.meta*0.5)" >
                                        <div class="round-circle bg-danger">

                                        </div>
                                    </span>
                                    <span title="entre 51 y 69%" v-if="(info.cargados) >  Math.ceil(info.meta*0.5) && (info.cargados) <=  Math.ceil(info.meta*0.69)">
                                        <div class="round-circle bg-warning"></div>
                                    </span>
                                    <span title="mayor al 70%" v-if="(info.cargados) >=  Math.ceil(info.meta*0.7)">
                                        <div class="round-circle bg-success"></div>
                                    </span>
                                </div>
                            </p>
                        </div>

                    </div>

                    <div class="row mt-15" v-show="metaGeneral > 0">
                        <div class="col-6">
                            
                            <h2>Centros de votación</h2>
                        
                        </div>
                        <div class="col-6">
                            <button class="btn btn-info" @click="downloadExcel()" style="float: right">
                                Descargar excel
                            </button>
                        </div>
                        <div class="col-12">
                   
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Municipio</th>
                                            <th>Parroquia</th>
                                            <th>Centro Votación</th>
                                            <th>Meta</th>
                                            <th>Carga</th>
                                            <th>Pendiente</th>
                                            <th></th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    
                                        <tr v-for="centroVotacionMeta in centroVotacionMetas">
                                            <td>@{{ centroVotacionMeta.parroquia.municipio.nombre }}</td>
                                            <td>@{{ centroVotacionMeta.parroquia.nombre }}</td>
                                            <td>@{{ centroVotacionMeta.nombre }}</td>
                                            <td>@{{ centroVotacionMeta.metas_ubchs[0].meta }}</td>
                                            <td>@{{ centroVotacionMeta.personal_caracterizacions.length }}</td>
                                            <td>@{{ centroVotacionMeta.metas_ubchs[0].meta - centroVotacionMeta.personal_caracterizacions.length }}</td>
                                            <td>
                                                <span title="menor al 50%" v-if="(centroVotacionMeta.personal_caracterizacions.length) <=  Math.ceil(centroVotacionMeta.metas_ubchs[0].meta*0.5)" >
                                                    <div class="round-circle bg-danger">

                                                    </div>
                                                </span>
                                                <span title="entre 51 y 69%" v-if="(centroVotacionMeta.personal_caracterizacions.length) >  Math.ceil(centroVotacionMeta.metas_ubchs[0].meta*0.5) && (centroVotacionMeta.personal_caracterizacions.length) <=  Math.ceil(centroVotacionMeta.metas_ubchs[0].meta*0.69)">
                                                    <div class="round-circle bg-warning"></div>
                                                </span>
                                                <span title="mayor al 70%" v-if="(centroVotacionMeta.personal_caracterizacions.length) >=  Math.ceil(centroVotacionMeta.metas_ubchs[0].meta*0.7)">
                                                    <div class="round-circle bg-success"></div>
                                                </span>
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>

                    <div class="row w-100">
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
                <!--end::Body-->
            </div>
            <!--end::Card-->
        </div>
        <!--end::Container-->

        @include("Listados.rep.partials.modal")

    </div>


@endsection

@push("scripts")

    @include("reporteCarga.scripts")

@endpush

@push("styles")

    <style>

        .active-link{
            background-color:#c0392b !important;
        }

    </style

@endpush
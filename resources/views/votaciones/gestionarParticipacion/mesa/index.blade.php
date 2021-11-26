@extends("layouts.main")

@section("content")

    <div class="d-flex flex-column-fluid" id="dev-ubch" v-cloak>

        <div class="loader-cover-custom" v-if="loading == true">
            <div class="loader-custom"></div>
        </div>

        <!--begin::Container-->
        <div class="container" v-cloak>
            <!--begin::Card-->
            <div class="card card-custom">
                <!--begin::Header-->
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title">
                        <h3 class="card-label">Gestionar participación</h3>
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
                                    <div class="form-group">
                                        <label for="">Hora:</label>
                                        <div class="d-flex">
                                            <select class="form-control" v-model="hora">
                                                <option v-for="i in 12" :value="i + 1 < 11 ? '0'+i : i">@{{ i + 1 < 11 ? '0'+i : i }}</option>
                                            </select>
                                            <select class="form-control" v-model="minuto">
                                                <option v-for="(i, index) in 60">@{{ index < 10 ? '0'+index : index }}</option>
                                            </select>
                                            <select class="form-control" v-model="meridiano">
                                                <option value="AM">AM</option>
                                                <option value="PM">PM</option>
                                            </select>
                                        </div>
                                        <small class="text-danger" v-if="errors.hasOwnProperty('hora')">@{{ errors['hora'][0] }}</small>
                                        <small class="text-danger" v-if="errors.hasOwnProperty('minuto')">@{{ errors['minuto'][0] }}</small>
                                        <small class="text-danger" v-if="errors.hasOwnProperty('meridiano')">@{{ errors['meridiano'][0] }}</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Mesa:</label>
                                        <select class="form-control" v-model="selectedMesa">
                                            <option :value="mesa.id" v-for="mesa in mesas">@{{ mesa.numero_mesa }}</option>
                                        </select>
                                        <small class="text-danger" v-if="errors.hasOwnProperty('mesa')">@{{ errors['mesa'][0] }}</small>
                                        
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Cantidad votos:</label>
                                        <input type="text" class="form-control" v-model="cantidadVotos" @keypress="isNumber($event)">
                                    </div>
                                    <small class="text-danger" v-if="errors.hasOwnProperty('cantidad_votos')">@{{ errors['cantidad_votos'][0] }}</small>
                                    
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="float-right">
                                        <button class="btn btn-warning" @click="clear()">Limpiar</button>
                                        <button class="btn btn-primary" @click="store()" v-if="!storeLoading">Crear</button>
                                        <div class="spinner spinner-primary ml-1 mr-13 mt-2" v-if="storeLoading"></div>
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-12">
                                    <div class="float-right">
                                        <div class="form-group">
                                            <label>Buscar</label>
                                            <div class="d-flex">
                                                <input class="form-control" placeholder="Nombre de mesa" v-model="searchText">
                                                <button class="btn btn-primary" v-if="!searchLoading" @click="search()">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                                <div class="spinner spinner-primary ml-1 mr-13" v-if="searchLoading"></div>
                                            </div>
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
                                            <span>Mesa</span>
                                        </th>

                                        <th class="datatable-cell datatable-cell-sort" style="width: 130px;">
                                            <span>Hora</span>
                                        </th>

                                        <th class="datatable-cell datatable-cell-sort" style="width: 130px;">
                                            <span>Cantidad</span>
                                        </th>

                                        <th class="datatable-cell datatable-cell-sort">
                                            <span>Acción</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="participacion in participaciones">
                                        <td>@{{ participacion.mesa.numero_mesa }}</td>
                                        <td>@{{ hourFormat(participacion.hora) }}</td>
                                        <td>@{{ participacion.cantidad }}</td>
                                        <td>
                                            <button class="btn btn-secondary" @click="remove(participacion.id)">
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

    @include("votaciones.gestionarParticipacion.mesa.script")

@endpush

@push("styles")

    <style>

        .active-link{
            background-color:#c0392b !important;
        }

    </style

@endpush
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
                        <h3 class="card-label">Cierre de mesa por partido</h3>
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
                                <div class="col-12">
                                    <div class="float-right">
                                        <a href="{{ route('cierre-mesa.partidos.cierre') }}" class="btn btn-primary w-100">Cierre de mesa</a>
                                        <div class="form-group">
                                            <label>Buscar</label>
                                            <div class="d-flex">
                                                <input class="form-control" placeholder="Centro de votación" v-model="searchText">
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
                                            <span>Municipio</span>
                                        </th>

                                        <th class="datatable-cell datatable-cell-sort" style="width: 130px;">
                                            <span>Parroquia</span>
                                        </th>

                                        <th class="datatable-cell datatable-cell-sort" style="width: 130px;">
                                            <span>Centro de votación</span>
                                        </th>

                                        <th class="datatable-cell datatable-cell-sort">
                                            <span>Hora</span>
                                        </th>

                                        <th class="datatable-cell datatable-cell-sort">
                                            <span>Acción</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="centro in centrosVotacion">
                                        <td>@{{ centro.centro_votacion.parroquia.municipio.nombre }}</td>
                                        <td>@{{ centro.centro_votacion.parroquia.nombre }}</td>
                                        <td>@{{ centro.centro_votacion.nombre }}</td>
                                        <td>@{{ hourFormat(centro.hora_transmision) }} </td>
                                        <td>
                                            <button class="btn btn-primary" data-toggle="modal" data-target=".marketModal" @click="getResultados(centro.id)">Resultados</button>
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

        @include("cierreMesa.partidos.modal")
    </div>

@endsection

@push("scripts")

    @include("cierreMesa.partidos.script")

@endpush

@push("styles")

    <style>

        .active-link{
            background-color:#c0392b !important;
        }

    </style

@endpush
@extends("layouts.main")

@section("content")

    <div class="d-flex flex-column-fluid" id="content" >

        <!--begin::Container-->
        <div class="container" v-cloak>
            <!--begin::Card-->
            <div class="card card-custom">
                <!--begin::Header-->
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label style="visibility: hidden;">Buscar</label>
                            <h3 class="card-label">Gestionar votos</h3>
                        </div>
                        <div class="col-md-6">
                            <div class="float-right">
                                <div class="form-group">
                                    <label>Buscar</label>
                                    <div class="d-flex">
                                        <input class="form-control" placeholder="Centro de votación" v-model="searchText">
                                        <button class="btn btn-primary" v-if="!loading" @click="search()">
                                            <i class="fas fa-search"></i>
                                        </button>
                                        <div class="spinner spinner-primary ml-1 mr-13" v-if="loading"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Municipio</th>
                                        <th>Parroquia</th>
                                        <th>Centro</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                
                                    <tr v-for="(centro, index) in centrosVotacion">
                                        <td>@{{ centro.parroquia.municipio.nombre }}</td>
                                        <td>@{{ centro.parroquia.nombre }}</td>
                                        <td>@{{ centro.nombre  }}</td>
                                        <td>
                                            <button class="btn btn-primary">Registrar voto</button>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
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
        
    </div>

    

@endsection

@push("scripts")

    @include("votaciones.gestionarVotos.scripts")

@endpush

@push("styles")

    <style>

        .active-link{
            background-color:#c0392b !important;
        }

    </style

@endpush
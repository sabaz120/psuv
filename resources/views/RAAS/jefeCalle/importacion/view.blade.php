@extends("layouts.main")

@section("content")

    <div class="d-flex flex-column-fluid" id="content" v-cloak>

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
                        <h3 class="card-label">Importación de jefe de Calle</h3>
                        <a href="{{asset('excel/base_importacion_jefes_calle.xlsx')}}" download>Descargar excel base</a>
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
                                    <div class="float-center">
                                        <div class="form-group">
                                            <label>Archivo</label>
                                            <input 
											    @change="handleChange"
                                                class="form-control" 
                                                type="file" 
                                            >
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive" v-if="results.length>0">
                            <div class="text-center">
                                <h3>Datos de jefe's que no fueron importados</h3>
                            </div>
                            <table class="table">
                                <thead>
                                    <tr>

                                        <th class="datatable-cell datatable-cell-sort">
                                            <span>Comunidad</span>
                                        </th>

                                        <th class="datatable-cell datatable-cell-sort">
                                            <span>Calle</span>
                                        </th>

                                        <th class="datatable-cell datatable-cell-sort">
                                            <span>Cédula jefe comunidad</span>
                                        </th>

                                        <th class="datatable-cell datatable-cell-sort">
                                            <span>Cédula jefe calle</span>
                                        </th>

                                        <th class="datatable-cell datatable-cell-sort">
                                            <span>Motivo</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="result in results">
                                        <td>@{{ result.datos.nombre_comunidad }}</td>
                                        <td>@{{ result.datos.nombre_calle }}</td>
                                        <td>@{{ result.datos.cedula_jefe_comunidad }}</td>
                                        <td>@{{ result.datos.cedula_persona }}</td>
                                        <td>@{{ result.motivo }}</td>
                                    </tr>
                                </tbody>
                            </table>
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

@push('scripts')
@include('RAAS.jefeCalle.importacion.scripts')
@endpush

@push("styles")

    <style>

        .active-link{
            background-color:#c0392b !important;
        }

    </style>

@endpush
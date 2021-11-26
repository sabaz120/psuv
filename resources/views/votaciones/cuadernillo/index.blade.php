@extends("layouts.main")

@section("content")

    <div class="d-flex flex-column-fluid" id="content" >

        <!--begin::Container-->
        <div class="container" v-cloak>
            <!--begin::Card-->
            <div class="card card-custom">
                <!--begin::Header-->
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title">
                        <h3 class="card-label">Cuadernillos</h3>
                    </div>
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Municipio</label>
                                <select class="form-control" v-model="selectedMunicipio" @change="getParroquias()" :disabled="authMunicipio != 0">
                                    <option value="0">Seleccione</option>
                                    <option v-for="municipio in municipios" :value="municipio.id">@{{ municipio.nombre }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Parroquia</label>
                                <select class="form-control" v-model="selectedParroquia">
                                    <option value="0">Seleccione</option>
                                    <option v-for="parroquia in parroquias" :value="parroquia.id">@{{ parroquia.nombre }}</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-2">
                            <label style="visibility: hidden;">Centro de Votación</label>
                            <button class="btn btn-primary" @click="getCentrosVotacion()" v-if="!searchLoader">Buscar</button>
                            <div class="spinner spinner-primary ml-1 mr-13 mt-5" v-if="searchLoader"></div>
                        </div>
                        
                    </div>

                    <div class="row">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Código de centro</th>
                                        <th>Centro</th>
                                        <th>Descargar REP</th>
                                        <th>Descargar UBCH</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                
                                    <tr v-for="(centro, index) in centrosVotacion">
                                        <td>@{{ index + 1 }}</td>
                                        <td>@{{ centro.nombre }}</td>
                                        <td>
                                            <button @click="generatePDF(centro.id)" class="btn btn-success">PDF</button>
                                        </td>
                                        <td>
                                            <button @click="generateUBCHPDF(centro.parroquia.municipio.id, centro.parroquia.id, centro.id)" class="btn btn-success">PDF</button>
                                        </td>
                                        <td>
                                
                                            <div v-if="centro.descarga_cuadernillo.length > 0">
                                            <span v-if="centro.descarga_cuadernillo[0].descargado == true">Descargado</span>
                                            </div>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!--end::Body-->
            </div>
            <!--end::Card-->
        </div>
        <!--end::Container-->
        @include('votaciones.cuadernillo.modal')
    </div>

    

@endsection

@push("scripts")

    @include("votaciones.cuadernillo.script")

@endpush
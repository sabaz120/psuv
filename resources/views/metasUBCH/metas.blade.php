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
                        <h3 class="card-label">Reporte de carga</h3>
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
                                    <option value="0">Todos los municipios</option>
                                    <option v-for="municipio in municipios" :value="municipio.id">@{{ municipio.nombre }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Parroquia</label>
                                <select class="form-control" v-model="selectedParroquia" @change="getCentroVotacion()">
                                    <option value="0">Todas las parroquias</option>
                                    <option v-for="parroquia in parroquias" :value="parroquia.id">@{{ parroquia.nombre }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Centro de Votación</label>
                                <select class="form-control" v-model="selectedCentroVotacion">
                                    <option value="0">Todos los centros de votación</option>
                                    <option v-for="centroVotacion in centrosVotacion" :value="centroVotacion.id">@{{ centroVotacion.nombre }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label style="visibility: hidden;">Centro de Votación</label>
                            <button class="btn btn-primary" @click="download()">Generar excel</button>
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

    @include("metasUBCH.scripts")

@endpush
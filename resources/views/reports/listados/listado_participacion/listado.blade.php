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
                        <h3 class="card-label">Reporte listado de participación</h3>
                    </div>
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>Estructura base</label>
                                <select class="form-control" v-model="selectedTipo" @change="changeType()">
                                    <option value="UBCH">UBCH</option>
                                    <option value="Comunidad">Comunidad</option>
                                    <option value="Calle">Calle</option>
                                </select>
                            </div>
                        </div>
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
                                <select class="form-control" v-model="selectedParroquia" @change="getComunidades()">
                                    <option value="0">Todas las parroquias</option>
                                    <option v-for="parroquia in parroquias" :value="parroquia.id">@{{ parroquia.nombre }}</option>
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
                                <select class="form-control" v-model="selectedCalle">
                                    <option value="0">Todos las calles</option>
                                    <option v-for="calle in calles" :value="calle.id">@{{ calle.nombre }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <label style="visibility: hidden;">Centro de Votación</label>
                            <button class="btn btn-primary" @click="downloadExcel()" v-if="loading == false">Descargar excel</button>
                            <p class="text-center">
                                <div class="spinner spinner-primary ml-1 mr-13 mt-5" v-if="loading"></div>
                            </p>
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

    @include("reports.listados.listado_participacion.scripts")

@endpush
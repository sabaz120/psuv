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
                        <h3 class="card-label">Parámetros de búsqueda</h3>
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

                    {{--<div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Centros de votación</th>
                                            <th>Centros abiertos</th>
                                            <th>Centros cerrados</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{ App\Models\CentroVotacion::count() }}</td>
                                            <td>0</td>
                                            <td>{{ App\Models\CentroVotacion::count() }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>--}}

                    <div class="row mt-10">
                        <div class="col-md-5">
                            <p class="text-center">
                            <img src="{{ url('capa0.png') }}" alt="" style="border-radius: 20px; height: 450px;">
                            </p>
                            <p class="text-center">Víctor José Clark Boscan - Candidato a la reelección a la gobernación</p>
                        </div>
                        <div class="col-md-7">

                            <div class="row">
                                <div class="col-12">
                                    <p><div id="chart_12" class="d-flex justify-content-center"></div></p>
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td><b>Participación</b></td>
                                                <td>@{{ metaGeneral }}</td>
                                            </tr>
                                            <tr>
                                                <td><b>Movilización</b></td>
                                                <td>@{{ cargados }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="row mt-10">
                        <div class="col-12">
                            <h2>@{{ type }}</h2>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Entidad</th>
                                            <th>Participación</th>
                                            <th>Movilización</th>
                                            <th>Gobierno</th>
                                            <th>Oposición</th>
                                            
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(info, index) in secondaryInfo">
                                            <td>@{{ info.nombre }}</td>
                                            <td>@{{ info.participacion }}</td>
                                            <td>@{{ info.movilizacion }}</td>
                                            <td>@{{ info.movilizacion }} </td>
                                            <td>@{{ (info.participacion - info.movilizacion) > 0 ? (info.participacion - info.movilizacion) : 0  }}</td>
                                            
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        


                        <div v-for="(info, index) in secondaryInfo" class="col-md-4 col-sm-6 mt-10 pt-2 pb-2" >
                            {{--<h6>@{{ info.nombre }}</h6>
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
                            </div>--}}
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
    @include("scriptDashboard")
@endpush
@extends("layouts.main")

@section("content")

    <div class="d-flex flex-column-fluid" id="dev-ubch" v-cloak>

        <!--begin::Container-->
        <div class="container" v-cloak>
            <!--begin::Card-->
            <div class="card card-custom">
                <!--begin::Header-->
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title">
                        <h3 class="card-label">Nuevo cierre de mesa por partido</h3>
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
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label for="">Municipio</label>
                                        <select class="form-control" @change="getParroquias()" v-model="selectedMunicipio" :disabled="disabledMunicipio">
                                            <option :value="municipio.id" v-for="municipio in municipios">@{{ municipio.nombre }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label for="">Paroquia</label>
                                        <select class="form-control" @change="getCentrosVotacion()" v-model="selectedParroquia">
                                            <option :value="parroquia.id" v-for="parroquia in parroquias">@{{ parroquia.nombre }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label for="">Centro de votación</label>
                                        <select class="form-control" v-model="selectedCentroVotacion" @change="getMesas()">
                                            <option :value="centroVotacion.id" v-for="centroVotacion in centrosVotacion">@{{ centroVotacion.nombre }}</option>
                                        </select>
                  
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label for="">Mesa</label>
                                        <select class="form-control" v-model="selectedMesa">
                                            <option :value="mesa.id" v-for="mesa in mesas">@{{ mesa.numero_mesa }}</option>
                                        </select>
                                        <small class="text-danger" v-if="errors.hasOwnProperty('mesaId')">@{{ errors['mesaId'][0] }}</small>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label for="">Hora</label>
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
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="float-right">
                                       
                                       <div class="d-flex">
                                           <button class="btn btn-warning" @click="limpiar()">Limpiar</button>
                                           <button class="btn btn-primary" @click="update()" v-if="!storeLoading">Crear</button>
                                           <div class="spinner spinner-primary ml-1 mr-13 mt-2" v-if="storeLoading"></div>
                                       </div>
                                 
                                   </div>
                                </div>
                            </div>

                            <div class="row" v-show="candidatos.length > 0">
                                <div class="col-12">
                                    <div class="float-right">
                                       
                                        <div class="form-group">
                                            <label for="">Buscar</label>
                                            <div class="d-flex">
                                                <input type="text" class="form-control" v-model="candidatosSearchText">
                                                <button class="btn btn-primary" @click="search()">Buscar</button>
                                            </div>

                                        </div>
                                 
                                   </div>
                                </div>
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th style="width: 120px;">Foto</th>
                                                    <th>Candidato</th>
                                                    <th>Cargo</th>
                                                    <th>Partido político</th>
                                                    <th>Total de votos</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="candidato in candidatos">
                                                    <td>
                                                        <img :src="candidato.foto" alt="" class="w-100">
                                                    </td>
                                                    <td>
                                                        @{{ candidato.candidato }}
                                                    </td>
                                                    <td>
                                                        @{{ candidato.cargo_eleccion }}
                                                    </td>
                                                    <td>
                                                        @{{ candidato.partido_politico }}
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" value="0" :id="'voto-'+candidato.id" @keypress="isNumber($event)">
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="float-right">
                                       
                                       <div class="d-flex">
                                           <button class="btn btn-success" @click="storeResults()">Guardar votos</button>
                                           
                                       </div>
                                 
                                   </div>
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

    @include("cierreMesa.partidos.cierrePartido.script")

@endpush

@push("styles")

    <style>

        .active-link{
            background-color:#c0392b !important;
        }

    </style

@endpush
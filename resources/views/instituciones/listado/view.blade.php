@extends("layouts.main")

@section("content")
@php
$user=\Auth::user();
$municipios=\App\Models\Municipio::query();
if($user->municipio_id){
    $municipios->where('id',$user->municipio_id);
}
$municipios=$municipios->get();
$instituciones=\App\Models\Institucion::query();
if(count($user->instituciones)>0){
    $instituciones->whereIn("id",$user->instituciones->pluck('id')->all());
}
$instituciones=$instituciones->get();

$movilizaciones=\App\Models\Movilizacion::all();
@endphp
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
                        <h3 class="card-label">Instituciones Listado</h3>
                    </div>
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body">
                    <div class="row">
                        <!-- Personal -->
                        <div class="col-6">
                            <label for="">Personal</label>
                            <select class="form-control" v-model="personal">
                                <option value="Trabajadores">Trabajadores</option>
                                <option value="1*familia">1*familia</option>
                            </select>
                        </div>
                        <!-- End Personal -->
                        <!-- Instituciones -->
                        <div class="col-6">
                            <label for="">Instituciones</label>
                            <select class="form-control" v-model="institucion_nombre">
                                <option value="">Seleccione</option>
                                @foreach($instituciones as $institucion)
                                    <option value="{{$institucion->nombre}}">{{$institucion->nombre}}</option>
                                @endforeach
                            </select>
                        </div>
                        <!-- End Instituciones -->
                        <!-- Municipio -->
                        <div class="col-6">
                            <label for="">Municipio</label>
                            <select class="form-control" v-model="municipio_nombre" @change="obtenerParroquias()">
                                <option value="">Seleccione</option>
                                @foreach($municipios as $municipio)
                                    <option value="{{$municipio->nombre}}">{{$municipio->nombre}}</option>
                                @endforeach
                            </select>
                        </div>
                        <!-- End municipio -->
                        <!-- Parroquia -->
                        <div class="col-6">
                            <label for="">Parroquia</label>
                            <select class="form-control" v-model="parroquia_nombre" @change="obtenerCentrosVotacion()">
                                <option value="">Seleccione</option>
                                <option v-for="parroquia in parroquias" :value="parroquia.nombre">
                                    @{{parroquia.nombre}}
                                </option>
                            </select>
                        </div>
                        <!-- End Parroquia -->
                        <!-- Parroquia -->
                        <div class="col-6">
                            <label for="">Centro Votación</label>
                            <select class="form-control" v-model="centro_votacion_nombre" >
                                <option value="">Seleccione</option>
                                <option v-for="centro_votacion in centros_votacion" :value="centro_votacion.nombre">
                                    @{{centro_votacion.nombre}}
                                </option>
                            </select>
                        </div>
                        <!-- End Parroquia -->
                        <!-- Voto -->
                        <div class="col-6">
                            <label for="">Voto</label>
                            <select class="form-control" v-model="voto">
                                <option value="t">SI</option>
                                <option value="f">NO</option>
                            </select>
                        </div>
                        <!-- End Voto -->
                        <!-- Movilización -->
                        <div class="col-6">
                            <label for="">Movilización</label>
                            <select class="form-control" v-model="movilizacion_nombre">
                                <option value="">Seleccione</option>
                                @foreach($movilizaciones as $movilizacion)
                                    <option value="{{$movilizacion->nombre}}">{{$movilizacion->nombre}}</option>
                                @endforeach
                            </select>
                        </div>
                        <!-- End Movilización -->

                        <div class="col-12 text-center my-5">
                            <button class="btn btn-success" @click="generate">
                                Generar
                            </button>
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

@push('scripts')
<script type="text/javascript">
    /********* VUE ***********/
    var vue_instance = new Vue({
        el: '#content',
        components: {},
        data: {
            loading: true,
            municipios:[],
            parroquias:[],
            centros_votacion:[],
            municipio_nombre:"{{Auth::user()->municipio ? Auth::user()->municipio->nombre : ''}}",
            parroquia_nombre:"",
            centro_votacion_nombre:"",
            institucion_nombre:"",
            movilizacion_nombre:"",
            personal:"Trabajadores",
            voto:"t",
        },
        created: function() {
            this.$nextTick(async function() {
                this.loading = false;
                if(this.municipio_nombre){
                    this.obtenerParroquias();
                }
            });
        },
        methods: {
         
            async obtenerParroquias() {
                try {
                    if(this.municipio_nombre==""){
                        swal({
                            text:"Debe seleccionar un municipio",
                            icon:"error"
                        })
                        return false;
                    }
                    this.loading = true;
                    let filters = {
                        municipio_nombre:this.municipio_nombre,
                     }
                    const response = await axios({
                        method: 'get',
                        responseType: 'json',
                        url: "{{ url('api/parroquias-busqueda') }}"+"/"+this.municipio_nombre,
                        params: filters
                    });

                    this.loading = false;
                    this.parroquias = response.data;
                    this.parroquia_nombre="";
                } catch (err) {
                    this.loading = false;
                    console.log(err)
                }
            },

                     
            async obtenerCentrosVotacion() {
                try {
                    if(this.parroquia_nombre==""){
                        swal({
                            text:"Debe seleccionar una parroquia",
                            icon:"error"
                        })
                        return false;
                    }
                    this.loading = true;
                    let filters = {
                        parroquia_nombre:this.parroquia_nombre,
                     }
                    const response = await axios({
                        method: 'get',
                        responseType: 'json',
                        url: "{{ url('api/centro-votacion-busqueda') }}"+"/"+this.parroquia_nombre,
                        params: filters
                    });

                    this.loading = false;
                    this.centros_votacion = response.data;
                    this.centro_votacion_nombre="";
                } catch (err) {
                    this.loading = false;
                    console.log(err)
                }
            },

            generate(){
                if("{{Auth::user()->municipio}}"){
                    if(this.municipio_nombre==""){
                        swal({
                            text:"Debe seleccionar un municipio",
                            icon:"error"
                        })
                        return false;
                    }
                }
                let params={
                    personal:this.personal,
                    institucion_nombre:this.institucion_nombre,
                    municipio_nombre:this.municipio_nombre,
                    parroquia_nombre:this.parroquia_nombre,
                    centro_votacion_nombre:this.centro_votacion_nombre,
                    movilizacion_nombre:this.movilizacion_nombre,
                    voto:this.voto
                };
                this.loading=true;
                axios({
                    url: `api/report/institutions/list`,
                    method: 'GET',
                    params:params,
                    responseType: 'blob' // important
                }).then((response) => {
                    const url = window.URL.createObjectURL(new Blob([response.data]))
                    const link = document.createElement('a')
                    link.href = url
                    link.setAttribute('download', 'reporte-instituciones-listado.xlsx')
                    document.body.appendChild(link)
                    link.click()
                    this.loading=false;
                })
            }//generate()

        } //methods
    });
</script>

@endpush

@push("styles")

    <style>

        .active-link{
            background-color:#c0392b !important;
        }

    </style>

@endpush
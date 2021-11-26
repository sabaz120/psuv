@extends("layouts.main")

@section("content")
@php
$municipios=\App\Models\Municipio::all();
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
                        <h3 class="card-label">Reporte movilización de electores</h3>
                    </div>
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body">
                    <div class="row">
                        <!-- Municipio -->
                        <div class="col-6">
                            <label for="">Municipio</label>
                            <select class="form-control" v-model="municipio_nombre" @change="obtenerParroquias()">
                                @if(Auth::user()->municipio_id)
                                    @foreach($municipios as $municipio)
                                        @if($municipio->id==Auth::user()->municipio_id)
                                            <option value="{{$municipio->nombre}}">{{$municipio->nombre}}</option>
                                        @endif
                                    @endforeach
                                @else
                                <option value="">Seleccione</option>
                                    @foreach($municipios as $municipio)
                                            <option value="{{$municipio->nombre}}">{{$municipio->nombre}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <!-- End municipio -->
                        <!-- Parroquia -->
                        <div class="col-6">
                            <label for="">Parroquia</label>
                            <select class="form-control" v-model="parroquia_nombre" @change="obtenerCentrosVotacion()" >
                                <option value="">Seleccione</option>
                                <option v-for="parroquia in parroquias" :value="parroquia.nombre">
                                    @{{parroquia.nombre}}
                                </option>
                            </select>
                        </div>
                        <!-- End Parroquia -->
                        <!-- Parroquia -->
                        <div class="col-6">
                            <label for="">Centro de votación</label>
                            <select class="form-control" v-model="centro_votacion_nombre" >
                                <option value="">Seleccione</option>
                                <option v-for="centroVotacion in centrosVotacion" :value="centroVotacion.nombre">
                                    @{{centroVotacion.nombre}}
                                </option>
                            </select>
                        </div>
                        <!-- End Parroquia -->
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
            centrosVotacion:[],
            municipio_nombre:"{{Auth::user()->municipio ? Auth::user()->municipio->nombre : ''}}",
            parroquia_nombre:"",
            centro_votacion_nombre:""
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
            async obtenerCentrosVotacion() {
                try {
                    if(this.parroquia_nombre==""){
                        // swal({
                        //     text:"Debe seleccionar una parroquia",
                        //     icon:"error"
                        // })
                        this.centrosVotacion = [];
                    this.centro_votacion_nombre="";
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
                    this.centrosVotacion = response.data;
                    this.centro_votacion_nombre="";
                } catch (err) {
                    this.loading = false;
                    console.log(err)
                }
            },
            async obtenerParroquias() {
                try {
                    if(this.municipio_nombre==""){
                        // swal({
                        //     text:"Debe seleccionar un municipio",
                        //     icon:"error"
                        // })
                        this.parroquias = [];
                        this.parroquia_nombre="";
                        this.centrosVotacion = [];
                        this.centro_votacion_nombre="";
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
                    municipio_nombre:this.municipio_nombre,
                    parroquia_nombre:this.parroquia_nombre,
                    centro_votacion_nombre:this.centro_votacion_nombre,
                };
                this.loading=true;
                axios({
                    url: `api/raas/report/voter_mobilization`,
                    method: 'GET',
                    params:params,
                    responseType: 'blob' // important
                }).then((response) => {
                    const url = window.URL.createObjectURL(new Blob([response.data]))
                    const link = document.createElement('a')
                    link.href = url
                    link.setAttribute('download', 'reporte-movilizacion-raas.xlsx')
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
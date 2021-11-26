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
                        <h3 class="card-label">Estadística cierre de mesa por candidatos</h3>
                    </div>
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body">
                    <div class="row">
                        <!-- cargos -->
                        <div class="col-md-6">
                            <label for="calle">Cargo</label>
                            <select class="form-control" v-model="cargo_eleccion" @change="obtenerCandidatos()">
                                <option value="">Seleccione</option>
                                <option :value="cargo" v-for="cargo in cargos">@{{cargo }}</option>
                            </select>
                        </div>
                        <!-- End cargos -->
                        <!-- candidato -->
                        <div class="col-6">
                            <label for="">Candidato</label>
                            <select class="form-control" v-model="candidato_id">
                            <option value="">Seleccione</option>
                                <option v-for="candidato in candidatos" :value="candidato.id">
                                    @{{candidato.fullName}}
                                </option>
                            </select>
                        </div>
                        <!-- End candidato -->
                        <!-- municipio -->
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
                            <button class="btn btn-success" @click="getResults()">
                                Generar
                            </button>
                        </div>

                        <!-- Body -->
                        <div class="col-12 my-5" v-show="results.length>0">

    <br>

    <p><div id="chart_12" class="d-flex justify-content-center"></div></p>


                        <!-- <br> -->

                        <!-- <div id="chartContainer" style="height: 450px; width: 100%;"></div> -->

                        <br>
                            <div class="text-right my-2">
                                <button title="Exportar a excel" class="btn btn-info" @click="exportTableToExcel('tblBody','cierre_candidatos')" >
                                    Exportar <i class="fa fa-file-excel"></i>
                                </button>
                            </div>
                            <table border="1" class="table table-bordered"  id="tblBody">
                                <thead>
                                    <tr>
                                        <th>Candidato</th>
                                        <th>Cargo</th>
                                        <th>Total de votos</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="result in results">
                                        <td>@{{result.candidato}}</td>
                                        <td>@{{result.cargo_eleccion}}</td>
                                        <td>@{{result.total_votos}}</td>
                                        <td>
                                            <button v-if="!municipio_nombre" class="btn btn-info" @click="entity=result;tipo_detalle='municipio';getDetail()"  data-toggle="modal" data-target=".marketModal">
                                                Detalle por municipio
                                            </button>
                                            <button v-if="municipio_nombre && !parroquia_nombre" class="btn btn-info" @click="entity=result;tipo_detalle='parroquia';getDetail()"  data-toggle="modal" data-target=".marketModal">
                                                Detalle por parroquia
                                            </button>
                                            <button v-if="parroquia_nombre && !centro_votacion_nombre" class="btn btn-info" @click="entity=result;tipo_detalle='centro_votacion';getDetail()"  data-toggle="modal" data-target=".marketModal">
                                                Detalle por centro de votación
                                            </button>
                                            <button v-if="centro_votacion_nombre" class="btn btn-info" @click="entity=result;tipo_detalle='mesa';getDetail()"  data-toggle="modal" data-target=".marketModal">
                                                Detalle por mesa
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- End Body -->


                    </div>
                </div>
                <!--end::Body-->
            </div>
            <!--end::Card-->
        </div>
        <!--end::Container-->

<!-- Modal-->
<div class="modal fade marketModal"  data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel" v-if="entity">Detalle estadístico del candidato(a): @{{entity.candidato}} </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="clearForm()">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        
                        <div class="col-12" v-if="resultsDetail.length>0">
                            <div class="text-right my-2">
                                <button title="Exportar a excel" class="btn btn-info" @click="exportTableToExcel('tableDetail','detalle')" >
                                    Exportar <i class="fa fa-file-excel"></i>
                                </button>
                            </div>
                            <table border="1" class="table table-bordered" id="tableDetail">
                                <thead>
                                    <tr>
                                        <th colspan="2">Candidato: @{{entity.candidato}}</th>
                                    </tr>
                                    <tr>
                                        <th>
                                            <span class="text-capitalize" v-if="tipo_detalle=='municipio'">Municipio</span>
                                            <span class="text-capitalize" v-if="tipo_detalle=='parroquia'">Parroquia</span>
                                            <span class="text-capitalize" v-if="tipo_detalle=='centro_votacion'">Centro de votación</span>
                                            <span class="text-capitalize" v-if="tipo_detalle=='mesa'">Mesa</span>
                                        </th>
                                        <th>Total de votos</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="rd in resultsDetail">
                                        <td>
                                            <span v-if="tipo_detalle=='mesa'">Mesa @{{rd.numero_mesa}} </span>
                                            <span v-else>@{{rd.categoria}}</span>
                                            
                                        </td>
                                        <td>@{{rd.total_votos}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div> <!-- row-->
                </div> <!-- container fluid-->                    
            </div> <!-- modal body-->   
            <div class="modal-footer">
                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal" @click="clearForm()">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<!-- End modal -->
    </div>

@endsection

@push('scripts')
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
<script src="{{ url('assets/js/pages/features/charts/apexchartsdashboard.js') }}"></script>

<script type="text/javascript">
    /********* VUE ***********/
    var vue_instance = new Vue({
        el: '#content',
        components: {},
        data: {
            loading: true,
            results:[],
            resultsDetail:[],
            municipios:[],
            parroquias:[],
            candidatos:[],
            centrosVotacion:[],
            municipio_nombre:"{{Auth::user()->municipio ? Auth::user()->municipio->nombre : ''}}",
            municipio_id:"{{Auth::user()->municipio ? Auth::user()->municipio_id : ''}}",
            candidato_id:"",
            parroquia_nombre:"",
            centro_votacion_nombre:"",
            tipo_detalle:"",
            entity:null,
            cargo_eleccion:"",
            cargos:[
                "Gobernador",
                "Alcalde",
                // "Concejal",
            ],
            clickCount:0
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
            async getDetail(){
                this.resultsDetail=[];
                if(this.tipo_detalle==""){
                    swal({
                        text:"Debe seleccionar un tipo de detalle",
                        icon:"error"
                    })
                    return false;
                }
                try {
                    let params={
                        tipo_detalle:this.tipo_detalle,
                        candidato_id:this.entity.candidato_id,
                        municipio_nombre:this.municipio_nombre,
                        parroquia_nombre:this.parroquia_nombre,
                        centro_votacion_nombre:this.centro_votacion_nombre,
                    };
                    this.loading = true;
                    const response = await axios({
                        method: 'get',
                        responseType: 'json',
                        url: "{{ url('api/report/cierre/candidatos/detalle') }}",
                        params: params
                    });

                    this.loading = false;
                    this.resultsDetail = response.data;
                } catch (err) {
                    this.loading = false;
                    console.log(err)
                }
            },
            clearForm(){
                this.entity=null;
                this.tipo_detalle="";
                this.resultsDetail=[];
            },
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
                    this.results = [];
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
                    this.results = [];
                } catch (err) {
                    this.loading = false;
                    console.log(err)
                }
            },

            async obtenerCandidatos() {
                try {
                    if(this.cargo_eleccion==""){
                        swal({
                            text:"Debe seleccionar un cargo",
                            icon:"error"
                        })
                        this.candidatos = [];
                        return false;
                    }
                    this.loading = true;
                    let filters = {
                        not_pagination:1,
                        municipio_id:this.municipio_id,
                        cargo_eleccion:this.cargo_eleccion
                     }
                    const response = await axios({
                        method: 'get',
                        responseType: 'json',
                        url: "{{ url('api/candidatos') }}",
                        params: filters
                    });

                    this.loading = false;
                    this.candidatos = response.data.data;
                } catch (err) {
                    this.loading = false;
                    console.log(err)
                }
            },

            async getResults(){
                try {
                    if(this.cargo_eleccion==""){
                        swal({
                            text:"Debe seleccionar un cargo",
                            icon:"error"
                        })
                        this.candidatos = [];
                        return false;
                    }
                    let params={
                        candidato_id:this.candidato_id,
                        municipio_nombre:this.municipio_nombre,
                        parroquia_nombre:this.parroquia_nombre,
                        centro_votacion_nombre:this.centro_votacion_nombre,
                        cargo_eleccion:this.cargo_eleccion,
                    };
                    this.loading = true;
                    const response = await axios({
                        method: 'get',
                        responseType: 'json',
                        url: "{{ url('api/report/cierre/candidatos') }}",
                        params: params
                    });

                    this.loading = false;
                    this.results = response.data;


                    let series=this.results.map(function(obj){
                        return obj.total_votos;
                    });
                    let labels=this.results.map(function(obj){
                        return obj.candidato;
                    });

                    apexChartCandidatos.init(series, labels, this.clickCount > 0 ? false : true, "#chart_12");
                    this.clickCount++;
/*
                    let dataPie=this.results.map(function(obj){
                        let rObj={};
                        rObj.y=obj.total_votos;
                        rObj.label=obj.candidato;
                        return rObj;
                    });

                    // console.log(dataPie);

                    var chart = new CanvasJS.Chart("chartContainer", {
	                    theme: "light2", // "light1", "light2", "dark1", "dark2"
	                    exportEnabled: true,
	                    animationEnabled: true,
	                    title: {
		                    text: "Cierre de mesa candidatos"
	                    },
                        exportFileName: "Cierre de mesa candidatos",  
	                    data: [{
		                    type: "pie",
		                    startAngle: 25,
		                    toolTipContent: "{y} votos",
		                    showInLegend: "true",
		                    legendText: "{label}",
		                    indexLabelFontSize: 16,
		                    indexLabel: "{label}",
		                    dataPoints: dataPie
	                    }]
                    });
                    chart.render();

                    */
                } catch (err) {
                    this.loading = false;
                    console.log(err)
                }
            },

            exportTableToExcel(tableID, filename = ''){
                var downloadLink;
                var dataType = 'application/vnd.ms-excel' +  ';charset=utf-8';
                // var dataType = 'application/vnd.ms-excel';
                var tableSelect = document.getElementById(tableID);
                var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');
    
                // Specify file name
                filename = filename?filename+'.xls':'excel_data.xls';
    
                // Create download link element
                downloadLink = document.createElement("a");
    
                document.body.appendChild(downloadLink);
    
                if(navigator.msSaveOrOpenBlob){
                    var blob = new Blob(['\ufeff', tableHTML], {
                        type: dataType
                    });
                    navigator.msSaveOrOpenBlob( blob, filename);
                }else{
                    // Create a link to the file
                    downloadLink.href = 'data:' + dataType + ',\uFEFF ' + tableHTML;
    
                    // Setting the file name
                    downloadLink.download = filename;
        
                    //triggering the function
                    downloadLink.click();
                }
            }//exportTableToExcel()

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
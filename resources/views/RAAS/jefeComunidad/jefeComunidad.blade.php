@extends("layouts.main")

@section("content")

    <div class="d-flex flex-column-fluid" id="dev-jefe-comunidad" v-cloak>

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
                        <h3 class="card-label">Jefe de Comunidad</h3>
                    </div>
                    <div class="card-toolbar">
                        <!--begin::Button-->
                        <button style="cursor: pointer;" class="btn btn-primary font-weight-bolder" data-toggle="modal" data-target=".marketModal" @click="create()">
                        <span class="svg-icon svg-icon-md">
                            <!--begin::Svg Icon | path:assets/media/svg/icons/Design/Flatten.svg-->
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect x="0" y="0" width="24" height="24"></rect>
                                    <circle fill="#000000" cx="9" cy="15" r="6"></circle>
                                    <path d="M8.8012943,7.00241953 C9.83837775,5.20768121 11.7781543,4 14,4 C17.3137085,4 20,6.6862915 20,10 C20,12.2218457 18.7923188,14.1616223 16.9975805,15.1987057 C16.9991904,15.1326658 17,15.0664274 17,15 C17,10.581722 13.418278,7 9,7 C8.93357256,7 8.86733422,7.00080962 8.8012943,7.00241953 Z" fill="#000000" opacity="0.3"></path>
                                </g>
                            </svg>
                            <!--end::Svg Icon-->
                        </span>Nuevo Jefe de Comunidad</button>
                        <!--end::Button-->

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
                                    <div class="float-right">
                                        <div class="form-group">
                                            <label>Buscar</label>
                                            <div class="d-flex">
                                                <input class="form-control" placeholder="cedula" @keypress="isNumber($event)" v-model="cedulaSearch">
                                                <button class="btn btn-primary" v-if="!searchLoading" @click="search()">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                                <div class="spinner spinner-primary ml-1 mr-13" v-if="searchLoading"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>

                                        <th class="datatable-cell datatable-cell-sort">
                                            <span>UBCH</span>
                                        </th>

                                        <th class="datatable-cell datatable-cell-sort">
                                            <span>Comunidad</span>
                                        </th>

                                        <th class="datatable-cell datatable-cell-sort">
                                            <span>Cédula</span>
                                        </th>

                                        <th class="datatable-cell datatable-cell-sort">
                                            <span>Nombre</span>
                                        </th>

                                        <th class="datatable-cell datatable-cell-sort">
                                            <span>Teléfono</span>
                                        </th>

                                        <th class="datatable-cell datatable-cell-sort">
                                            <span>Tipo voto</span>
                                        </th>

                                        <th class="datatable-cell datatable-cell-sort">
                                            <span>Partido</span>
                                        </th>

                                        <th class="datatable-cell datatable-cell-sort">
                                            <span>Movilización</span>
                                        </th>

                                        <th class="datatable-cell datatable-cell-sort">
                                            <span>Acción</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="jefeComunidad in jefesComunidad">
                                        <td>@{{ jefeComunidad.jefe_ubch.centro_votacion.nombre }}</td>
                                        <td>@{{ jefeComunidad.comunidad.nombre }}</td>
                                        <td>@{{ jefeComunidad.personal_caracterizacion.cedula }}</td>
                                        <td>@{{ jefeComunidad.personal_caracterizacion.primer_nombre }} @{{ jefeComunidad.personal_caracterizacion.primer_apellido }}</td>
                                        <td>@{{ jefeComunidad.personal_caracterizacion.telefono_principal }}</td>
                                        <td>@{{ jefeComunidad.personal_caracterizacion.tipo_voto }}</td>
                                        <td>@{{ jefeComunidad.personal_caracterizacion.partido_politico.nombre }}</td>
                                        <td>@{{ jefeComunidad.personal_caracterizacion.movilizacion.nombre }}</td>
                                        <td>
                                            <button class="btn btn-success" data-toggle="modal" data-target=".marketModal" @click="edit(jefeComunidad.personal_caracterizacion, jefeComunidad.id, jefeComunidad)">
                                                <i class="far fa-edit"></i>
                                            </button>
                                            <button class="btn btn-secondary" @click="remove(jefeComunidad.id)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="row">
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
                    <!--end: Datatable-->
                </div>
                <!--end::Body-->
            </div>
            <!--end::Card-->
        </div>
        <!--end::Container-->

        @include("RAAS.jefeComunidad.jefeComunidadPartials.modalFormCreateEditSuspend")


    </div>

@endsection

@push("scripts")

<script type="text/javascript">

var sync = false;
var date = "";

const app = new Vue({
    el: '#dev-jefe-comunidad',
    data() {
        return {

            linkClass:"page-link",
            activeLinkClass:"page-link active-link bg-main",
            readonlyComunidad:false,

            cedulaJefeUBCH:"",
            nombreJefeUBCH:"",
            selectedUBCH:"",
            cedulaSearch:"",

            readonlyCentroVotacion:false,
            readonlyCedula:false,
            cedulaSearching:false,
            searchLoading:false,
            readonlyJefeCedula:false,
            cedulaJefeSearching:false,
            storeLoader:false,
            updateLoader:false,
            suspendLoader:false,

            selectedId:"",
            action:"create",
            errors:[],
            loading:false,

 
            selectedParroquia:"",
            selectedCentroVotacion:"",
            partidosPoliticos:[],
            selectedPartidoPolitico:"",
            movilizaciones:[],
            selectedMovilizacion:"",
            selectedEstado:"",
            jefesComunidad:[],
            comunidades:[],
            selectedComunidad:"",

            cedula:"",
            nombre:"",
            telefonoPrincipal:"",
            telefonoSecundario:"",
            tipoVoto:"",
            fechaNacimiento:"",
            nacionalidad:"",
            primerNombre:"",
            segundoNombre:"",
            primerApellido:"",
            segundoApellido:"",
            sexo:"",

            modalTitle:"Crear Jefe de Comunidad",
            currentPage:1,
            links:"",
            totalPages:"",
        }
    },
    methods: {

        create(){
            this.selectedId = ""
            this.action = "create"
            this.modalTitle = "Crear Jefe de Comunidad",
            this.selecteMunicipio = ""
            this.selectedParroquia = ""
            this.selectedCentroVotacion = ""
            this.selectedPartidoPolitico = ""
            this.selectedMovilizacion = "",
            this.cedula=""
            this.nombre=""
            this.telefonoPrincipal=""
            this.telefonoSecundario=""
            this.tipoVoto=""
            this.readonlyCedula = false
            this.readonlyCentroVotacion = false
            this.selectedUBCH = ""
            this.selectedComunidad = ""
            this.nombreJefeUBCH = ""
            this.cedulaJefeUBCH = ""
            this.readonlyJefeCedula = false
            this.readonlyComunidad = false
            this.errors = []
        },
        async edit(elector, jefeId, model){
            this.selectedId = jefeId
            this.action = "edit"
            this.modalTitle = "Editar Jefe de Comunidad",
            this.readonlyCedula = false
            this.cedula = elector.cedula
            this.readonlyCentroVotacion = true
            this.readonlyJefeCedula = true
            this.readonlyComunidad = true
    
            this.cedulaJefeUBCH = model.jefe_ubch.personal_caracterizacion.cedula
            this.nombreJefeUBCH = model.jefe_ubch.personal_caracterizacion.full_name
            this.selectedParroquia = model.jefe_ubch.personal_caracterizacion.parroquia_id

            await this.getComunidades()
            this.selectedComunidad = model.comunidad_id
            
            
            this.setElectorData(elector)
        },
        async suspend(elector, jefeId, model){
            this.selectedId = jefeId
            this.action = "suspend"
            this.modalTitle = "Suspender Jefe de Comunidad",
            this.readonlyJefeCedula = false
            this.readonlyCedula = false
            this.cedula = elector.cedula
            this.readonlyCentroVotacion = true

            this.readonlyJefeCedula = true
            this.readonlyComunidad = true
    
            this.cedulaJefeUBCH = model.jefe_ubch.personal_caracterizacion.cedula
            this.nombreJefeUBCH = model.jefe_ubch.personal_caracterizacion.primer_nombre+" "+model.jefe_ubch.personal_caracterizacion.primer_apellido
            this.selectedParroquia = model.jefe_ubch.personal_caracterizacion.parroquia_id

            await this.getComunidades()
            this.selectedComunidad = model.comunidad_id

            this.setElectorData(elector)
        },
        clearForm(){
            this.create()
        },
        async searchJefeCedula(){

            try{

                this.cedulaJefeSearching = true
                //this.readonlyJefeCedula = true

                let res = await axios.post("{{ url('api/raas/jefe-comunidad/search-jefe-ubch-by-cedula') }}", {cedulaJefe: this.cedulaJefeUBCH, municipio_id: "{{ \Auth::user()->municipio_id }}"})
                this.cedulaJefeSearching = false 
                if(res.data.success == false){
                    this.readonlyJefeCedula = false
                    this.create()
                    swal({
                        text:res.data.msg,
                        icon:"error"
                    })
                }
                else{
                    this.cedulaJefeSearching = false
            
                    this.nombreJefeUBCH = res.data.personal_caracterizacion.primer_nombre+" "+res.data.personal_caracterizacion.primer_apellido
                    this.selectedUBCH = res.data.id
                    this.selectedCentroVotacion = res.data.personal_caracterizacion.centro_votacion_id
                    this.selectedParroquia = res.data.centro_votacion.parroquia_id
                    await this.getComunidades()

                }

                this.cedulaJefeSearching = false

            }catch(err){
                this.storeLoader = false
                this.readonlyJefeCedula = false
                swal({
                    text:"Hay algunos campos que debes revisar",
                    icon: "error"
                })

                this.errors = err.response.data.errors

            }
            this.cedulaJefeSearching = false

        },
        async searchCedula(){

            this.cedulaSearching = true
            this.readonlyCedula = true

            let res = await axios.post("{{ url('api/raas/jefe-comunidad/search-by-cedula') }}", {
                cedula: this.cedula,
                municipio_id: "{{ \Auth::user()->municipio_id }}"
            })

            if(res.data.success == false){
                this.readonlyCedula = false
                swal({
                    text:res.data.msg,
                    icon:"error"
                })
            }
            else{
                
                this.setElectorData(res.data.elector)

            }

            this.cedulaSearching = false

        },  
        async setElectorData(elector){

            this.nombre = elector.full_name
            this.selectedMunicipio = elector.municipio_id

            this.selectedParroquia = elector.parroquia_id

            this.selectedCentroVotacion = elector.centro_votacion_id

            this.telefonoPrincipal = elector.telefono_principal
            this.telefonoSecundario = elector.telefono_secundario
            this.fechaNacimiento = elector.fecha_nacimiento
            this.tipoVoto = elector.tipo_voto
            this.selectedEstado = elector.estado_id
            this.nacionalidad = elector.nacionalidad

            this.primerNombre = elector.primer_nombre
            this.segundoNombre = elector.segundo_nombre == "null" || elector.segundo_nombre == null ? "" : elector.segundo_nombre
            this.primerApellido = elector.primer_apellido
            this.segundoApellido = elector.segundo_apellido == "null" || elector.segundo_apellido == null ? "" : elector.segundo_apellido
            this.sexo = elector.sexo
            this.selectedPartidoPolitico = elector.partido_politico_id
            this.selectedMovilizacion = elector.movilizacion_id

        },  
        async fetch(link = ""){

            let res = await axios.get(link == "" ? "{{ url('api/raas/jefe-comunidad/fetch') }}"+"?municipio_id="+"{{ \Auth::user()->municipio_id }}" : link.url+"{{ \Auth::user()->municipio_id }}")
            this.jefesComunidad = res.data.data
            this.links = res.data.links
            this.currentPage = res.data.current_page
            this.totalPages = res.data.last_page


        },
        async store(){

            try{
                
                this.errors = []
                this.storeLoader = true

                let res = await axios.post("{{ url('api/raas/jefe-comunidad/store') }}", {cedulaJefe: this.cedulaJefeUBCH, cedula: this.cedula, nacionalidad: this.nacionalidad, primer_apellido: this.primerApellido, segundo_apellido: this.segundoApellido, primer_nombre: this.primerNombre, segundo_nombre: this.segundoNombre, sexo: this.sexo, telefono_principal: this.telefonoPrincipal, telefono_secundario: this.telefonoSecundario, tipo_voto: this.tipoVoto, estado_id: this.selectedEstado, municipio_id: this.selectedMunicipio, parroquia_id: this.selectedParroquia, centro_votacion_id: this.selectedCentroVotacion, partido_politico_id: this.selectedPartidoPolitico, movilizacion_id: this.selectedMovilizacion, fecha_nacimiento: this.fechaNacimiento, comunidad: this.selectedComunidad})
                
                this.storeLoader = false

                if(res.data.success == true){

                    swal({
                        text:res.data.msg,
                        icon: "success"
                    }).then(ans => {

                        $('.marketModal').modal('hide')
                        $('.modal-backdrop').remove()
                      

                    })

                    this.fetch()

                }else{

                    swal({
                        text:res.data.msg,
                        icon: "error"
                    })

                }

            }catch(err){
                this.storeLoader = false
                swal({
                    text:"Hay algunos campos que debes revisar",
                    icon: "error"
                })

                this.errors = err.response.data.errors

            }
            this.storeLoader = false

        },

        async update(){

            try{
                
                this.errors = []
                this.updateLoader = true

                let res = await axios.post("{{ url('api/raas/jefe-comunidad/update') }}", {cedulaJefe: this.cedulaJefeUBCH, cedula: this.cedula, nacionalidad: this.nacionalidad, primer_apellido: this.primerApellido, segundo_apellido: this.segundoApellido, primer_nombre: this.primerNombre, segundo_nombre: this.segundoNombre, sexo: this.sexo, telefono_principal: this.telefonoPrincipal, telefono_secundario: this.telefonoSecundario, tipo_voto: this.tipoVoto, estado_id: this.selectedEstado, municipio_id: this.selectedMunicipio, parroquia_id: this.selectedParroquia, centro_votacion_id: this.selectedCentroVotacion, partido_politico_id: this.selectedPartidoPolitico, movilizacion_id: this.selectedMovilizacion, fecha_nacimiento: this.fechaNacimiento, id: this.selectedId, comunidad: this.selectedComunidad})
                
                this.updateLoader = false

                if(res.data.success == true){

                    swal({
                        text:res.data.msg,
                        icon: "success"
                    }).then(ans => {

                        $('.marketModal').modal('hide')
                        $('.modal-backdrop').remove()
                      

                    })

                    this.fetch(this.page)

                }else{
                    this.updateLoader = false
                    swal({
                        text:res.data.msg,
                        icon: "error"
                    })

                }

            }catch(err){
                this.updateLoader = false
                swal({
                    text:"Hay algunos campos que debes revisar",
                    icon: "error"
                })

                this.errors = err.response.data.errors

            }
            this.updateLoader = false

        },
        async remove(id){

            try{
                
                this.errors = []

                let res = await axios.post("{{ url('api/raas/jefe-comunidad/suspend') }}", {id: id})

                if(res.data.success == true){

                    swal({
                        text:res.data.msg,
                        icon: "success"
                    })

                    this.fetch(this.page)

                }else{
                  
                    swal({
                        text:res.data.msg,
                        icon: "error"
                    })

                }

            }catch(err){
            
                swal({
                    text:"Hay algunos campos que debes revisar",
                    icon: "error"
                })

                this.errors = err.response.data.errors

            }
       

        },
        
       
        async getComunidades(){

            let res = await axios.get("{{ url('/api/comunidades') }}"+"/"+this.selectedParroquia)
            this.comunidades = res.data

        },

        async getPartidosPoliticos(){

            let res = await axios.get("{{ url('/api/partidos-politicos') }}")
            this.partidosPoliticos = res.data

        },

        async getMovilizaciones(){

            let res = await axios.get("{{ url('/api/movilizacion') }}")
            this.movilizaciones = res.data

        },

        isNumber(evt) {
            evt = (evt) ? evt : window.event;
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if ((charCode > 31 && (charCode < 48 || charCode > 57))) {
                evt.preventDefault();;
            } else {
                return true;
            }
        },
        async search(){

            this.searchLoading = true
            let res = await axios.get("{{ url('/api/raas/jefe-comunidad/search') }}"+"?cedula="+this.cedulaSearch+"&municipio_id="+ "{{ \Auth::user()->municipio_id }}")
            this.searchLoading = false

            this.jefesComunidad = res.data.data
            this.links = res.data.links
            this.currentPage = res.data.current_page
            this.totalPages = res.data.last_page


        }
        

    },
    created() {
        this.getPartidosPoliticos()
        this.getMovilizaciones()
        this.fetch()

    }
});
</script>

@endpush

@push("styles")

    <style>

        .active-link{
            background-color:#c0392b !important;
        }

    </style

@endpush
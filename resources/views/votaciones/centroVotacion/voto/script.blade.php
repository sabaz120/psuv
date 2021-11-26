<script type="text/javascript">

        var sync = false;
        var date = "";

        const app = new Vue({
            el: '#dev-ubch',
            data() {
                return {

                    linkClass:"page-link",
                    activeLinkClass:"page-link active-link bg-main",
                    searchText:"",
                    cedula:"",

                    votoLoading:false,
                    searchLoading:false,    
                    loading:false,
                    searchTextLoading:false,

                    instituciones:[],
                    selectedInstitucion:"",
                    movimientos:[],
                    selectedMovimiento:"",

                    registerType:"puntorojo",
                    searchCodigoCuadernillo:"",
                    nombreElector:"",
                    disableVoto:true,

                    votantes:[],
                    currentPage:1,
                    links:"",
                    totalPages:"",

                    centroVotacion:"{{ $centro_votacion->id }}",

                    authMunicipio:"{{ \Auth::user()->municipio_id ? \Auth::user()->municipio_id : 0}}"
                }
            },
            methods: {
                
                async getCentrosVotacion(){
                    this.loading = true
                    let res = await axios.get("{{ url('/api/votaciones/centro-votacion/get-centros') }}"+"?municipio_id="+this.authMunicipio)
                    this.loading = false
                    this.centrosVotacion = res.data.data

                    this.links = res.data.links
                    this.currentPage = res.data.current_page
                    this.totalPages = res.data.last_page

                },
                
                async fetch(url){
                    let res = null
                    this.loading = true
                    if(this.searchText == ""){
                        res = await axios.get(url.url)
                    }else{
                        res = await axios.get(url.url+"&searchText="+this.searchText+"&municipio_id="+this.authMunicipio)
                    }
                    this.loading = false

                    
                    this.centrosVotacion = res.data.data

                    this.links = res.data.links
                    this.currentPage = res.data.current_page
                    this.totalPages = res.data.last_page

                },
                async search(){

                    this.searchLoading = true
                    let res = await axios.get("{{ url('api/votaciones/centro-votacion/search-centros') }}",  {"params": {
                            "search": this.searchText,
                            "municipio_id": this.authMunicipio
                    }})
                    this.searchLoading = false

                    this.centrosVotacion = res.data.data
                    this.links = res.data.links
                    this.currentPage = res.data.current_page
                    this.totalPages = res.data.last_page
                    

                },
                isNumber(evt) {

                    this.disableVoto = true

                    evt = (evt) ? evt : window.event;
                    var charCode = (evt.which) ? evt.which : evt.keyCode;
                    if ((charCode > 31 && (charCode < 48 || charCode > 57))) {
                        evt.preventDefault();;
                    } else {
                        return true;
                    }
                },
                async searchByCodigoCuadernillo(){
                   
                    this.nombreElector = ""
                    this.searchLoading = true
                    let res = await axios.post("{{ url('api/votaciones/centro-votacion/search-by-codigo-cuadernillo') }}", {
                        "codigoCuadernillo": this.searchCodigoCuadernillo,
                        "centroVotacionId": this.centroVotacion
                    })
                    this.searchLoading = false

                    if(res.data.success == true){
                        this.disableVoto = false
                        this.nombreElector = res.data.elector.elector.primer_nombre+" "+res.data.elector.elector.primer_apellido

                    }else{

                        swal({
                            text: res.data.msg,
                            icon:"error"
                        })

                    }

                },
                async searchByCedula(){
                   
                   this.nombreElector = ""
                   this.searchLoading = true
                   let res = await axios.post("{{ url('api/votaciones/centro-votacion/search-by-cedula') }}", {
                       "cedula": this.cedula,
                       "centroVotacionId": this.centroVotacion
                   })
                   this.searchLoading = false

                   if(res.data.success == true){
                       this.disableVoto = false
                       this.nombreElector = res.data.elector.elector.primer_nombre+" "+res.data.elector.elector.primer_apellido

                   }else{

                       swal({
                           text: res.data.msg,
                           icon:"error"
                       })

                   }

               },
                async ejercerVoto(){

                    if(this.nombreElector == ""){
                        
                        swal({
                            text: "Debe agregar un código de cuadernillo",
                            icon:"error"
                        })

                        return

                    }
               
                    this.votoLoading = true
                    let res = await axios.post("{{ url('api/votaciones/centro-votacion/update-voto') }}", {
                        "codigoCuadernillo": this.searchCodigoCuadernillo,
                        "centroVotacionId": this.centroVotacion
                    })
                    this.votoLoading = false

                    if(res.data.success == true){

                        swal({
                            text: res.data.msg,
                            icon:"success"
                        })

                        this.nombreElector = ""
                        this.searchCodigoCuadernillo = ""
                        this.disableVoto = true

                        this.getVotantes()

                    }else{

                        swal({
                            text: res.data.msg,
                            icon:"error"
                        })

                    }

                },
                async ejercerVotoInstitucion(){

                    if(this.nombreElector == ""){
                        
                        swal({
                            text: "Debe agregar un código de cuadernillo",
                            icon:"error"
                        })

                        return

                    }

                    if(this.selectedInstitucion == ""){
                        
                        swal({
                            text: "Debes seleccionar una institución",
                            icon:"error"
                        })

                        return

                    }

                    this.votoLoading = true
                    let res = await axios.post("{{ url('api/votaciones/centro-votacion/update-voto-instituciones') }}", {
                        "cedula": this.cedula,
                        "institucion": this.selectedInstitucion,
                        "centroVotacionId": this.centroVotacion
                    })
                    this.votoLoading = false

                    if(res.data.success == true){

                        swal({
                            text: res.data.msg,
                            icon:"success"
                        })

                        this.nombreElector = ""
                        this.cedula = ""
                        this.selectedInstitucion = ""
                        this.disableVoto = true

                        this.getVotantes()

                    }else{

                        swal({
                            text: res.data.msg,
                            icon:"error"
                        })

                    }

                },
                async ejercerVotoMovimiento(){

                    if(this.nombreElector == ""){
                        
                        swal({
                            text: "Debe agregar un código de cuadernillo",
                            icon:"error"
                        })

                        return

                    }

                    if(this.selectedMovimiento == ""){
                        
                        swal({
                            text: "Debes seleccionar un movimiento",
                            icon:"error"
                        })

                        return

                    }

                    this.votoLoading = true
                    let res = await axios.post("{{ url('api/votaciones/centro-votacion/update-voto-instituciones') }}", {
                        "cedula": this.cedula,
                        "institucion": this.selectedMovimiento,
                        "centroVotacionId": this.centroVotacion
                    })
                    this.votoLoading = false

                    if(res.data.success == true){

                        swal({
                            text: res.data.msg,
                            icon:"success"
                        })

                        this.nombreElector = ""
                        this.cedula = ""
                        this.selectedMovimiento = ""
                        this.disableVoto = true

                        this.getVotantes()

                    }else{

                        swal({
                            text: res.data.msg,
                            icon:"error"
                        })

                    }

                },
                async getVotantes(){

                    let res = await axios.get("{{ url('/api/votaciones/centro-votacion/get-votantes') }}"+"?centroVotacionId="+this.centroVotacion)
                    this.votantes = res.data.data

                    this.links = res.data.links
                    this.currentPage = res.data.current_page
                    this.totalPages = res.data.last_page

                },
                async fetch(url){
                    let res = null
                    if(this.searchText == ""){
                        res = await axios.get(url.url+"&centroVotacionId="+this.centroVotacion)
                    }else{
                        res = await axios.get(url.url+"&searchText="+this.searchText+"&centroVotacionId="+this.centroVotacion)
                    }
                    
                    this.votantes = res.data.data

                    this.links = res.data.links
                    this.currentPage = res.data.current_page
                    this.totalPages = res.data.last_page

                },

                async getInstituciones(){

                    let res = await axios.get("{{ url('api/instituciones') }}")
                    this.instituciones = res.data
                },

                async getMovimientos(){

                    let res = await axios.get("{{ url('api/movimientos') }}")
                    this.movimientos = res.data
                    
                },

                async searchVotanteCentro(){

                    this.searchTextLoading = true

                    let res = await axios.get("{{ url('/api/votaciones/centro-votacion/search-votantes') }}"+"?centroVotacionId="+this.centroVotacion+"&searchText="+this.searchText)
                    this.votantes = res.data.data

                    this.searchTextLoading = false

                    this.links = res.data.links
                    this.currentPage = res.data.current_page
                    this.totalPages = res.data.last_page

                },

                async remove(id){

                    swal({
                        title: "¿Estás seguro?",
                        text: "Eliminarás este voto!",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then(async (willDelete) => {

                        if (willDelete) {
                            let res = await axios.post("{{ url('api/votaciones/centro-votacion/delete-voto') }}", {id: id, registerType: this.registerType})

                            if(res.data.success == true){

                                swal({
                                    text:res.data.msg,
                                    icon: "success"
                                })

                                let url = {
                                    url:""
                                }
                                url.url = "{{ url('/api/votaciones/centro-votacion/get-votantes') }}"+"?centroVotacionId="+this.centroVotacion+"&page="+this.page
                                this.fetch(url)

                            }else{
                        
                                swal({
                                    text:res.data.msg,
                                    icon: "error"
                                })

                            }
                        }


                    })


                },

                resetName(){

                    this.nombreElector = ""

                }
                

            },
            created() {

                this.getVotantes()
                this.getInstituciones()
                this.getMovimientos()

            }
        });
    </script>
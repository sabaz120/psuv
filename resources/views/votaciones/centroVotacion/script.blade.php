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
                    personalSearchText:"",

                    searchLoading:false,
                    loading:false,

                    selectedCentroVotacion:"",

                    centrosVotacion:[],
                    currentPage:1,
                    links:"",
                    totalPages:"",

                    puntoRojoMunicipio:"",
                    puntoRojoParroquia:"",
                    puntoRojoUBCH:"",
                    puntoRojoJefeUBCH:"",
                    puntoRojoTelefono:"",
                    personalPuntoRojoData:[],

                    personalPuntoRojoCurrentPage:1,
                    personalPuntoRojoLinks:"",
                    personalPuntoRojoTotalPages:"",

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
                        res = await axios.get(url.url+"&municipio_id="+this.authMunicipio)
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
                async personalPuntoRojo(centroVotacion){

                    this.selectedCentroVotacion = centroVotacion.id
                    this.puntoRojoMunicipio = centroVotacion.parroquia.municipio.nombre
                    this.puntoRojoParroquia = centroVotacion.parroquia.nombre
                    this.puntoRojoUBCH = centroVotacion.nombre
                    this.puntoRojoJefeUBCH = centroVotacion.jefe_ubchs[0].personal_caracterizacion.full_name
                    this.puntoRojoTelefono = centroVotacion.jefe_ubchs[0].personal_caracterizacion.telefono_principal
                    
                    let res = await axios.get("{{ url('api/votaciones/centro-votacion/get-personal-punto-rojo') }}",  {"params": {
                        "centro_votacion_id": centroVotacion.id
                    }})

                    this.personalPuntoRojoData = res.data.data

                    this.personalPuntoRojoLinks = res.data.links
                    this.personalPuntoRojoCurrentPage = res.data.current_page
                    this.personalPuntoRojoTotalPages = res.data.last_page

                },

                async personalFetch(url){
                    let res = null
                    this.loading = true
                    if(this.personalSearchText == ""){
                        res = await axios.get(url.url+"&centro_votacion_id="+this.selectedCentroVotacion)
                    }else{
                        res = await axios.get(url.url+"&search="+this.personalSearchText+"&centro_votacion_id="+this.selectedCentroVotacion)
                    }
                    this.loading = false

                    
                    this.personalPuntoRojoData = res.data.data

                    this.personalPuntoRojoLinks = res.data.links
                    this.personalPuntoRojoCurrentPage = res.data.current_page
                    this.personalPuntoRojoTotalPages = res.data.last_page

                },
                async searchPersonal(){

      
                    let res = await axios.get("{{ url('api/votaciones/centro-votacion/search-personal-punto-rojo') }}",  {"params": {
                            "search": this.personalSearchText,
                            "centro_votacion_id": this.selectedCentroVotacion
                    }})
   

                    this.personalPuntoRojoData = res.data.data

                    this.personalPuntoRojoLinks = res.data.links
                    this.personalPuntoRojoCurrentPage = res.data.current_page
                    this.personalPuntoRojoTotalPages = res.data.last_page


                },
                

            },
            created() {

                this.getCentrosVotacion()

            }
        });
    </script>
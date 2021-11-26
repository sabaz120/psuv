<script type="text/javascript">
    var app = new Vue({
        el: '#content',
        data() {
            return {

                linkClass:"page-link",
                activeLinkClass:"page-link active-link bg-main",
                currentPage:1,
                links:"",
                totalPages:"",
                email:"",

                loading:false,
                emailLoading:false,
                searchLoader:false,

                selectedMunicipio:"0",
                selectedParroquia:"0",
                selectedCentroVotacion:"",
                
                municipios:[],
                parroquias:[],
                centrosVotacion:[],
                
                authMunicipio:"{{ \Auth::user()->municipio_id ? \Auth::user()->municipio_id : 0}}"
            }
        },
        methods: {

            async getMunicipios(){

                this.selectedParroquia = "0"
                this.selectedComunidad = "0"

                let res = await axios.get("{{ url('/api/municipios') }}")
                this.municipios = res.data

            },
            async getParroquias(){
                
                this.selectedParroquia = "0"
                this.selectedComunidad = "0"

                let res = await axios.get("{{ url('/api/parroquias') }}"+"/"+this.selectedMunicipio)
                this.parroquias = res.data

            },

            async getCentrosVotacion(){

                if(this.selectedParroquia == 0){

                    swal({
                        text:"Debe seleccionar una parroquia",
                        icon:"warning"
                    })

                    return

                }

                this.searchLoader = true
                let res = await axios.get("{{ url('/api/cuadernillo') }}"+"?parroquia_id="+this.selectedParroquia)
                this.centrosVotacion = res.data.centros
                this.searchLoader = false

            },

            async generatePDF(centro_votacion){

                let res = await this.countElectores(centro_votacion)

                if(res.amount < 400){
                    window.location.href = "{{ url('cuadernillo/generate-pdf/') }}"+"/"+centro_votacion
                }
                else{

                    if(res.descargado != null){

                        if(res.descargado.file){
                            window.location.href = res.descargado.file
                            return 
                        }
                        
                    }

                    this.selectedCentroVotacion = centro_votacion
                    $(".REPmodal").modal()

                }
                

            },

            async generateUBCHPDF(municipio_id, parroquia_id, centro_votacion){

                
                window.location.href = "{{ url('cuadernillo/generate-pdf-ubch') }}"+"?municipio_id="+municipio_id+"&parroquia_id="+parroquia_id+"&centro_votacion_id="+centro_votacion



            },
            async countElectores(centro_votacion){

                let res = await axios.get("{{ url('api/cuadernillo/count-electores/') }}"+"/"+centro_votacion)
                return res.data

            },
            async enviarCorreo(){

                if(this.email == ""){
                    swal({
                        text:"Correo electrónico es requerido",
                        icon:"error"
                    })

                    return
                }

                let response = this.validateCorreo(this.email)
                if(!response){
                    swal({
                        text:"Formato del correo eletrónico no es válido",
                        "icon": "error"
                    })

                    return
                }

                this.emailLoading = true
                let res = await axios.post("{{ url('/api/cuadernillo/store-export-job') }}", {"centroVotacion": this.selectedCentroVotacion, "email": this.email})
                this.emailLoading = false
                if(res.data.success == true){
                    this.email = ""
                    swal({
                        text:res.data.msg,
                        icon:"success"
                    }).then(ans =>{
                        window.location.reload()
                    })

                }

            },
            validateCorreo(email){
                const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                return re.test(String(email).toLowerCase());
            }


        },
        async created() {

            await this.getMunicipios()
            this.selectedMunicipio = "0"
            this.selectedParroquia = "0"
            this.selectedComunidad = "0"
            this.selectedMunicipio = this.authMunicipio

            if(this.selectedMunicipio != "0"){
                await this.getParroquias()
            }


        }
    });
</script>
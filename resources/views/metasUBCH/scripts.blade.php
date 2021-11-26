<script type="text/javascript">
    var app = new Vue({
        el: '#content',
        data() {
            return {
                selectedMunicipio:"0",
                selectedParroquia:"0",
                selectedCentroVotacion:"0",
                municipios:[],
                parroquias:[],
                centrosVotacion:[],
                email:"",
                emailError:"",
                loading:false,
                authMunicipio:"{{ \Auth::user()->municipio_id ? \Auth::user()->municipio_id : 0}}"
            }
        },
        methods: {

            async getMunicipios(){

                this.selectedParroquia = "0"
                this.selectedCentroVotacion = "0"

                let res = await axios.get("{{ url('/api/municipios') }}")
                this.municipios = res.data

            },
            async getParroquias(){
        
                this.selectedCentroVotacion = "0"

                let res = await axios.get("{{ url('/api/parroquias') }}"+"/"+this.selectedMunicipio)
                this.parroquias = res.data

            },

            async getCentroVotacion(){

                let res = await axios.get("{{ url('/api/centro-votacion') }}"+"/"+this.selectedParroquia)
                this.centrosVotacion = res.data

            },
            async download(){

                //let paramsCentroVotacion = this.selectedParroquia != "0" ? "&selectedCentroVotacion="+this.selectedCentroVotacion : ""
                let paramsParroquia = this.selectedMunicipio != "0" ? "&selectedParroquia="+this.selectedParroquia : ""
                let paramsMunicipio = "selectedMunicipio="+this.selectedMunicipio

                /*if(this.selectedParroquia == 0){
                    let amount = await this.checkAmount();
                    
                    if(amount > 100000){
                        
                        $(".REPmodal").modal()
                        return
                    }

                }*/
                

                window.location.href="{{ url('api/metas-ubch/download') }}"+"?"+paramsMunicipio+paramsParroquia
                
                
            },
            async checkAmount(){

                let res = await axios.post("{{ url('/listado/amount/rep') }}", {"municipio": this.selectedMunicipio})
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

                this.loading = true
                let res = await axios.post("{{ url('/listado/rep/store-export-job') }}", {"entity": this.selectedMunicipio != 0 ? "municipios" : "todos", "entity_id": this.selectedMunicipio, "email": this.email})
                this.loading = false
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
        created() {

            this.getMunicipios()
            this.selectedMunicipio = "0"
            this.selectedParroquia = "0"
            this.selectedCentroVotacion = "0"
            this.selectedMunicipio = this.authMunicipio

            if(this.selectedMunicipio != "0"){
                this.getParroquias()
            }

        }
    });
</script>
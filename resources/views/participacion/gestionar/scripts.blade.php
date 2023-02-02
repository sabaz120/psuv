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
                selectedTipo:"UBCH",

                clickCount:0,
                secondaryGraphic:0,
                type:"",
                metaGeneral:0,
                cargados:0,
                centroVotacionMetas:[],
                loading:false,
                secondaryInfo:"",

                selectedMunicipio:"0",
                selectedParroquia:"0",
                selectedComunidad:"0",
                selectedCentroVotacion:"0",
                searchedMunicipio:"0",
                searchedParroquia:"0",
                searchedCentroVotacion:"0",
                selectedCalle:"0",
                municipios:[],
                parroquias:[],
                comunidades:[],
                calles:[],
                centrosVotacion:[],
                loading:false,
                authMunicipio:"{{ \Auth::user()->municipio_id ? \Auth::user()->municipio_id : 0}}",
                elector:{
                    full_name:"",
                    cedula:"",
                    telefono_principal:"",
                    tipo_voto:"duro",
                    partido_politico_id:"1",
                    movilizacion_id:"1",
                    cedula_encontrada:""
                },
                cedula_elector:"",
                telefono_principal_elector:"",
                personal_caraterizacion:null,
                assignedPersons:[]
            }
        },
        methods: {
            async getPersons(){
                let res = await axios.get("{{ url('/api/participacion') }}",{
                    params:{
                        tipo:this.selectedTipo,
                        municipio_id:this.selectedMunicipio,
                        parroquia_id:this.selectedParroquia,
                        centro_votacion_id:this.selectedCentroVotacion,
                        comunidad_id:this.selectedComunidad,
                        calle_id:this.selectedCalle,
                    }
                })
                this.assignedPersons = res.data.data.map(function(person){
                    return {
                        id:person.id,
                        cedula:person.personal_caracterizacion.cedula,
                        centro_votacion_nombre:person.personal_caracterizacion.centro_votacion.nombre,
                        full_name:person.personal_caracterizacion.full_name,
                        telefono_principal:person.personal_caracterizacion.telefono_principal,
                    }
                })
            },
            async getMunicipios(){
                this.selectedParroquia = "0"
                this.selectedComunidad = "0"
                this.selectedCentroVotacion="0"
                let res = await axios.get("{{ url('/api/municipios') }}")
                this.municipios = res.data
                this.getPersons();
            },
            async getParroquias(){
                this.selectedParroquia = "0"
                this.selectedComunidad = "0"
                this.selectedCentroVotacion="0"
                let res = await axios.get("{{ url('/api/parroquias') }}"+"/"+this.selectedMunicipio)
                this.parroquias = res.data
                this.getPersons();
            },
            async getComunidades(){
                if(this.selectedTipo=='UBCH'){
                    this.comunidades=[];
                    return false;
                } 
                let res = await axios.get("{{ url('/api/comunidades') }}"+"/"+this.selectedParroquia)
                this.comunidades = res.data
                this.getPersons();
            },
            async getCalles(){
                if(this.selectedTipo=='UBCH' || this.selectedTipo=='Comunidad'){
                    this.calles=[];
                    return false;
                }
                let filters = {
                    comunidad_id:this.selectedComunidad,
                    order_by:"nombre",
                    order_direction:"ASC"
                }
                let res = await axios.get("{{ route('api.calles.index') }}"+"/",{
                    params: filters
                })
                this.calles = res.data
                this.getPersons();
            },
            async getCentroVotacion(){
                if(this.selectedParroquia=="0"){
                    this.centrosVotacion = [];
                    return false;
                }
                this.selectedCentroVotacion = "0"
                let res = await axios.get("{{ url('/api/centro-votacion') }}"+"/"+this.selectedParroquia)
                this.centrosVotacion = res.data
                this.getPersons();
            },
            changeType(){
                if(this.selectedTipo=="UBCH"){
                    this.selectedComunidad="0";
                    this.selectedCalle="0";
                    this.selectedCentroVotacion="0"
                }else if(this.selectedTipo=="Comunidad"){
                    this.selectedCalle="0";
                }
                this.assignedPersons=[];
                this.getPersons();
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
            async obtenerElector() {
                if(this.elector.cedula==""){
                    swal({
                        text:"Debe ingresar una cédula válida",
                        icon:"error"
                    });
                    return false;
                }
                try {
                    this.loading = true;
                    let filters = {
                        cedula:this.elector.cedula
                    }
                    const response = await axios({
                        method: 'GET',
                        responseType: 'json',
                        url: "{{ url('elector/search-by-cedula') }}",
                        params: filters
                    });
                    this.loading = false;
                    if(response.data.success==true){
                        this.elector=response.data.elector;
                        this.elector.cedula_encontrada=this.elector.cedula;
                        if(response.data.elector.tipo_voto){
                            this.elector.tipo_voto=response.data.elector.tipo_voto.toLowerCase();
                        }else{
                            this.elector.tipo_voto="duro"
                        }
                        if(response.data.elector.partido_politico_id){
                            this.elector.partido_politico_id=response.data.elector.partido_politico_id;
                        }else{
                            this.elector.partido_politico_id="1"
                        }
                        if(response.data.elector.movilizacion_id){
                            this.elector.movilizacion_id=response.data.elector.movilizacion_id;
                        }else{
                            this.elector.movilizacion_id="1";
                        }
                        if(response.data.elector.telefono_principal){
                            this.elector.telefono_principal=response.data.elector.telefono_principal;
                        }
                    }else{
                        this.elector.cedula_encontrada="";
                        this.elector.full_name="";
                        this.elector.telefono_principal="";
                        if(response.data.success == false){
                            swal({
                                text:response.data.msg,
                                icon:"error"
                            })

                            return
                        }
                    }
                } catch (err) {
                    this.loading = false;
                    console.log(err)
                }
            },
            async assign(){
                if(this.elector.cedula_encontrada==""){
                    swal({
                        text:"Debe ingresar un elector",
                        icon:"error"
                    });
                    return false;
                }
                try {
                    this.loading = true;
                    const response = await axios({
                        method: 'POST',
                        responseType: 'json',
                        url: "{{ route('api.participacion.store') }}",
                        data: {
                            tipo:this.selectedTipo,
                            cedula:this.elector.cedula_encontrada,
                            nacionalidad:this.elector.nacionalidad,
                            primer_apellido:this.elector.primer_apellido,
                            primer_nombre:this.elector.primer_nombre,
                            sexo:this.elector.sexo,
                            elector_centro_votacion_id:this.elector.centro_votacion_id,
                            estado_id:this.elector.estado_id,
                            municipio_id:this.elector.municipio_id,
                            parroquia_id:this.elector.parroquia_id,
                            telefono_principal:this.elector.telefono_principal,
                            tipo_voto:this.elector.tipo_voto,
                            partido_politico_id:this.elector.partido_politico_id,
                            movilizacion_id:this.elector.movilizacion_id,
                            centro_votacion_id:this.selectedCentroVotacion,
                            calle_id:this.selectedCalle,
                            comunidad_id:this.selectedComunidad,
                        }
                    });
                    this.loading = false;
                    this.assignedPersons.push({
                        id:response.data.data.id,
                        centro_votacion_nombre:response.data.data.personal_caracterizacion.centro_votacion.nombre,
                        cedula:this.elector.cedula_encontrada,
                        full_name:this.elector.full_name,
                        telefono_principal:this.elector.telefono_principal,
                    })
                    swal({
                        text:response.data.message,
                        icon: "success"
                    }).then(ans => {
                        this.elector.cedula=""
                        this.elector.cedula_encontrada=""
                        this.elector.full_name=""
                        this.elector.telefono_principal=""
                    })
                } catch (err) {
                    this.loading = false;
                    console.log(err)
                    swal({
                        text:err.response.data.message,
                        icon:"error"
                    });
                }
            },
            async deleteAssignPerson(index,id){
                try {
                    this.loading = true;
                    const response = await axios({
                        method: 'DELETE',
                        responseType: 'json',
                        url: "{{ route('api.participacion.delete') }}",
                        data: {
                            tipo:this.selectedTipo,
                            id_participacion:id
                        }
                    });
                    this.loading = false;
                    this.assignedPersons.splice(index,1)
                    swal({
                        text:response.data.message,
                        icon: "success"
                    }).then(ans => {

                    })
                } catch (err) {
                    this.loading = false;
                    console.log(err)
                    swal({
                        text:err.response.data.message,
                        icon:"error"
                    });
                }
            }
        },
        async created() {
            await this.getMunicipios()
            this.selectedMunicipio = "0"
            this.selectedParroquia = "0"
            this.selectedComunidad = "0"
            this.selectedCentroVotacion = "0"
            this.selectedMunicipio = this.authMunicipio
            if(this.selectedMunicipio != "0"){
                await this.getParroquias()
            }
            await this.getPersons();
        }
    });
</script>
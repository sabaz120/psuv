<script type="text/javascript">


    var vue_instance = new Vue({
        el: '#dev-comunidad',
        data() {
            return {

                linkClass:"page-link",
                activeLinkClass:"page-link active-link bg-main",
                action:"create",
                authMunicipio:"{{ \Auth::user()->municipio_id != null ? \Auth::user()->municipio_id : '' }}",
                
                comunidades:[],
                municipios:[],
                selectedMunicipio:"",
                parroquias:[],
                selectedParroquia:"",
                centroVotaciones:[],
                selectedCentroVotacion:"",
                selectedId:"",
                nombre:"",
                errors:[],

                nombreSearch:"",
                loading:false,
                searchLoading:false,
                storeLoader:false,
                updateLoader:false,

                modalTitle:"Crear comunidad",
                currentPage:1,
                links:"",
                totalPages:"",
            }
        },
        methods: {
            async getCentroVotacion(){
                this.selectedCentroVotacion = ""
                let res = await axios.get("{{ url('/api/centro-votacion') }}"+"/"+this.selectedParroquia)
                this.centroVotaciones = res.data
            },
            create(){
                this.selectedId = ""
                this.action = "create"
                this.modalTitle = "Crear comunidad",
                this.selectedMunicipio = ""
                this.selectedParroquia = ""
                this.selectedCentroVotacion = ""
                this.nombre=""
                this.errors = []
                this.centroVotaciones=[];
            },
            async edit(comunidad){
                this.selectedMunicipio = comunidad.centro_votacion?.parroquia?.municipio_id
                await this.getParroquias()
                this.selectedParroquia = comunidad.centro_votacion?.parroquia_id;
                await this.getCentroVotacion();
                this.selectedCentroVotacion = comunidad.centro_votacion_id;
                this.selectedId = comunidad.id
                this.action = "edit"
                this.modalTitle = "Editar comunidad"
                this.nombre = comunidad.nombre
            },
            async searchCedula(){

                if(this.cedula == ""){
                    swal({
                        text:"Debes ingresar una cédula",
                        icon:"error"
                    })

                    this.cedula = ""

                    return
                }

                this.cedulaSearching = true
                this.readonlyCedula = true

                let res = await axios.post("{{ url('raas/ubch/search-by-cedula') }}", {cedula: this.cedula})

                if(res.data.success == false){
                    this.readonlyCedula = false
                    this.create()
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
            async fetch(link = ""){

                let res = await axios.get(link == "" ? "{{ url('api/comunidad/fetch') }}" + "?_token={{ csrf_token() }}" : link.url+"&_token={{ csrf_token() }}")
                this.comunidades = res.data.data
                this.links = res.data.links
                this.currentPage = res.data.current_page
                this.totalPages = res.data.last_page


            },
            async store(){

                try{
                    
                    this.errors = []
                    this.storeLoader = true

                    let res = await axios.post("{{ url('api/comunidad/store') }}", {
                        parroquia_id: this.selectedParroquia, 
                        centro_votacion_id: this.selectedCentroVotacion, 
                        "nombre": this.nombre
                    })
                    
                    this.storeLoader = false

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

                    let res = await axios.post("{{ url('api/comunidad/update') }}", {
                        nombre: this.nombre, 
                        parroquia_id: this.selectedParroquia, 
                        centro_votacion_id: this.selectedCentroVotacion, 
                        id: this.selectedId
                    })
                    
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
                Swal.fire({
                title: '¿Estás seguro de eliminar este registro?',
                text: "No podrás revertirlo",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, eliminar',
                cancelButtonText: 'Cancelar',
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        try{
                            let res = await axios.post("{{ url('api/comunidad/delete') }}", {id: id})
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
                    }
                })
            },
            async getMunicipios(){

                this.selectedParroquia = ""
                this.selectedCentroVotacion = ""

                let res = await axios.get("{{ url('/api/municipios') }}")
                this.municipios = res.data

            },
            async getParroquias(){
                

                let res = await axios.get("{{ url('/api/parroquias') }}"+"/"+this.selectedMunicipio)
                this.parroquias = res.data

            },

            async search(){

                this.searchLoading = true
                let res = await axios.get("{{ url('/api/comunidad/search') }}", {"params": {
                    "search": this.nombreSearch,
                    "_token": "{{ csrf_token() }}"
                }})
                this.searchLoading = false

                this.comunidades = res.data.data
                this.links = res.data.links
                this.currentPage = res.data.current_page
                this.totalPages = res.data.last_page
                

            }
        },
        created() {
            this.selectedMunicipio = this.authMunicipio
            this.getMunicipios()

            if(this.selectedMunicipio != ""){
                this.getParroquias()
            }

            this.fetch()

        }
    });
</script>
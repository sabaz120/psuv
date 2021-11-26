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

                searchText:"",

                storeLoader:false,
                updateLoader:false,
                searchLoader:false,

                personalSala:[],
                municipios:[],
                errors:[],
                readonlyMunicipio:true,
                readonlyCedula:false,
                modalTitle:"Nuevo personal",
                action:"create",

                personalId:"",
                selectedMunicipio:"",
                nombre:"",
                apellido:"",
                cedula:"",
                telefono:"",
                rol:"jefe de sala",

                authMunicipio:"{{ \Auth::user()->municipio_id ? \Auth::user()->municipio_id : 0}}"
            }
        },
        methods: {

            create(){
                this.errors = []
                this.modalTitle = "Nuevo personal"
                this.action = "create"
                this.nombre = ""
                this.apellido = ""
                this.cedula = ""
                this.telefono = ""
                this.rol = "jefe de sala"
                this.personalId = ""
                this.readonlyCedula = false
                this.readonlyMunicipio = false

                if(this.authMunicipio == 0){
                    this.readonlyMunicipio = false
                    this.selectedMunicipio = ""
                }else{
                    this.readonlyMunicipio = true
                    this.selectedMunicipio = this.authMunicipio
                }

            },

            edit(personal){
                this.errors = []
                this.personalId = personal.id
                this.readonlyMunicipio = true
                this.selectedMunicipio = personal.municipio_id
                this.modalTitle = "Editar personal"
                this.action = "edit"
                this.nombre = personal.nombre
                this.apellido = personal.apellido
                this.cedula = personal.cedula
                this.telefono = personal.telefono_principal
                this.rol = personal.rol
                this.readonlyCedula = true

            },

            async store(){

                try{
                
                    this.errors = []
                    this.storeLoader = true

                    let res = await axios.post("{{ url('api/sala-tecnica/store-personal') }}", {
                                "nombre": this.nombre,
                                "apellido": this.apellido,
                                "cedula": this.cedula,
                                "telefono": this.telefono,
                                "rol": this.rol,
                                "municipio": this.selectedMunicipio
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

                        let url = "{{ url('api/sala-tecnica/get-personal') }}"
                        this.fetch(url)

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

                    let res = await axios.post("{{ url('api/sala-tecnica/update-personal') }}", {
                        "nombre": this.nombre,
                        "apellido": this.apellido,
                        "cedula": this.cedula,
                        "telefono": this.telefono,
                        "rol": this.rol,
                        "municipio": this.selectedMunicipio,
                        "personalId": this.personalId
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

                        let url = "{{ url('api/sala-tecnica/get-personal') }}"
                        this.fetch(url)

                    }else{

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

            async searchPersonal(){

                this.searchLoader = true
                let url = ""
                if(this.searchText != ""){
                    url = "{{ url('/api/sala-tecnica/search-personal') }}"+"?municipio_id="+this.authMunicipio+"&searchText="+this.searchText
                
                }else{

                    url = "{{ url('api/sala-tecnica/get-personal') }}"

                }
                await this.fetch(url)
                this.searchLoader = false

            },

            async fetch(url){

                if(this.searchText != ""){
                    url = url+"&municipio_id="+this.authMunicipio+"&searchText="+this.searchText
                }

                let res = await axios.get(url)
                this.personalSala = res.data.data

                this.links = res.data.links
                this.currentPage = res.data.current_page
                this.totalPages = res.data.last_page

            },
            async getMunicipios(){

                let res = await axios.get("{{ url('/api/municipios') }}")
                this.municipios = res.data

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

            async remove(personal){

                swal({
                    title: "¿Estás seguro?",
                    text: "Eliminarás este personal de la sala técnica!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then(async (willDelete) => {

                    if (willDelete) {
                        let res = await axios.post("{{ url('api/sala-tecnica/delete-personal') }}", {id: personal.id})

                        if(res.data.success == true){

                            swal({
                                text:res.data.msg,
                                icon: "success"
                            })

                            let url = "{{ url('api/sala-tecnica/get-personal') }}"+"?page="+this.page
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


        },
        async created() {

            await this.getMunicipios()

            if(this.authMunicipio == 0){
                this.readonlyMunicipio = false
                this.selectedMunicipio = ""
            }else{
                this.readonlyMunicipio = true
                this.selectedMunicipio = this.authMunicipio
            }

            

            let url = "{{ url('api/sala-tecnica/get-personal') }}"
            this.fetch(url)

        }
    });
</script>
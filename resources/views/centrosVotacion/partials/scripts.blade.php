<!-- Image upload -->
<script type="text/javascript">
    /********* VUE ***********/
    var vue_instance = new Vue({
        el: '#content',
        components: {},
        data: {
            loading: true,
            action:"create",//create,edit,suspend
            actionTestigo:"create",
            actionPuntoRojo:"create",
            //Class
            linkClass:"page-link",
            activeLinkClass:"page-link active-link bg-main",

            //Form
            formMesa:{
                transmision:false,
                numero_mesa:"",
                hora_transmision:"00:00:00",
                observacion:"",
            },
            formTestigo:{
                mesa_id:"",
                personal_caracterizacion:false,
                telefono_principal:"",
                telefono_secundario:"",
                partido_politico_id:4,
                tipo_voto:"duro",
                movilizacion_id:3,
            },
            formPuntoRojo:{
                nombre:"",
                apellido:"",
                cedula:"",
                telefono_principal:"",
                telefono_secundario:"",
            },
            entityId:null,
            entityMesaId:null,
            entityTestigoId:null,
            entityPuntoRojoId:null,
            searchText:"",
            cedula_testigo:"",
            cedula_testigo_error:"",
            //Array data
            results:[],
            mesas:[],
            testigos:[],
            personal:[],
            //paginate centros votacion
            currentPage:1,
            links:"",
            totalPages:"",
   
        },
        created: function() {
            this.$nextTick(async function() {
                await this.fetch();
                this.loading = false;
            });
        },
        methods: {
            async initMesa(entity){
                this.entityId=entity.id;
                await this.getMesas();
                await this.setNumeroMesa();
            },
            async initTestigo(entity){
                this.entityId=entity.id;
                await this.getMesas();
                await this.getTestigos();
            },
            async initPuntoRojo(entity){
                this.entityId=entity.id;
                await this.getPersonal();
            },
            async fetch(link = ""){
                this.loading = true;
                let filters={
                    includes:[
                        "mesas",
                        "parroquia.municipio"
                    ],
                    mesasCount:1,
                    electoresCount:1,
                    search:this.searchText,
                    municipio_id:"{{Auth::user()->municipio_id ? Auth::user()->municipio_id : 0}}",
                };

                //Agregar validacion municipio id del user autenticado
                if(link==""){
                    filters.page=1;
                }
                let res = await axios.get(link == "" ? "{{ route('api.centros.votacion.index') }}" : link.url,{
                    params:filters
                })
                this.results = res.data.data
                this.links = res.data.links
                this.currentPage = res.data.current_page
                this.totalPages = res.data.last_page
                this.loading = false;
            },
            setNumeroMesa(){
                this.formMesa.numero_mesa=this.mesas.length+1;
                let exist=0;
                for(let i=0;i<this.mesas.length;i++){
                    if(this.mesas[i].numero_mesa==this.formMesa.numero_mesa){
                        exist=1;
                        break;
                    }
                }//for
                if(exist){
                    for(n=1;n<=this.mesas.length;n++){
                        let numberExist=0;
                        for(let i=0;i<this.mesas.length;i++){
                            if(this.mesas[i].numero_mesa==n){
                                numberExist=1;
                                break;
                            }
                        }//for
                        if(!numberExist){
                            this.formMesa.numero_mesa=n;
                            break;
                        }
                    }
                }//if(exist)
            },
            //////////////////////////////////////////////init personal punto rojo
            async getPersonal(){
                this.loading = true;
                let filters={
                    includes:[
                    ],
                    centro_votacion_id:this.entityId,
                    order_by:"cedula",
                    order_direction:"ASC"
                };
                let res = await axios.get("{{ route('api.puntorojo.index') }}",{
                    params:filters
                })
                this.personal = res.data.data
                this.loading = false;
            },
            async storePuntoRojo(){
                try {
                    if(this.formPuntoRojo.cedula==""){
                        swal({
                        text:"Debe indicar la cédula",
                        icon:"error"
                        });
                        return false;
                    }else if(this.formPuntoRojo.nombre==""){
                        swal({
                        text:"Debe indicar el nombre",
                        icon:"error"
                        });
                        return false;
                    }else if(this.formPuntoRojo.apellido==""){
                        swal({
                        text:"Debe indicar el apellido",
                        icon:"error"
                        });
                        return false;
                    }else if(this.formPuntoRojo.telefono_principal==""){
                        swal({
                        text:"Debe indicar el teléfono principal",
                        icon:"error"
                        });
                        return false;
                    }
                    let dataForm=this.formPuntoRojo;
                    dataForm.centro_votacion_id=this.entityId;
                    this.loading = true;
                    const response = await axios({
                        method: 'POST',
                        responseType: 'json',
                        url: "{{ route('api.puntorojo.store') }}",
                        data: dataForm
                    });
                    this.loading = false;
                    swal({
                        text:response.data.message,
                        icon: "success"
                    }).then(ans => {
                        // $('.marketModal').modal('hide')
                        // $('.modal-backdrop').remove()

                    })
                    await this.clearFormPuntoRojo();
                    await this.getPersonal();
                } catch (error) {
                    this.loading = false;
                    let msg=error.response.data.message;
                    if(msg=="The given data was invalid."){
                        msg="Los datos proporcionados no son válidos.";
                    }
                    swalAlert("error",msg, errorsToHtmlList(error.response.data.errors));
                    // swal({
                    //     text:error.response.data.message,
                    //     icon:"error"
                    // });

                }
            },
            editPuntoRojo(entity){
                this.actionPuntoRojo="edit";
                this.entityPuntoRojoId=entity.id;
                this.formPuntoRojo.cedula=entity.cedula;
                this.formPuntoRojo.nombre=entity.nombre;
                this.formPuntoRojo.apellido=entity.apellido;
                this.formPuntoRojo.telefono_principal=entity.telefono_principal;
                this.formPuntoRojo.telefono_secundario=entity.telefono_secundario;
            },
            async suspendPuntoRojo(entityId){
                try {
                    this.loading = true;
                    const response = await axios({
                        method: 'DELETE',
                        responseType: 'json',
                        url: "{{ url('api/personal-punto-rojo') }}"+"/"+entityId,
                        data: {}
                    });
                    this.loading = false;
                    swal({
                        text:response.data.message,
                        icon: "success"
                    }).then(ans => {

                    })
                    await this.clearFormPuntoRojo();
                    await this.getPersonal();
                } catch (error) {
                    this.loading = false;
                    let msg="";
                    if(error.response){
                        msg=error.response.data.message;
                        if(msg=="The given data was invalid."){
                        msg="Los datos proporcionados no son válidos.";
                    }
                    swalAlert("error",msg, errorsToHtmlList(error.response.data.errors));
                    }
                }//catch
            },
            async updatePuntoRojo(){
                let dataForm=this.formPuntoRojo;
                try {
                    if(this.formPuntoRojo.cedula==""){
                        swal({
                        text:"Debe indicar la cédula",
                        icon:"error"
                        });
                        return false;
                    }else if(this.formPuntoRojo.nombre==""){
                        swal({
                        text:"Debe indicar el nombre",
                        icon:"error"
                        });
                        return false;
                    }else if(this.formPuntoRojo.apellido==""){
                        swal({
                        text:"Debe indicar el apellido",
                        icon:"error"
                        });
                        return false;
                    }else if(this.formPuntoRojo.telefono_principal==""){
                        swal({
                        text:"Debe indicar el teléfono principal",
                        icon:"error"
                        });
                        return false;
                    }
                    this.loading = true;
                    const response = await axios({
                        method: 'PUT',
                        responseType: 'json',
                        url: "{{ url('api/personal-punto-rojo') }}"+"/"+this.entityPuntoRojoId,
                        params: dataForm
                    });
                    this.loading = false;
                    swal({
                        text:response.data.message,
                        icon: "success"
                    }).then(ans => {
                        // $('.marketModal').modal('hide')
                        // $('.modal-backdrop').remove()

                    })
                    await this.clearFormPuntoRojo();
                    await this.getPersonal();
                } catch (error) {
                    this.loading = false;
                    let msg="";
                    if(error.response.data){
                        msg=error.response.data.message;
                    }
                    if(msg=="The given data was invalid."){
                        msg="Los datos proporcionados no son válidos.";
                    }
                    swalAlert("error",msg, errorsToHtmlList(error.response.data.errors));
                }
            },
            clearFormPuntoRojo(){
                this.formPuntoRojo.nombre="";
                this.formPuntoRojo.apellido="";
                this.formPuntoRojo.cedula="";
                this.formPuntoRojo.telefono_principal="";
                this.formPuntoRojo.telefono_secundario="";
                this.actionPuntoRojo="create";
            },
            ///////////////////////////////////////////////////////////////////////////////////////////End personal punto rojo
            async getTestigos(){
                this.loading = true;
                let filters={
                    includes:[
                        "mesa",
                        "personalCaracterizacion"
                    ],
                    centro_votacion_id:this.entityId,
                    order_by:"mesa_id",
                    order_direction:"ASC"
                };
                //Agregar validacion de testigos de la ultima eleccion (Agregar filtro en backend)
                let res = await axios.get("{{ route('api.testigos.index') }}",{
                    params:filters
                })
                this.testigos = res.data.data
                this.loading = false;
            },
            async storeTestigo(){
                try {
                    if(this.formTestigo.mesa_id==""){
                        swal({
                        text:"Debe seleccionar una mesa",
                        icon:"error"
                        });
                        return false;
                    }else if(!this.formTestigo.personal_caracterizacion){
                        swal({
                            text:"Debe buscar al elector",
                            icon:"error"
                        });
                        return false;
                    }else if(!this.formTestigo.telefono_principal){
                        swal({
                            text:"Debe indicar el número de teléfono principal",
                            icon:"error"
                        });
                        return false;
                    }
                    let dataForm=this.formTestigo;
                    this.loading = true;
                    const response = await axios({
                        method: 'POST',
                        responseType: 'json',
                        url: "{{ route('api.testigos.store') }}",
                        data: dataForm
                    });
                    this.loading = false;
                    swal({
                        text:response.data.message,
                        icon: "success"
                    }).then(ans => {
                        // $('.marketModal').modal('hide')
                        // $('.modal-backdrop').remove()

                    })
                    await this.clearFormTestigo();
                    await this.fetch();
                    await this.getTestigos();
                } catch (error) {
                    this.loading = false;
                    let msg=error.response.data.message;
                    if(msg=="The given data was invalid."){
                        msg="Los datos proporcionados no son válidos.";
                    }
                    swalAlert("error",msg, errorsToHtmlList(error.response.data.errors));
                    // swal({
                    //     text:error.response.data.message,
                    //     icon:"error"
                    // });

                }
            },
            editTestigo(entity){
                this.actionTestigo="edit";
                this.entityTestigoId=entity.id;
                this.formTestigo.mesa_id=entity.mesa_id;
                this.formTestigo.personal_caracterizacion=entity.personal_caracterizacion;
                this.formTestigo.telefono_principal=entity.personal_caracterizacion.telefono_principal;
                this.formTestigo.telefono_secundario=entity.personal_caracterizacion.telefono_secundario;
                this.cedula_testigo=entity.personal_caracterizacion.cedula;
                this.cedula_testigo_error="";
            },
            async suspendTestigo(entityId){
                try {
                    this.loading = true;
                    const response = await axios({
                        method: 'DELETE',
                        responseType: 'json',
                        url: "{{ url('api/testigos') }}"+"/"+entityId,
                        data: this.formTestigo
                    });
                    this.loading = false;
                    swal({
                        text:response.data.message,
                        icon: "success"
                    }).then(ans => {

                    })
                    await this.clearFormTestigo();
                    await this.fetch();
                    await this.getTestigos();
                } catch (error) {
                    this.loading = false;
                    let msg="";
                    if(error.response){
                        msg=error.response.data.message;
                        if(msg=="The given data was invalid."){
                        msg="Los datos proporcionados no son válidos.";
                    }
                    swalAlert("error",msg, errorsToHtmlList(error.response.data.errors));
                    }
                }//catch
            },
            async updateTestigo(){
               if(!this.formTestigo.telefono_principal){
                        swal({
                            text:"Debe indicar el número de teléfono principal",
                            icon:"error"
                        });
                        return false;
                }
                let dataForm=this.formTestigo;
                try {
                    this.loading = true;
                    const response = await axios({
                        method: 'PUT',
                        responseType: 'json',
                        url: "{{ url('api/testigos') }}"+"/"+this.entityTestigoId,
                        params: dataForm
                    });
                    this.loading = false;
                    swal({
                        text:response.data.message,
                        icon: "success"
                    }).then(ans => {
                        // $('.marketModal').modal('hide')
                        // $('.modal-backdrop').remove()

                    })
                    await this.clearFormTestigo();
                    await this.fetch();
                    await this.getTestigos();
                } catch (error) {
                    this.loading = false;
                    let msg="";
                    if(error.response.data){
                        msg=error.response.data.message;
                    }
                    if(msg=="The given data was invalid."){
                        msg="Los datos proporcionados no son válidos.";
                    }
                    swalAlert("error",msg, errorsToHtmlList(error.response.data.errors));
                }
            },
            clearFormTestigo(){
                this.formTestigo.mesa_id="";
                this.formTestigo.personal_caracterizacion=null;
                this.formTestigo.telefono_principal="";
                this.formTestigo.telefono_secundario="";
                this.cedula_testigo="",
                this.cedula_testigo_error="",
                this.actionTestigo="create";
            },
            async getMesas(){
                this.loading = true;
                let filters={
                    centro_votacion_id:this.entityId,
                    order_by:"numero_mesa",
                    order_direction:"ASC",
                    last_election:1
                };
                //Agregar validacion de mesas de la ultima eleccion (Agregar filtro en backend)
                let res = await axios.get("{{ route('api.mesas.index') }}",{
                    params:filters
                })
                this.mesas = res.data.data
                this.loading = false;
            },
            async store(){
                try {
                    let dataForm=this.formMesa;
                    dataForm.centro_votacion_id=this.entityId;
                    this.loading = true;
                    const response = await axios({
                        method: 'POST',
                        responseType: 'json',
                        url: "{{ route('api.mesas.store') }}",
                        data: dataForm
                    });
                    this.loading = false;
                    swal({
                        text:response.data.message,
                        icon: "success"
                    }).then(ans => {
                        // $('.marketModal').modal('hide')
                        // $('.modal-backdrop').remove()

                    })
                    await this.clearForm();
                    await this.fetch();
                    await this.getMesas();
                    await this.setNumeroMesa();
                } catch (error) {
                    this.loading = false;
                    let msg=error.response.data.message;
                    if(msg=="The given data was invalid."){
                        msg="Los datos proporcionados no son válidos.";
                    }
                    swalAlert("error",msg, errorsToHtmlList(error.response.data.errors));
                    // swal({
                    //     text:error.response.data.message,
                    //     icon:"error"
                    // });

                }
            },
            edit(entity){
                this.action="edit";
                this.entityMesaId=entity.id;
                this.formMesa.numero_mesa=entity.numero_mesa;
                this.formMesa.observacion=entity.observacion;
                this.formMesa.transmision=entity.transmision;
                this.formMesa.hora_transmision=entity.hora_transmision;
            },
            async suspend(entityId){
                try {
                    this.loading = true;
                    const response = await axios({
                        method: 'DELETE',
                        responseType: 'json',
                        url: "{{ url('api/mesas') }}"+"/"+entityId,
                        data: this.form
                    });
                    this.loading = false;
                    swal({
                        text:response.data.message,
                        icon: "success"
                    }).then(ans => {

                    })
                    await this.clearForm();
                    await this.fetch();
                    await this.getMesas();
                    await this.setNumeroMesa();
                } catch (error) {
                    this.loading = false;
                    let msg="";
                    if(error.response){
                        msg=error.response.data.message;
                        if(msg=="The given data was invalid."){
                        msg="Los datos proporcionados no son válidos.";
                    }
                    swalAlert("error",msg, errorsToHtmlList(error.response.data.errors));
                    }
                }//catch
            },
            async update(){
                let dataForm=this.formMesa;
                dataForm.centro_votacion_id=this.entityId;
                try {
                    this.loading = true;
                    const response = await axios({
                        method: 'PUT',
                        responseType: 'json',
                        url: "{{ url('api/mesas') }}"+"/"+this.entityMesaId,
                        params: dataForm
                    });
                    this.loading = false;
                    swal({
                        text:response.data.message,
                        icon: "success"
                    }).then(ans => {
                        // $('.marketModal').modal('hide')
                        // $('.modal-backdrop').remove()

                    })
                    await this.clearForm();
                    await this.fetch();
                    await this.getMesas();
                    await this.setNumeroMesa();
                } catch (error) {
                    this.loading = false;
                    let msg="";
                    if(error.response.data){
                        msg=error.response.data.message;
                    }
                    if(msg=="The given data was invalid."){
                        msg="Los datos proporcionados no son válidos.";
                    }
                    swalAlert("error",msg, errorsToHtmlList(error.response.data.errors));
                }
            },
            clearForm(){
                this.formMesa.observacion="";
                this.action="create";
            },
            async obtenerMunicipios() {
                try {
                    this.loading = true;
                    let filters = {}
                    const response = await axios({
                        method: 'get',
                        responseType: 'json',
                        url: "{{ url('api/municipios') }}",
                        params: filters
                    });

                    if(response.data.success == false){
                        swal({
                            text:response.data.msg,
                            icon:"error"
                        })

                        return false;
                    }

                    this.loading = false;
                    this.municipios = response.data;
                } catch (err) {
                    this.loading = false;
                    console.log(err)
                }
            },
            async obtenerPartidosPoliticos() {
                try {
                    this.loading = true;
                    let filters = {}
                    const response = await axios({
                        method: 'Get',
                        responseType: 'json',
                        url: "{{ route('api.partidos-politicos.index') }}",
                        params: filters
                    });
                    this.loading = false;
                    this.partidosPoliticos = response.data;
                } catch (err) {
                    this.loading = false;
                    console.log(err)
                }
            },
            async obtenerElector() {
                
                if(this.cedula_testigo==""){
                    swal({
                        text:"Debe ingresar una cédula válida",
                        icon:"error"
                    });
                    return false;
                }
                try {
                    this.loading = true;
                    let filters = {
                        cedula:this.cedula_testigo
                    }
                    const response = await axios({
                        method: 'Get',
                        responseType: 'json',
                        _token:"{{ csrf_token() }}",
                        url: "{{ url('elector/search-by-cedula') }}",
                        params: filters
                    });
                    
                    this.loading = false;
                    
                    if(response.data.success==true){
                        this.formTestigo.personal_caracterizacion=response.data.elector;
                        this.cedula_testigo_error="";
                        if(response.data.elector.tipo_voto){
                            this.formTestigo.tipo_voto=response.data.elector.tipo_voto.toLowerCase();
                        }else{
                            this.formTestigo.tipo_voto="duro";
                        }
                        if(response.data.elector.partido_politico_id){
                            this.formTestigo.partido_politico_id=response.data.elector.partido_politico_id;
                        }else{
                            this.formTestigo.partido_politico_id=4;

                        }
                        if(response.data.elector.movilizacion_id){
                            this.formTestigo.movilizacion_id=response.data.elector.movilizacion_id;
                        }else{
                            this.formTestigo.movilizacion_id=3;
                        }
                        this.formTestigo.telefono_principal=response.data.elector.telefono_principal;
                        this.formTestigo.telefono_secundario=response.data.elector.telefono_secundario;
                    }else{
                        
                        this.formTestigo.personal_caracterizacion=null;
                        this.cedula_testigo_error="Elector no encontrado";
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
                    this.cedula_testigo_error=err.response.data.message;
                }
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

        } //methods
    });
</script>

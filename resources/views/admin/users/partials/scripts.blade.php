<script type="text/javascript">
    /********* VUE ***********/
    var vue_instance = new Vue({
        el: '#content',
        components: {},
        data: {
            loading: true,
            action:"create",//create,edit,suspend
            //Class
            linkClass:"page-link",
            activeLinkClass:"page-link active-link bg-main",

            //Form
            form:{
                role_id:"",
                municipio_id:"",
                name:"",
                last_name:"",
                email:"",
                password:"",
                instituciones:[],
                movimientos:[],
            },
            entityId:null,
            searchText:"",
            //Array data
            roles:[],
            municipios:[],
            movimientos:[],
            instituciones:[],
            results:[],

            //paginate
            modalTitle:"Crear Usuario",
            currentPage:1,
            links:"",
            totalPages:"",
   
        },
        created: function() {
            this.$nextTick(async function() {
                await this.fetch();
                await this.obtenerRoles();
                await this.obtenerMovimientos();
                await this.obtenerMunicipios();
                await this.obtenerInstituciones();
                this.loading = false;
            });
        },
        methods: {
            async fetch(link = ""){
                this.loading = true;
                let filters={
                    includes:[
                        "roles",
                        "instituciones",
                        "movimientos"
                    ],
                    search:this.searchText
                };
                if(link==""){
                    filters.page=1;
                }
                let res = await axios.get(link == "" ? "{{ route('api.users.index') }}" : link.url,{
                    params:filters
                })
                this.results = res.data.data
                this.links = res.data.links
                this.currentPage = res.data.current_page
                this.totalPages = res.data.last_page
                this.loading = false;
            },
            async store(){
                //Validations
                if(this.form.role_id==""){
                    swal({
                        text:"Debe seleccionar un rol",
                        icon:"error"
                    });
                    return false;
                }else if(this.form.name==""){
                    swal({
                        text:"Debe ingresar el nombre",
                        icon:"error"
                    });
                    return false;
                }else if(this.form.last_name==""){
                    swal({
                        text:"Debe ingresar el apellido",
                        icon:"error"
                    });
                    return false;
                }else if(this.form.email==""){
                    swal({
                        text:"Debe ingresar el correo electrónico",
                        icon:"error"
                    });
                    return false;
                }else if(this.form.password==""){
                    swal({
                        text:"Debe ingresar la contraseña",
                        icon:"error"
                    });
                    return false;
                }else if(this.form.password.length<8){
                    swal({
                        text:"La contraseña debe tener mínimo 8 dígitos",
                        icon:"error"
                    });
                    return false;
                }
                let dataForm=this.form;
                dataForm.password_confirmation=this.form.password;
                if(!dataForm.municipio_id){
                    dataForm.municipio_id=null;
                }
                try {
                    this.loading = true;
                    const response = await axios({
                        method: 'POST',
                        responseType: 'json',
                        url: "{{ route('api.users.store') }}",
                        data: dataForm
                    });
                    this.loading = false;
                    swal({
                        text:response.data.message,
                        icon: "success"
                    }).then(ans => {
                        $('.marketModal').modal('hide')
                        $('.modal-backdrop').remove()

                    })
                    this.clearForm();
                    this.fetch();
                } catch (error) {
                    this.loading = false;
                    console.log(error)
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
                this.entityId=entity.id;
                if(entity.roles.length>0){
                    this.form.role_id=entity.roles[0].id;
                }else{
                    this.form.role_id="";
                }
                this.form.instituciones=entity.instituciones.map(function(x){
                    return x.institucion_id;
                });
                this.form.movimientos=entity.movimientos.map(function(x){
                    return x.movimiento_id;
                });
                this.form.name=entity.name;
                this.form.municipio_id=entity.municipio_id;
                if(!entity.municipio_id) {
                    this.form.municipio_id="";
                }
                this.form.last_name=entity.last_name;
                this.form.email=entity.email;
                this.form.password="";
            },
            async suspend(entityId){
                try {
                    this.loading = true;
                    const response = await axios({
                        method: 'DELETE',
                        responseType: 'json',
                        url: "{{ url('api/users') }}"+"/"+entityId,
                        data: this.form
                    });
                    this.loading = false;
                    swal({
                        text:response.data.message,
                        icon: "success"
                    }).then(ans => {
                        $('.marketModal').modal('hide')
                        $('.modal-backdrop').remove()

                    })
                    this.clearForm();
                    this.fetch();
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
            async update(){
                let dataForm={
                    name:this.form.name,
                    last_name:this.form.last_name,
                    email:this.form.email,
                    role_id:this.form.role_id,
                    instituciones:this.form.instituciones,
                    movimientos:this.form.movimientos,
                    municipio_id:this.form.municipio_id,
                }
                if(!this.form.municipio_id){
                    dataForm.municipio_id=null;
                }
                if(!this.form.movimientos){
                    dataForm.movimientos=[];
                }
                if(!this.form.instituciones){
                    dataForm.instituciones=[];
                }
                //Validations
                if(this.form.role_id==""){
                    swal({
                        text:"Debe seleccionar un rol",
                        icon:"error"
                    });
                    return false;
                }else if(this.form.name==""){
                    swal({
                        text:"Debe ingresar el nombre",
                        icon:"error"
                    });
                    return false;
                }else if(this.form.last_name==""){
                    swal({
                        text:"Debe ingresar el apellido",
                        icon:"error"
                    });
                    return false;
                }else if(this.form.email==""){
                    swal({
                        text:"Debe ingresar el correo electrónico",
                        icon:"error"
                    });
                    return false;
                }else if(this.form.password!=""){
                    if(this.form.password.length<8){
                        swal({
                            text:"La contraseña debe tener mínimo 8 dígitos",
                            icon:"error"
                        });
                        return false;
                    }
                    dataForm.password=this.form.password;
                    dataForm.password_confirmation=this.form.password;
                }
              try {
                    this.loading = true;
                    const response = await axios({
                        method: 'PUT',
                        responseType: 'json',
                        url: "{{ url('api/users') }}"+"/"+this.entityId,
                        params: dataForm
                    });
                    this.loading = false;
                    swal({
                        text:response.data.message,
                        icon: "success"
                    }).then(ans => {
                        $('.marketModal').modal('hide')
                        $('.modal-backdrop').remove()

                    })
                    this.clearForm();
                    this.fetch();
                } catch (error) {
                    this.loading = false;
                    let msg=error.response.data.message;
                    if(msg=="The given data was invalid."){
                        msg="Los datos proporcionados no son válidos.";
                    }
                    swalAlert("error",msg, errorsToHtmlList(error.response.data.errors));
                }
            },
            clearForm(){
                this.form.role_id="";
                this.form.municipio_id="";
                this.form.name="";
                this.form.last_name="";
                this.form.email="";
                this.form.password="";
                this.form.instituciones=[];
                this.form.movimientos=[];
                this.action="create";
            },
            async obtenerRoles() {
                try {
                    this.loading = true;
                    let filters = {
                        order_by:"name",
                        order_direction:"ASC"
                    }
                    const response = await axios({
                        method: 'get',
                        responseType: 'json',
                        url: "{{ route('api.roles.index') }}",
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
                    this.roles = response.data.data;
                } catch (err) {
                    this.loading = false;
                    console.log(err)
                }
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
            async obtenerInstituciones() {
                try {
                    this.loading = true;
                    let filters = {}
                    const response = await axios({
                        method: 'get',
                        responseType: 'json',
                        url: "{{ route('api.institucion.index') }}",
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
                    this.instituciones = response.data;
                } catch (err) {
                    this.loading = false;
                    console.log(err)
                }
            },
            async obtenerMovimientos() {
                try {
                    this.loading = true;
                    let filters = {}
                    const response = await axios({
                        method: 'get',
                        responseType: 'json',
                        url: "{{ route('api.movimiento.index') }}",
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
                    this.movimientos = response.data;
                } catch (err) {
                    this.loading = false;
                    console.log(err)
                }
            },

        } //methods
    });
</script>

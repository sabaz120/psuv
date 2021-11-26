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
                comunidad_id:"0",
                tipo:"---",
                sector:"---",
                nombre:"",
            },
            entityId:null,
            searchText:"",
            //Array data
            comunidades:[],
            results:[],


            //paginate
            modalTitle:"Crear Calle",
            currentPage:1,
            links:"",
            totalPages:"",
   
        },
        created: function() {
            this.$nextTick(async function() {
                await this.fetch();
                await this.obtenerComunidades();
                this.loading = false;
            });
        },
        methods: {
            async fetch(link = ""){
                this.loading = true;
                let filters={
                    municipio_id:"{{Auth::user()->municipio_id ? Auth::user()->municipio_id : 0}}",
                    includes:"comunidad",
                    search:this.searchText
                };
                if(link==""){
                    filters.page=1;
                }
                let res = await axios.get(link == "" ? "{{ route('api.calles.index') }}" : link.url,{
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
                if(this.form.comunidad_id=="0"){
                    swal({
                        text:"Debe seleccionar una comunidad",
                        icon:"error"
                    });
                    return false;
                }else if(this.form.nombre==""){
                    swal({
                        text:"Debe ingresar el nombre",
                        icon:"error"
                    });
                    return false;
                }else if(this.form.tipo==""){
                    swal({
                        text:"Debe ingresar el tipo",
                        icon:"error"
                    });
                    return false;
                }else if(this.form.sector==""){
                    swal({
                        text:"Debe ingresar el sector",
                        icon:"error"
                    });
                    return false;
                }
                try {
                    this.loading = true;
                    const response = await axios({
                        method: 'POST',
                        responseType: 'json',
                        url: "{{ route('api.calles.store') }}",
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
                this.form.comunidad_id=entity.comunidad.id;
                this.form.nombre=entity.nombre;
                this.form.tipo=entity.tipo;
                this.form.sector=entity.sector;
            },
            async suspend(entityId){
                try {
                    this.loading = true;
                    const response = await axios({
                        method: 'DELETE',
                        responseType: 'json',
                        url: "{{ url('api/calles') }}"+"/"+entityId,
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
              //Validations
                //Validations
                if(this.form.comunidad_id=="0"){
                    swal({
                        text:"Debe seleccionar una comunidad",
                        icon:"error"
                    });
                    return false;
                }else if(this.form.nombre==""){
                    swal({
                        text:"Debe ingresar el nombre",
                        icon:"error"
                    });
                    return false;
                }else if(this.form.tipo==""){
                    swal({
                        text:"Debe ingresar el tipo",
                        icon:"error"
                    });
                    return false;
                }else if(this.form.sector==""){
                    swal({
                        text:"Debe ingresar el sector",
                        icon:"error"
                    });
                    return false;
                }
              try {
                    this.loading = true;
                    const response = await axios({
                        method: 'PUT',
                        responseType: 'json',
                        url: "{{ url('api/calles') }}"+"/"+this.entityId,
                        params: this.form
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
                this.form.comunidad_id="0";
                this.form.nombre="";
                this.form.sector="---";
                this.form.tipo="---";
                this.action="create";
            },
            async obtenerComunidades() {
                try {
                    this.loading = true;
                    let filters = {
                        municipio_id:"{{Auth::user()->municipio_id ? Auth::user()->municipio_id : 0}}",
                        includes:"comunidad",
                        order_by:"nombre",
                        order_direction:"ASC"
                     }
                    const response = await axios({
                        method: 'get',
                        responseType: 'json',
                        url: "{{ route('api.comunidades.index') }}",
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
                    this.comunidades = response.data.data;
                } catch (err) {
                    this.loading = false;
                    console.log(err)
                }
            },

        } //methods
    });
</script>

<!-- Image upload -->
<script src="https://unpkg.com/vue-image-upload-resize"></script>
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
                nombre:"",
                municipio_id:"",
                apellido:"",
                foto:"",
                cargo_eleccion:"",
                partidos_politicos:[]
            },
            entityId:null,
            searchText:"",
            //Array data
            cargos:[
                "Gobernador",
                "Alcalde",
                // "Concejal",
            ],
            municipios:[],
            partidosPoliticos:[],
            results:[],
            preview_image:"",
            hasImage :false,

            //paginate
            modalTitle:"Crear personal",
            currentPage:1,
            links:"",
            totalPages:"",
   
        },
        created: function() {
            this.$nextTick(async function() {
                await this.fetch();
                await this.obtenerMunicipios();
                await this.obtenerPartidosPoliticos();
                this.loading = false;
            });
        },
        methods: {
            async setImage(file) {
                this.form.foto = file;
                this.preview_image = file;
                this.hasImage = true;
                if(this.action=="edit"){
                    try {
                    this.loading = true;
                    const response = await axios({
                        method: 'POST',
                        responseType: 'json',
                        url: "{{ url('api/candidato-actualizar-imagen') }}"+"/"+this.entityId,
                        data: {
                            foto:file
                        }
                    });
                    this.loading = false;
                    swal({
                        text:response.data.message,
                        icon: "success"
                    }).then(ans => {
                    })
                    this.fetch();
                    } catch (error) {
                    this.loading = false;
                    let msg=error.response.data.message;
                    if(msg=="The given data was invalid."){
                        msg="Los datos proporcionados no son válidos.";
                    }
                    swalAlert("error",msg, errorsToHtmlList(error.response.data.errors));
                    }
                }//if action edit
            },
            startImageResize: function (file) {
                console.log('file');
                console.log(file);
            },
            endImageResize: function (file) {
                console.log('file');
                console.log(file);
            },
            async fetch(link = ""){
                this.loading = true;
                let filters={
                    includes:[
                        "partidosPoliticos",
                    ],
                    search:this.searchText
                };
                if(link==""){
                    filters.page=1;
                }
                let res = await axios.get(link == "" ? "{{ route('api.candidatos.index') }}" : link.url,{
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
                if(this.form.nombre==""){
                    swal({
                        text:"Debe ingresar el nombre",
                        icon:"error"
                    });
                    return false;
                }else if(this.form.apellido==""){
                    swal({
                        text:"Debe ingresar el apellido",
                        icon:"error"
                    });
                    return false;
                }else if(this.form.foto==""){
                    swal({
                        text:"Debe seleccionar una foto",
                        icon:"error"
                    });
                    return false;
                }else if(this.form.cargo_eleccion==""){
                    swal({
                        text:"Debe seleccionar un cargo",
                        icon:"error"
                    });
                    return false;
                }else if(this.form.municipio_id=="" && this.form.cargo_eleccion!="Gobernador"){
                    swal({
                        text:"Debe seleccionar un municipio",
                        icon:"error"
                    });
                    return false;
                }else if(this.form.partidos_politicos.length==0){
                    swal({
                        text:"Debe seleccionar al menos un partido político",
                        icon:"error"
                    });
                    return false;
                }
                try {
                    this.loading = true;
                    const response = await axios({
                        method: 'POST',
                        responseType: 'json',
                        url: "{{ route('api.candidatos.store') }}",
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
            edit(entity){
                this.action="edit";
                this.entityId=entity.id;
                this.form.partidos_politicos=entity.partidos_politicos.map(function(x){
                    return x.id;
                });
                this.form.nombre=entity.nombre;
                this.form.apellido=entity.apellido;
                this.form.municipio_id=entity.municipio_id;
                if(!entity.municipio_id) {
                    this.form.municipio_id="";
                }
                this.form.foto=entity.foto;
                this.preview_image=entity.foto;
                this.hasImage=true;
                this.form.cargo_eleccion=entity.cargo_eleccion;
            },
            async suspend(entityId){
                try {
                    this.loading = true;
                    const response = await axios({
                        method: 'DELETE',
                        responseType: 'json',
                        url: "{{ url('api/candidatos') }}"+"/"+entityId,
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
                if(this.form.nombre==""){
                    swal({
                        text:"Debe ingresar el nombre",
                        icon:"error"
                    });
                    return false;
                }else if(this.form.apellido==""){
                    swal({
                        text:"Debe ingresar el apellido",
                        icon:"error"
                    });
                    return false;
                }else if(this.form.foto==""){
                    swal({
                        text:"Debe seleccionar una foto",
                        icon:"error"
                    });
                    return false;
                }else if(this.form.cargo_eleccion==""){
                    swal({
                        text:"Debe seleccionar un cargo",
                        icon:"error"
                    });
                    return false;
                }else if(this.form.municipio_id=="" && this.form.cargo_eleccion!="Gobernador"){
                    swal({
                        text:"Debe seleccionar un municipio",
                        icon:"error"
                    });
                    return false;
                }else if(this.form.partidos_politicos.length==0){
                    swal({
                        text:"Debe seleccionar al menos un partido político",
                        icon:"error"
                    });
                    return false;
                }

                let dataForm={
                    nombre:this.form.nombre,
                    apellido:this.form.apellido,
                    cargo_eleccion:this.form.cargo_eleccion,
                    municipio_id:this.form.municipio_id,
                    partidos_politicos:this.form.partidos_politicos,
                }
              try {
                    this.loading = true;
                    const response = await axios({
                        method: 'PUT',
                        responseType: 'json',
                        url: "{{ url('api/candidatos') }}"+"/"+this.entityId,
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
                this.form.nombre="";
                this.form.apellido="";
                this.form.foto="";
                this.form.hasImage=false;
                this.form.cargo_eleccion="";
                this.form.municipio_id="";
                this.form.partidos_politicos=[];
                this.action="create";
                $(".fileinput").val('');
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
            generateExcel(){
                let params={};
                this.loading=true;
                axios({
                    url: `api/report/candidatos`,
                    method: 'GET',
                    params:params,
                    responseType: 'blob' // important
                }).then((response) => {
                    const url = window.URL.createObjectURL(new Blob([response.data]))
                    const link = document.createElement('a')
                    link.href = url
                    link.setAttribute('download', 'reporte-candidatos.xlsx')
                    document.body.appendChild(link)
                    link.click()
                    this.loading=false;
                }).catch((err) => {
                    this.loading = false;
                    swal({
                        text:"Ha ocurrido un error al intentar generar el excel",
                        icon:"error"
                    });
                })
            },//generate()
        } //methods
    });
</script>

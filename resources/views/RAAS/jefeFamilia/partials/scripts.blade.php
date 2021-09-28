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
                jefe_calle_id:null,
                personal_caracterizacion:null,
                tipo_voto:"",
                telefono_principal:"",
                telefono_secundario:"",
                partido_politico_id:"",
                movilizacion_id:"",
            },
            entityId:null,
            //search
            cedula_jefe_calle:"",
            cedula_jefe_calle_error:"",
            jefe_calle:null,
            cedula_jefe:"",
            cedula_jefe_error:"",
            //Array data
            tipoDeVotos:[
                "Duro",
                "Blando"
            ],
            partidosPoliticos:[],
            tiposDeMovilizacion:[],
            results:[],


            //paginate
            modalTitle:"Crear Jefe de Familia",
            currentPage:1,
            links:"",
            totalPages:"",
   
        },
        created: function() {
            this.$nextTick(async function() {
                this.loading = false;
                await this.fetch();
                await this.obtenerPartidosPoliticos();
                await this.obtenerTiposMovilizacion();
            });
        },
        methods: {
            async fetch(link = ""){
                let res = await axios.get(link == "" ? "{{ route('api.jefe-familia.index') }}" : link.url)
                this.results = res.data.data
                this.links = res.data.links
                this.currentPage = res.data.current_page
                this.totalPages = res.data.last_page
            },
            async store(){
                //Validations
                if(this.form.jefe_calle_id==null){
                    swal({
                        text:"Debe indicar el jefe de calle",
                        icon:"error"
                    });
                    return false;
                }else if(this.form.personal_caracterizacion==null){
                    swal({
                        text:"Debe indicar el jefe de familia",
                        icon:"error"
                    });
                    return false;
                }else if(this.form.tipo_voto==""){
                    swal({
                        text:"Debe seleccionar un tipo de voto",
                        icon:"error"
                    });
                    return false;
                }else if(this.form.telefono_principal==""){
                    swal({
                        text:"Debe ingresar un teléfono principal",
                        icon:"error"
                    });
                    return false;
                }else if(this.form.telefono_secundario==""){
                    swal({
                        text:"Debe ingresar un teléfono secundario",
                        icon:"error"
                    });
                    return false;
                }else if(this.form.partido_politico_id==""){
                    swal({
                        text:"Debe seleccionar un partido político",
                        icon:"error"
                    });
                    return false;
                }else if(this.form.movilizacion_id==""){
                    swal({
                        text:"Debe seleccionar un tipo de movilización",
                        icon:"error"
                    });
                    return false;
                }
                try {
                    this.loading = true;
                    const response = await axios({
                        method: 'POST',
                        responseType: 'json',
                        url: "{{ route('api.jefe-familia.store') }}",
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
                } catch (err) {
                    this.loading = false;
                    console.log(err)
                    swal({
                        text:err.response.data.message,
                        icon:"error"
                    });
                }
            },
            edit(entity){
                this.action="edit";
                this.entityId=entity.id;
                //Jefe comunidad
                this.cedula_jefe_calle=entity.jefe_calle.personal_caracterizacion.cedula;
                this.jefe_calle=entity.jefe_calle;
                this.form.jefe_calle_id=entity.jefe_calle.id;
                //Jefe calle
                this.cedula_jefe=entity.personal_caracterizacion.cedula;
                this.form.personal_caracterizacion=entity.personal_caracterizacion;
                this.form.tipo_voto=entity.personal_caracterizacion.tipo_voto;
                this.form.telefono_principal=entity.personal_caracterizacion.telefono_principal;
                this.form.telefono_secundario=entity.personal_caracterizacion.telefono_secundario;
                this.form.partido_politico_id=entity.personal_caracterizacion.partido_politico_id;
                this.form.movilizacion_id=entity.personal_caracterizacion.movilizacion_id;
            },
            async suspend(){

            },
            async update(){
              //Validations
                if(this.form.jefe_calle_id==null){
                    swal({
                        text:"Debe indicar el jefe de calle",
                        icon:"error"
                    });
                    return false;
                }else if(this.form.personal_caracterizacion==null){
                    swal({
                        text:"Debe indicar el jefe de familia",
                        icon:"error"
                    });
                    return false;
                }else if(this.form.tipo_voto==""){
                    swal({
                        text:"Debe seleccionar un tipo de voto",
                        icon:"error"
                    });
                    return false;
                }else if(this.form.telefono_principal==""){
                    swal({
                        text:"Debe ingresar un teléfono principal",
                        icon:"error"
                    });
                    return false;
                }else if(this.form.telefono_secundario==""){
                    swal({
                        text:"Debe ingresar un teléfono secundario",
                        icon:"error"
                    });
                    return false;
                }else if(this.form.partido_politico_id==""){
                    swal({
                        text:"Debe seleccionar un partido político",
                        icon:"error"
                    });
                    return false;
                }else if(this.form.movilizacion_id==""){
                    swal({
                        text:"Debe seleccionar un tipo de movilización",
                        icon:"error"
                    });
                    return false;
                }
              try {
                    this.loading = true;
                    const response = await axios({
                        method: 'PUT',
                        responseType: 'json',
                        url: "{{ url('api/raas/jefe-familia') }}"+"/"+this.entityId,
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
                } catch (err) {
                    this.loading = false;
                    console.log(err)
                }
            },
            clearForm(){
                this.form.jefe_calle_id=null;
                this.form.personal_caracterizacion=null;
                this.form.tipo_voto="";
                this.form.telefono_principal="";
                this.form.telefono_secundario="";
                this.form.partido_politico_id="";
                this.form.movilizacion_id="";
                this.cedula_jefe="";
                this.cedula_jefe_error="";
                this.cedula_jefe_calle="";
                this.cedula_jefe_calle_error="";
                this.entityId=null;
                this.jefe_calle=null;
                this.action="create";
            },
            async obtenerJefeCalle() {
                if(this.cedula_jefe_calle==""){
                    swal({
                        text:"Debe ingresar una cédula válida",
                        icon:"error"
                    });
                    return false;
                }
                try {
                    this.loading = true;
                    let filters = {}
                    const response = await axios({
                        method: 'Get',
                        responseType: 'json',
                        url: "{{ url('api/raas/jefe-calle') }}"+"/"+this.cedula_jefe_calle,
                        params: filters
                    });
                    this.loading = false;
                    this.jefe_calle = response.data.data;
                    this.form.jefe_calle_id = this.jefe_calle.id;
                } catch (err) {
                    this.loading = false;
                    console.log(err)
                }
            },
            async obtenerJefe() {
                if(this.cedula_jefe==""){
                    swal({
                        text:"Debe ingresar una cédula válida",
                        icon:"error"
                    });
                    return false;
                }
                try {
                    this.loading = true;
                    let filters = {
                        cedula:this.cedula_jefe
                    }
                    const response = await axios({
                        method: 'POST',
                        responseType: 'json',
                        url: "{{ url('api/raas/jefe-comunidad/search-by-cedula') }}",
                        data: filters
                    });
                    this.loading = false;
                    if(response.data.success==true){
                        this.form.personal_caracterizacion=response.data.elector;
                        this.cedula_jefe_error="";
                    }else{
                        this.form.personal_caracterization=null;
                        this.cedula_jefe_error="Elector no encontrado";
                    }
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
            async obtenerTiposMovilizacion() {
                try {
                    this.loading = true;
                    let filters = {}
                    const response = await axios({
                        method: 'Get',
                        responseType: 'json',
                        url: "{{ route('api.movilizacion.index') }}",
                        params: filters
                    });
                    this.loading = false;
                    this.tiposDeMovilizacion = response.data;
                } catch (err) {
                    this.loading = false;
                    console.log(err)
                }
            },

        } //methods
    });
</script>

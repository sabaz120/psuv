<script type="text/javascript">
    /********* VUE ***********/
    var vue_instance = new Vue({
        el: '#content',
        components: {},
        data: {
            loading: true,
            action:"create",//create,edit,suspend
            actionFamily:"create",//create,edit,suspend
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
            familyForm:{
                personal_caracterizacion:null,
                tipo_voto:"",
                telefono_principal:"",
                telefono_secundario:"",
                partido_politico_id:"",
                movilizacion_id:"",
            },
            entityId:null,
            entity:null,
            entityFamily:null,
            //search
            cedula_jefe_calle:"",
            cedula_jefe_calle_error:"",
            jefe_calle:null,
            cedula_jefe:"",
            cedula_jefe_error:"",
            cedula_familiar:"",
            cedula_familiar_error:"",
            //Array data
            tipoDeVotos:[
                "Duro",
                "Blando"
            ],
            partidosPoliticos:[],
            tiposDeMovilizacion:[],
            results:[],
            families:[],

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
            clearFormFamily(){
                this.cedula_familiar="";
                this.cedula_familiar_error="";
                this.actionFamily="create";
                this.entityFamily=null;
                this.familyForm.personal_caracterizacion=null;
                this.familyForm.tipo_voto="";
                this.familyForm.telefono_principal="";
                this.familyForm.telefono_secundario="";
                this.familyForm.partido_politico_id="";
                this.familyForm.movilizacion_id="";
            },
            obtenerFamilia(entity){
                this.entity=entity;
                this.indexFamily();

            },
            async indexFamily(){
                try {
                    this.loading = true;
                    let data={
                        jefe_familia_id:this.entity.id
                    };
                    const response = await axios({
                        method: 'GET',
                        responseType: 'json',
                        url: "{{ route('api.nucleo-familiar.index') }}",
                        params: data
                    });
                    this.loading = false;
                    this.families=response.data.data;
                } catch (err) {
                    this.loading = false;
                    console.log(err)
                    swal({
                        text:err.response.data.message,
                        icon:"error"
                    });
                }
            },
            async storeFamily(){
                //Validations
                if(this.familyForm.personal_caracterizacion==null){
                    swal({
                        text:"Debe indicar el familiar",
                        icon:"error"
                    });
                    return false;
                }else if(this.familyForm.tipo_voto==""){
                    swal({
                        text:"Debe seleccionar un tipo de voto",
                        icon:"error"
                    });
                    return false;
                }else if(this.familyForm.telefono_principal==""){
                    swal({
                        text:"Debe ingresar un teléfono principal",
                        icon:"error"
                    });
                    return false;
                }else if(this.familyForm.telefono_secundario==""){
                    swal({
                        text:"Debe ingresar un teléfono secundario",
                        icon:"error"
                    });
                    return false;
                }else if(this.familyForm.partido_politico_id==""){
                    swal({
                        text:"Debe seleccionar un partido político",
                        icon:"error"
                    });
                    return false;
                }else if(this.familyForm.movilizacion_id==""){
                    swal({
                        text:"Debe seleccionar un tipo de movilización",
                        icon:"error"
                    });
                    return false;
                }
                try {
                    this.loading = true;
                    let data=this.familyForm;
                    data.jefe_familia_id=this.entity.id;
                    const response = await axios({
                        method: 'POST',
                        responseType: 'json',
                        url: "{{ route('api.nucleo-familiar.store') }}",
                        data: data
                    });
                    this.loading = false;
                    swal({
                        text:response.data.message,
                        icon: "success"
                    }).then(ans => {
                        
                    })
                    this.clearFormFamily();
                    this.indexFamily();
                } catch (err) {
                    this.loading = false;
                    console.log(err)
                    swal({
                        text:err.response.data.message,
                        icon:"error"
                    });
                }
            },
            editFamily(entity){
                this.entityFamily=entity;
                this.cedula_familiar=entity.cedula;
                this.cedula_familiar_error="";
                this.actionFamily="edit";
                this.familyForm.personal_caracterizacion=entity;
                this.familyForm.tipo_voto=entity.tipo_voto;
                this.familyForm.telefono_principal=entity.telefono_principal;
                this.familyForm.telefono_secundario=entity.telefono_secundario;
                this.familyForm.partido_politico_id=entity.partido_politico_id;
                this.familyForm.movilizacion_id=entity.movilizacion_id;

            },
            async updateFamily(){
                //Validations
                if(this.familyForm.tipo_voto==""){
                    swal({
                        text:"Debe seleccionar un tipo de voto",
                        icon:"error"
                    });
                    return false;
                }else if(this.familyForm.telefono_principal==""){
                    swal({
                        text:"Debe ingresar un teléfono principal",
                        icon:"error"
                    });
                    return false;
                }else if(this.familyForm.telefono_secundario==""){
                    swal({
                        text:"Debe ingresar un teléfono secundario",
                        icon:"error"
                    });
                    return false;
                }else if(this.familyForm.partido_politico_id==""){
                    swal({
                        text:"Debe seleccionar un partido político",
                        icon:"error"
                    });
                    return false;
                }else if(this.familyForm.movilizacion_id==""){
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
                        url: "{{ url('api/raas/nucleo-familiar') }}"+"/"+this.entityFamily.id,
                        params: this.familyForm
                    });
                    swal({
                        text:response.data.message,
                        icon: "success"
                    }).then(ans => {

                    })
                    await this.clearFormFamily();
                    await this.indexFamily();
                    this.loading = false;
                } catch (err) {
                    this.loading = false;
                    console.log(err)
                    swal({
                        text:err.response.data.message,
                        icon:"error"
                    });
                }
            },
            async suspendFamily(entityFamily){
                try {
                    this.loading = true;
                    const response = await axios({
                        method: 'DELETE',
                        responseType: 'json',
                        url: "{{ url('api/raas/nucleo-familiar') }}"+"/"+entityFamily.id,
                        data: this.form
                    });
                    swal({
                        text:response.data.message,
                        icon: "success"
                    }).then(ans => {

                    })
                    await this.clearFormFamily();
                    await this.indexFamily();
                    this.loading = false;
                } catch (err) {
                    this.loading = false;
                    swal({
                        text:err.response.data.message,
                        icon:"error"
                    });
                }
            },
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
            async suspend(entityId){
                try {
                    this.loading = true;
                    const response = await axios({
                        method: 'DELETE',
                        responseType: 'json',
                        url: "{{ url('api/raas/jefe-familia') }}"+"/"+entityId,
                        data: {}
                    });
                    this.loading = false;
                    swal({
                        text:response.data.message,
                        icon: "success"
                    }).then(ans => {
                    })
                    this.clearForm();
                    this.fetch();
                } catch (err) {
                    this.loading = false;
                    swal({
                        text:err.response.data.message,
                        icon:"error"
                    });
                }
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
                    this.cedula_jefe_calle_error=err.response.data.message;
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
                        method: 'GET',
                        responseType: 'json',
                        url: "{{ url('api/elector/search-by-cedula') }}",
                        params: filters
                    });
                    this.loading = false;
                    if(response.data.success==true){
                        this.form.personal_caracterizacion=response.data.elector;
                        this.cedula_jefe_error="";
                    }else{
                        this.form.personal_caracterizacion=null;
                        this.cedula_jefe_error="Elector no encontrado";
                    }
                } catch (err) {
                    this.loading = false;
                    console.log(err)
                }
            },
            async obtenerFamiliar() {
                if(this.cedula_familiar==""){
                    swal({
                        text:"Debe ingresar una cédula válida",
                        icon:"error"
                    });
                    return false;
                }
                try {
                    this.loading = true;
                    let filters = {
                        cedula:this.cedula_familiar
                    }
                    const response = await axios({
                        method: 'GET',
                        responseType: 'json',
                        url: "{{ url('api/elector/search-by-cedula') }}",
                        params: filters
                    });
                    this.loading = false;
                    if(response.data.success==true){
                        this.familyForm.personal_caracterizacion=response.data.elector;
                        this.cedula_familiar_error="";
                    }else{
                        this.familyForm.personal_caracterization=null;
                        this.cedula_familiar_error="Elector no encontrado";
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

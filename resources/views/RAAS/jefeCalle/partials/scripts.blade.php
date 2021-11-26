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
                jefe_comunidad_id:null,
                comunidad_id:"0",
                calle_id:"0",
                personal_caraterizacion:null,
                tipo_voto:"",
                telefono_principal:"",
                telefono_secundario:"",
                partido_politico_id:"",
                movilizacion_id:"",
            },
            entityId:null,
            //search
            cedula_jefe_comunidad:"",
            cedula_jefe_comunidad_error:"",
            jefe_comunidad:null,
            cedula_jefe:"",
            cedula_jefe_error:"",
            searchText:"",
            //Array data
            comunidades:[],
            calles:[],
            tipoDeVotos:[
                "Duro",
                "Blando",
                "Opositor"
            ],
            partidosPoliticos:[],
            tiposDeMovilizacion:[],
            results:[],


            //paginate
            modalTitle:"Crear Jefe de Calle",
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
                let filters={
                    params:{
                        search:this.searchText
                    }
                };
                let res = await axios.get(link == "" ? "{{ route('api.jefe-calle.index') }}" : link.url,filters)
                this.results = res.data.data
                this.links = res.data.links
                this.currentPage = res.data.current_page
                this.totalPages = res.data.last_page
            },
            async store(){
                //Validations
                if(this.form.jefe_comunidad_id==null){
                    swal({
                        text:"Debe indicar el jefe de comunidad",
                        icon:"error"
                    });
                    return false;
                }else if(this.form.personal_caraterizacion==null){
                    swal({
                        text:"Debe indicar el jefe de calle",
                        icon:"error"
                    });
                    return false;
                }else if(this.form.calle_id=="0"){
                    swal({
                        text:"Debe seleccionar una calle",
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
                        url: "{{ route('api.jefe-calle.store') }}",
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
            async edit(entity){
                this.action="edit";
                this.entityId=entity.id;
                //Jefe comunidad
                this.cedula_jefe_comunidad=entity.jefe_comunidad.personal_caracterizacion.cedula;
                this.jefe_comunidad=entity.jefe_comunidad;
                this.form.jefe_comunidad_id=entity.jefe_comunidad.id;
                //Jefe calle
                this.cedula_jefe=entity.personal_caracterizacion.cedula;
                this.form.personal_caraterizacion=entity.personal_caracterizacion;
                this.form.tipo_voto=entity.personal_caracterizacion.tipo_voto.toLowerCase();
                this.form.telefono_principal=entity.personal_caracterizacion.telefono_principal;
                this.form.telefono_secundario=entity.personal_caracterizacion.telefono_secundario;
                this.form.partido_politico_id=entity.personal_caracterizacion.partido_politico_id;
                this.form.movilizacion_id=entity.personal_caracterizacion.movilizacion_id;
                //obtener calles
                this.comunidades=entity.jefe_comunidad.comunidades;
                this.form.comunidad_id=entity.jefe_comunidad.comunidad_id;
                await this.obtenerCalles();
                //Calle
                this.form.calle_id=entity.calle_id;
            },
            async suspend(entityId){
                try {
                    this.loading = true;
                    const response = await axios({
                        method: 'DELETE',
                        responseType: 'json',
                        url: "{{ url('api/raas/jefe-calle') }}"+"/"+entityId,
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
                    swal({
                        text:err.response.data.message,
                        icon:"error"
                    });
                }
            },
            async update(){
              //Validations
                if(this.form.jefe_comunidad_id==null){
                    swal({
                        text:"Debe indicar el jefe de comunidad",
                        icon:"error"
                    });
                    return false;
                }else if(this.form.personal_caraterizacion==null){
                    swal({
                        text:"Debe indicar el jefe de calle",
                        icon:"error"
                    });
                    return false;
                }else if(this.form.calle_id=="0"){
                    swal({
                        text:"Debe seleccionar una calle",
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
                        url: "{{ url('api/raas/jefe-calle') }}"+"/"+this.entityId,
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
                    swal({
                        text:err.response.data.message,
                        icon:"error"
                    });
                }
            },
            clearForm(){
                this.form.jefe_comunidad_id=null;
                this.form.comunidad_id="0";
                this.form.calle_id="0";
                this.form.personal_caraterizacion=null;
                this.form.tipo_voto="";
                this.form.telefono_principal="";
                this.form.telefono_secundario="";
                this.form.partido_politico_id="";
                this.form.movilizacion_id="";
                this.cedula_jefe="";
                this.cedula_jefe_comunidad="";
                this.cedula_jefe_comunidad_error="";
                this.cedula_jefe_error="";
                this.entityId=null;
                this.jefe_comunidad=null;
                this.calles=[];
                this.action="create";
            },
            async obtenerJefeComunidad() {
                if(this.cedula_jefe_comunidad==""){
                    swal({
                        text:"Debe ingresar una cédula válida",
                        icon:"error"
                    });
                    return false;
                }
                try {
                    this.loading = true;
                    let filters = {
                        cedula:this.cedula_jefe_comunidad
                    }
                    const response = await axios({
                        method: 'Post',
                        responseType: 'json',
                        url: "{{ url('/raas/jefe-comunidad/search-by-cedula-field') }}",
                        params: filters
                    });

                    if(response.data.success == false){
                        swal({
                            text:response.data.msg,
                            icon:"error"
                        })

                        return
                    }

                    this.loading = false;
                    this.jefe_comunidad = response.data.data;
                    this.comunidades = response.data.data.comunidades;
                    this.form.comunidad_id = response.data.data.comunidad_id;
                    this.form.jefe_comunidad_id = this.jefe_comunidad.id;
                    this.obtenerCalles();
                    this.cedula_jefe_comunidad_error="";
                } catch (err) {
                    this.loading = false;
                    this.cedula_jefe_comunidad_error=err.response.data.message;
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
                        method: 'GET',
                        responseType: 'json',
                        url: "{{ url('elector/search-by-cedula') }}",
                        params: filters
                    });
                    this.loading = false;
                    if(response.data.success==true){
                        this.form.personal_caraterizacion=response.data.elector;
                        this.cedula_jefe_error="";
                        if(response.data.elector.tipo_voto){
                            this.form.tipo_voto=response.data.elector.tipo_voto.toLowerCase();
                        }
                        if(response.data.elector.partido_politico_id){
                            this.form.partido_politico_id=response.data.elector.partido_politico_id;
                        }
                        if(response.data.elector.movilizacion_id){
                            this.form.movilizacion_id=response.data.elector.movilizacion_id;
                        }
                    }else{

                        
                        this.form.personal_caraterizacion=null;
                        this.cedula_jefe_error="Elector no encontrado";
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
            async obtenerCalles() {
                try {
                    if(this.form.comunidad_id=="0"){
                        swal({
                            text:"Debe seleccionar una comunidad",
                            icon:"error"
                        });
                        this.calles=[];
                    }
                    this.loading = true;
                    let filters = {
                        comunidad_id:this.form.comunidad_id,
                        order_by:"nombre",
                        order_direction:"ASC"
                    }
                    for(let i=0;i<this.comunidades.length;i++){
                        console.log(this.comunidades[i]);
                        if(this.comunidades[i].comunidad.id==this.form.comunidad_id){
                            this.form.jefe_comunidad_id=this.comunidades[i].id;
                            break;
                        }
                    }//for
                    const response = await axios({
                        method: 'Get',
                        responseType: 'json',
                        url: "{{ route('api.calles.index') }}",
                        params: filters
                    });
                    this.loading = false;
                    this.form.calle_id="0";
                    this.calles = response.data;
                    if(this.calles.length==0){
                        swal({
                            text:"La comunidad de este jefe de calle, no posee calles.",
                            icon:"error"
                        });
                        this.calles=[];
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

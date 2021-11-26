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
                personal_caracterizacion:null,
                tipo_voto:"duro",
                telefono_principal:"",
                telefono_secundario:"",
                partido_politico_id:"",
                movilizacion_id:3,
                movimiento_id:"",
                nivel_estructura_id:"",
                cargo_id:"",
                direccion:"",
                area_atencion:"Municipal"
            },
            familyForm:{
                personal_caracterizacion:null,
                tipo_voto:"duro",
                telefono_principal:"",
                telefono_secundario:"",
                partido_politico_id:"",
                movilizacion_id:3,
            },
            entityId:null,
            entity:null,
            entityFamily:null,
            //search
            cedula_trabajador:"",
            cedula_trabajador_error:"",
            jefe_calle:null,
            cedula_jefe:"",
            cedula_jefe_error:"",
            cedula_familiar:"",
            cedula_familiar_error:"",
            searchText:"",
            //Array data
            tipoDeVotos:[
                "Duro",
                "Blando",
                "Opositor"
            ],
            partidosPoliticos:[],
            tiposDeMovilizacion:[],
            results:[],
            families:[],
            movimientos:[],
            cargos:[],
            areasAtencion:[
                "Municipal",
                "Parroquial",
            ],
            nivelesEstructura:[],

            //paginate
            modalTitle:"Crear Personal",
            currentPage:1,
            links:"",
            totalPages:"",
   
        },
        created: function() {
            this.$nextTick(async function() {
                this.loading = false;
                await this.fetch();
                await this.obtenerMovimientos();
                await this.obtenerNivelesEstructura();
                await this.obtenerCargos();
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
                this.familyForm.tipo_voto="duro";
                this.familyForm.telefono_principal="";
                this.familyForm.telefono_secundario="";
                this.familyForm.partido_politico_id="";
                this.familyForm.movilizacion_id=3;
            },
            obtenerFamilia(entity){
                this.entity=entity;
                this.indexFamily();

            },
            async indexFamily(){
                try {
                    this.loading = true;
                    let data={
                        participacion_movimiento_id:this.entity.id
                    };
                    const response = await axios({
                        method: 'GET',
                        responseType: 'json',
                        url: "{{ route('api.participacion.movimiento.family.index') }}",
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
                    data.participacion_movimiento_id=this.entity.id;
                    const response = await axios({
                        method: 'POST',
                        responseType: 'json',
                        url: "{{ route('api.participacion.movimiento.family.store') }}",
                        data: data
                    });
                    this.loading = false;
                    swal({
                        text:response.data.message,
                        icon: "success"
                    }).then(ans => {
                        
                    })
                    await this.clearFormFamily();
                    await this.indexFamily();
                    await this.fetch();
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
                this.cedula_familiar=entity.personal_caracterizacion.cedula;
                this.cedula_familiar_error="";
                this.actionFamily="edit";
                this.familyForm.personal_caracterizacion=entity.personal_caracterizacion;
                this.familyForm.tipo_voto=entity.personal_caracterizacion.tipo_voto;
                this.familyForm.telefono_principal=entity.personal_caracterizacion.telefono_principal;
                this.familyForm.telefono_secundario=entity.personal_caracterizacion.telefono_secundario;
                this.familyForm.partido_politico_id=entity.personal_caracterizacion.partido_politico_id;
                this.familyForm.movilizacion_id=entity.personal_caracterizacion.movilizacion_id;

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
                // }else if(this.familyForm.telefono_secundario==""){
                //     swal({
                //         text:"Debe ingresar un teléfono secundario",
                //         icon:"error"
                //     });
                //     return false;
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
                        url: "{{ url('api/participacion-movimientos/familiares') }}"+"/"+this.entityFamily.id,
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
                        url: "{{ url('api/participacion-movimientos/familiares') }}"+"/"+entityFamily.id,
                        data: this.form
                    });
                    swal({
                        text:response.data.message,
                        icon: "success"
                    }).then(ans => {

                    })
                    await this.clearFormFamily();
                    await this.indexFamily();
                    await this.fetch();
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
                let filters={
                    trabajador_municipio_id:"{{Auth::user()->municipio ? Auth::user()->municipio_id : 0}}",
                    search:this.searchText,
                    count_familiares:1
                };
                let res = await axios.get(link == "" ? "{{ route('api.participacion.movimiento.index') }}" : link.url,{
                    params:filters
                })
                this.results = res.data.data
                this.links = res.data.links
                this.currentPage = res.data.current_page
                this.totalPages = res.data.last_page
            },
            async store(){
                
                //Validations
                if(this.form.personal_caracterizacion==null){
                    swal({
                        text:"Debe indicar el trabajador",
                        icon:"error"
                    });
                    return false;
                }else if(this.form.movimiento_id==""){
                    swal({
                        text:"Debe seleccionar un movimiento",
                        icon:"error"
                    });
                    return false;
                }else if(this.form.cargo_id==""){
                    swal({
                        text:"Debe seleccionar un cargo",
                        icon:"error"
                    });
                    return false;
                }else if(this.form.nivel_estructura_id==""){
                    swal({
                        text:"Debe seleccionar una estructura",
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
                // }else if(this.form.telefono_secundario==""){
                //     swal({
                //         text:"Debe ingresar un teléfono secundario",
                //         icon:"error"
                //     });
                //     return false;
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
                }else if(this.form.movilizacion_id==1 && this.form.direccion==""){
                    swal({
                        text:"Debe ingresar la dirección",
                        icon:"error"
                    });
                    return false;
                }
                try {
                    this.loading = true;
                    const response = await axios({
                        method: 'POST',
                        responseType: 'json',
                        url: "{{ route('api.participacion.movimiento.store') }}",
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
                this.cedula_trabajador=entity.personal_caracterizacion.cedula;
                //Jefe familia
                this.form.cargo_id=entity.cargo_id;
                this.form.movimiento_id=entity.movimiento_id;
                this.form.nivel_estructura_id=entity.nivel_estructura_id;
                this.form.direccion=entity.direccion;
                this.form.area_atencion=entity.area_atencion;
                this.form.personal_caracterizacion=entity.personal_caracterizacion;
                this.form.tipo_voto=entity.personal_caracterizacion.tipo_voto.toLowerCase();
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
                        url: "{{ url('api/participacion-movimientos') }}"+"/"+entityId,
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
                if(this.form.personal_caracterizacion==null){
                    swal({
                        text:"Debe indicar el trabajador",
                        icon:"error"
                    });
                    return false;
                }else if(this.form.movimiento_id==""){
                    swal({
                        text:"Debe seleccionar un movimiento",
                        icon:"error"
                    });
                    return false;
                }else if(this.form.cargo_id==""){
                    swal({
                        text:"Debe seleccionar un cargo",
                        icon:"error"
                    });
                    return false;
                }else if(this.form.tipo_voto==""){
                    swal({
                        text:"Debe seleccionar un tipo de voto",
                        icon:"error"
                    });
                    return false;
                }else if(this.form.nivel_estructura_id==""){
                    swal({
                        text:"Debe seleccionar una estructura",
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
                }else if(this.form.movilizacion_id==1 && this.form.movilizacion_id==""){
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
                        url: "{{ url('api/participacion-movimientos') }}"+"/"+this.entityId,
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
                this.form.personal_caracterizacion=null;
                this.form.direccion="";
                this.form.cargo_id="";
                this.form.movimiento_id="";
                this.form.nivel_estructura_id="";
                this.form.area_atencion="Municipal";
                this.form.tipo_voto="duro";
                this.form.telefono_principal="";
                this.form.telefono_secundario="";
                this.form.partido_politico_id="";
                this.form.movilizacion_id=3;
                this.cedula_trabajador="";
                this.cedula_trabajador_error="";
                this.entityId=null;
                this.action="create";
            },
            async obtenerTrabajador() {
                
                if(this.cedula_trabajador==""){
                    swal({
                        text:"Debe ingresar una cédula válida",
                        icon:"error"
                    });
                    return false;
                }
                try {
                    this.loading = true;
                    let filters = {
                        cedula:this.cedula_trabajador
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
                        this.form.personal_caracterizacion=response.data.elector;
                        this.cedula_trabajador_error="";
                        if(response.data.elector.tipo_voto){
                            this.form.tipo_voto=response.data.elector.tipo_voto.toLowerCase();
                        }else{
                            this.form.tipo_voto="duro";
                        }
                        if(response.data.elector.partido_politico_id){
                            this.form.partido_politico_id=response.data.elector.partido_politico_id;
                        }else{
                            this.form.partido_politico_id=4;

                        }
                        if(response.data.elector.movilizacion_id){
                            this.form.movilizacion_id=response.data.elector.movilizacion_id;
                        }else{
                            this.form.movilizacion_id=3;
                        }
                        this.form.telefono_principal=response.data.elector.telefono_principal;
                        this.form.telefono_secundario=response.data.elector.telefono_secundario;
                    }else{
                        
                        this.form.personal_caracterizacion=null;
                        this.cedula_trabajador_error="Elector no encontrado";
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
                    this.cedula_jefe_calle_error=err.response.data.message;
                    this.calles=[];
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
                        url: "{{ url('elector/search-by-cedula') }}",
                        params: filters
                    });
                    this.loading = false;
                    if(response.data.success==true){
                        this.familyForm.personal_caracterizacion=response.data.elector;
                        if(response.data.elector.tipo_voto){
                            this.familyForm.tipo_voto=response.data.elector.tipo_voto.toLowerCase();
                        }else{
                            this.familyForm.tipo_voto="duro";
                        }
                        if(response.data.elector.movilizacion_id){
                            this.familyForm.movilizacion_id=response.data.elector.movilizacion_id;
                        }
                        if(response.data.elector.partido_politico_id){
                            this.familyForm.partido_politico_id=response.data.elector.partido_politico_id;
                        }else{
                            this.familyForm.partido_politico_id=4;

                        }
                        if(response.data.elector.telefono_principal){
                            this.familyForm.telefono_principal=response.data.elector.telefono_principal;
                        }
                        if(response.data.elector.telefono_secundario){
                            this.familyForm.telefono_secundario=response.data.elector.telefono_secundario;
                        }
                        this.cedula_familiar_error="";
                    }else{
                        this.familyForm.personal_caracterizacion=null;
                        this.familyForm.tipo_voto="";
                        this.familyForm.movilizacion_id="";
                        this.familyForm.partido_politico_id="";
                        this.familyForm.telefono_principal="";
                        this.familyForm.telefono_secundario="";
                        this.cedula_familiar_error="Elector no encontrado";
                    }
                } catch (err) {
                    this.loading = false;
                    console.log(err)
                }
                
            },
            async obtenerMovimientos() {
                try {
                    this.loading = true;
                    let just_ids=[];
                    if("{{Auth::user()->movimientos}}"){
                        just_ids="{{json_encode(Auth::user()->movimientos->pluck('movimiento_id')->all())}}";
                        console.log(just_ids);
                    }
                    let filters = {
                        just_ids:just_ids
                    }
                    const response = await axios({
                        method: 'Get',
                        responseType: 'json',
                        url: "{{ route('api.movimiento.index') }}",
                        params: filters
                    });
                    this.loading = false;
                    this.movimientos = response.data;
                } catch (err) {
                    this.loading = false;
                    console.log(err)
                }
            },
            async obtenerNivelesEstructura() {
                try {
                    this.loading = true;
                    let filters = {}
                    const response = await axios({
                        method: 'Get',
                        responseType: 'json',
                        url: "{{ route('api.niveles-estructura.index') }}",
                        params: filters
                    });
                    this.loading = false;
                    this.nivelesEstructura = response.data;
                } catch (err) {
                    this.loading = false;
                    console.log(err)
                }
            },
            async obtenerCargos() {
                try {
                    this.loading = true;
                    let filters = {
                        tipo:1
                    }
                    const response = await axios({
                        method: 'Get',
                        responseType: 'json',
                        url: "{{ route('api.cargo.index') }}",
                        params: filters
                    });
                    this.loading = false;
                    this.cargos = response.data;
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

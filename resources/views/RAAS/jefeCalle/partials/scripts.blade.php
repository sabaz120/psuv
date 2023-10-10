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
                tipo_voto:"duro",
                telefono_principal:"",
                telefono_secundario:"",
                partido_politico_id:1,
                movilizacion_id:"",
                parroquia_id:"0",
                municipio_id:"{{Auth::user()->municipio ? Auth::user()->municipio->id : 0}}",
                centro_votacion_id:"0",
                rol_equipo_politico_id:"0"
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
            municipios:[],
            parroquias:[],
            centroVotaciones:[],
            comunidades:[],
            calles:[],
            rolesEquipoPoliticos:[],
            tipoDeVotos:[
                "Duro",
                "Blando",
                "Opositor"
            ],
            partidosPoliticos:[],
            tiposDeMovilizacion:[],
            results:[],
            //paginate
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
                this.getMunicipios();
                if(parseInt(this.form.municipio_id)){
                    await this.getParroquias();
                }
                await this.getRolesEquiposPoliticos();
            });
        },
        methods: {
            async getMunicipios(){
                if(this.form.municipio_id){
                    this.loading=true;
                    let res = await axios.get("{{ url('/api/municipios') }}",{
                        params:{
                            estado_name:"FALCON",
                            municipio_id:this.form.municipio_id
                        }
                    })
                    this.municipios = res.data
                    this.loading=false;
                }else{
                    this.parroquias=[];
                }
            },
            async getParroquias(){
                this.loading=true;
                this.form.parroquia_id="0";
                this.form.centro_votacion_id="0";
                this.form.comunidad_id="0";
                this.form.calle_id="0";
                this.centroVotaciones=[];
                this.comunidades=[];
                this.calles=[];
                if(parseInt(this.form.municipio_id)){
                    let res = await axios.get("{{ url('/api/parroquias') }}",{
                        params:{
                            municipio_id:this.form.municipio_id
                        }
                    })
                    this.parroquias = res.data
                }else{
                    this.parroquias=[];
                }
                this.loading=false;
            },
            async getCentroVotacion(){
                this.loading=true;
                this.form.centro_votacion_id="0";
                this.form.comunidad_id="0";
                this.form.calle_id="0";
                this.comunidades=[];
                this.calles=[];
                let res = await axios.get("{{ url('/api/centro-votacion') }}"+"/"+this.form.parroquia_id)
                this.centroVotaciones = res.data
                this.loading=false;
            },
            async getRolesEquiposPoliticos(){
                this.loading=true;
                let res = await axios.get("{{ url('/api/rol-equipo-politicos') }}",{
                    params:{
                        nivel_territorial_id:3
                    }
                })
                this.rolesEquipoPoliticos = res.data
                this.loading=false;
            },
            async getComunidades(){
                this.loading=true;
                this.form.comunidad_id="0";
                this.form.calle_id="0";
                this.calles=[];
                let res = await axios.get("{{ url('/api/comunidades') }}"+"/"+this.form.centro_votacion_id)
                this.comunidades = res.data
                this.loading=false;
            },
            async fetch(link = ""){
                let filters={
                    params:{
                        search:this.searchText,
                        municipio_id:this.form.municipio_id
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
                if(this.form.personal_caraterizacion==null){
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
                }else if(this.form.rol_equipo_politico_id==""){
                    swal({
                        text:"Debe seleccionar un rol",
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
                //Jefe calle
                this.cedula_jefe=entity.personal_caracterizacion.cedula;
                this.form.personal_caraterizacion=entity.personal_caracterizacion;
                this.form.tipo_voto=entity.personal_caracterizacion.tipo_voto.toLowerCase();
                this.form.telefono_principal=entity.personal_caracterizacion.telefono_principal;
                this.form.telefono_secundario=entity.personal_caracterizacion.telefono_secundario;
                this.form.partido_politico_id=entity.personal_caracterizacion.partido_politico_id;
                this.form.movilizacion_id=entity.personal_caracterizacion.movilizacion_id;
                this.form.municipio_id=entity.calle?.comunidad?.centro_votacion?.parroquia?.municipio_id;
                await this.getParroquias();
                this.form.parroquia_id=entity.calle?.comunidad?.centro_votacion?.parroquia_id;
                await this.getCentroVotacion();
                this.form.centro_votacion_id=entity.calle?.comunidad?.centro_votacion_id;
                await this.getComunidades();
                this.form.comunidad_id=entity.calle?.comunidad_id;
                await this.obtenerCalles();
                this.form.calle_id=entity.calle_id;
                this.form.rol_equipo_politico_id=entity.roles_nivel_territorial?.roles_equipo_politico_id;
            },
            async suspend(entityId){
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
                    }
                })
            },
            async update(){
              //Validations
                if(this.form.personal_caraterizacion==null){
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
                }else if(this.form.rol_equipo_politico_id==""){
                    swal({
                        text:"Debe seleccionar un rol",
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
                this.form.tipo_voto="duro";
                this.form.telefono_principal="";
                this.form.telefono_secundario="";
                this.form.partido_politico_id=1;
                this.form.movilizacion_id="";
                this.form.municipio_id="0";
                this.form.parroquia_id="0";
                this.form.centro_votacion_id="0";
                this.cedula_jefe="";
                this.cedula_jefe_comunidad="";
                this.cedula_jefe_comunidad_error="";
                this.cedula_jefe_error="";
                this.entityId=null;
                this.jefe_comunidad=null;
                this.calles=[];
                this.action="create";
                this.parroquias=[];
                this.centroVotaciones=[];
                this.comunidades=[];
                this.calles=[];
                this.form.rol_equipo_politico_id="0";
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
                        if(response.data.elector.telefono_principal){
                            this.form.telefono_principal=response.data.elector.telefono_principal;
                        }
                        if(response.data.elector.telefono_secundario){
                            this.form.telefono_secundario=response.data.elector.telefono_secundario;
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
                        // swal({
                        //     text:"Debe seleccionar una comunidad",
                        //     icon:"error"
                        // });
                        this.calles=[];
                        return;
                    }
                    this.loading = true;
                    let filters = {
                        comunidad_id:this.form.comunidad_id,
                        order_by:"nombre",
                        order_direction:"ASC"
                    }
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
                            text:"La comunidad seleccionada, no posee calles.",
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

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
                partido_politico_id:4,
                movilizacion_id:3,
                comision_trabajo_id:"",
                responsabilidad_comando_id:"",
            },
            entityId:null,
            entity:null,
            //search
            cedula_trabajador:"",
            cedula_trabajador_error:"",
            searchText:"",
            //Array data
            comisiones:[],
            responsabilidades:[],
            results:[],

            //paginate
            modalTitle:"Nuevo personal - Comando regional",
            currentPage:1,
            links:"",
            totalPages:"",
   
        },
        created: function() {
            this.$nextTick(async function() {
                this.loading = false;
                await this.fetch();
                await this.obtenerResponsabilidades();
                await this.obtenerComisionesTrabajo();
            });
        },
        methods: {

            async fetch(link = ""){
                let filters={
                    search:this.searchText,
                    includes:[
                        "personalCaracterizacion",
                        "comisionTrabajo",
                        "responsabilidadComando",
                    ]
                };
                let res = await axios.get(link == "" ? "{{ route('api.comando.regional.index') }}" : link.url,{
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
                        text:"Debe indicar el personal",
                        icon:"error"
                    });
                    return false;
                }else if(this.form.comision_trabajo_id==""){
                    swal({
                        text:"Debe seleccionar una comisión de trabajo",
                        icon:"error"
                    });
                    return false;
                }else if(this.form.responsabilidad_comando_id==""){
                    swal({
                        text:"Debe seleccionar una responsabilidad",
                        icon:"error"
                    });
                    return false;
                }else if(this.form.telefono_principal==""){
                    swal({
                        text:"Debe ingresar un teléfono principal",
                        icon:"error"
                    });
                    return false;
                }
                // else if(this.form.telefono_secundario==""){
                //     swal({
                //         text:"Debe ingresar un teléfono secundario",
                //         icon:"error"
                //     });
                //     return false;
                // }
                try {
                    this.loading = true;
                    const response = await axios({
                        method: 'POST',
                        responseType: 'json',
                        url: "{{ route('api.comando.regional.store') }}",
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
                this.form.comision_trabajo_id=entity.comision_trabajo_id;
                this.form.responsabilidad_comando_id=entity.responsabilidad_comando_id;
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
                        url: "{{ url('api/comandos/regionales') }}"+"/"+entityId,
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
                        text:"Debe indicar el personal",
                        icon:"error"
                    });
                    return false;
                }else if(this.form.comision_trabajo_id==""){
                    swal({
                        text:"Debe seleccionar una comisión de trabajo",
                        icon:"error"
                    });
                    return false;
                }else if(this.form.responsabilidad_comando_id==""){
                    swal({
                        text:"Debe seleccionar una responsabilidad",
                        icon:"error"
                    });
                    return false;
                }else if(this.form.telefono_principal==""){
                    swal({
                        text:"Debe ingresar un teléfono principal",
                        icon:"error"
                    });
                    return false;
                }
                // else if(this.form.telefono_secundario==""){
                //     swal({
                //         text:"Debe ingresar un teléfono secundario",
                //         icon:"error"
                //     });
                //     return false;
                // }
              try {
                    this.loading = true;
                    const response = await axios({
                        method: 'PUT',
                        responseType: 'json',
                        url: "{{ url('api/comandos/regionales') }}"+"/"+this.entityId,
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
                this.form.comision_trabajo_id="";
                this.form.responsabilidad_comando_id="";
                this.form.tipo_voto="duro";
                this.form.telefono_principal="";
                this.form.telefono_secundario="";
                this.form.partido_politico_id=4;
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
                    this.cedula_trabajador=err.response.data.message;
                    this.calles=[];
                }
            },

            async obtenerResponsabilidades() {
                try {
                    this.loading = true;
                    let filters = {}
                    const response = await axios({
                        method: 'Get',
                        responseType: 'json',
                        url: "{{ route('api.responsabilidad.comando.index') }}",
                        params: filters
                    });
                    this.loading = false;
                    this.responsabilidades = response.data;
                } catch (err) {
                    this.loading = false;
                    console.log(err)
                }
            },
            async obtenerComisionesTrabajo() {
                try {
                    this.loading = true;
                    let filters = {}
                    const response = await axios({
                        method: 'Get',
                        responseType: 'json',
                        url: "{{ route('api.comision.trabajo.index') }}",
                        params: filters
                    });
                    this.loading = false;
                    this.comisiones = response.data;
                } catch (err) {
                    this.loading = false;
                    console.log(err)
                }
            },
            
            generateExcel(){
                let params={};
                this.loading=true;
                axios({
                    url: `api/report/comandos/regional`,
                    method: 'GET',
                    params:params,
                    responseType: 'blob' // important
                }).then((response) => {
                    const url = window.URL.createObjectURL(new Blob([response.data]))
                    const link = document.createElement('a')
                    link.href = url
                    link.setAttribute('download', 'reporte-comandos-regionales.xlsx')
                    document.body.appendChild(link)
                    link.click()
                    this.loading=false;
                })
            },//generate()
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

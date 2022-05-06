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
            file:null,            
            results:[],
        },
        created: function() {
            this.$nextTick(async function() {
                this.loading = false;
            });
        },
        methods: {
            async handleChange(e) {
                if (!this.isValidFile(e.target.files[0])) return;
                this.setFile(e.target.files[0]);
                this.uploadFile();
            },
            
	        setFile(file) {
		        this.file = file;
	        },

            isValidFile(file) {
                const extension = file.name.split('.').reverse()[0];

                const validExtentions = ['xl', 'xls', 'xlsx'];

                if (!validExtentions.includes(extension)) {
                    swal({
                        text:'El archivo debe ser un formato de excel válido',
                        icon:"error"
                    });
                    return false;
                }
                return true;
            },

            
            async uploadFile() {
                try {

                    const dataForm = new FormData();
		            dataForm.append( 'file', this.file );

                    const {data,message} = await axios.post(
			            '/api/importacion/jefe_calle',
			            dataForm
		            );

                    swal({
                        text:data.message,
                        icon: "success"
                    })
                    this.results=data.data.errores;
                } catch (err) {
                    swal({
                        text:err.response.data.message,
                        icon:"error"
                    });
                }//catch()
            },//uploadFile()

            async store(){
                //Validations
                // if(this.form.jefe_comunidad_id==null){
                //     swal({
                //         text:"Debe indicar el jefe de comunidad",
                //         icon:"error"
                //     });
                //     return false;
                // }

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
                    })
                } catch (err) {
                    this.loading = false;
                    console.log(err)
                    swal({
                        text:err.response.data.message,
                        icon:"error"
                    });
                }
            },
        } //methods
    });
</script>

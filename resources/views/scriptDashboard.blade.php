<script src="{{ url('assets/js/pages/features/charts/apexchartsdashboard.js') }}"></script>

<script type="text/javascript">
    var app = new Vue({
        el: '#content',
        data() {
            return {

                linkClass:"page-link",
                activeLinkClass:"page-link active-link bg-main",
                currentPage:1,
                links:"",
                totalPages:"",

                clickCount:0,
                secondaryGraphic:0,
                type:"",
                metaGeneral:0,
                cargados:0,
                centroVotacionMetas:[],
                loading:false,
                secondaryInfo:"",

                selectedMunicipio:"0",
                selectedParroquia:"0",
                selectedCentroVotacion:"0",
                searchedMunicipio:"0",
                searchedParroquia:"0",
                searchedCentroVotacion:"0",
                municipios:[],
                parroquias:[],
                centrosVotacion:[],
                email:"",
                emailError:"",
                loading:false,
                authMunicipio:"{{ \Auth::user()->municipio_id ? \Auth::user()->municipio_id : 0}}"
            }
        },
        methods: {

            async getMunicipios(){

                this.selectedParroquia = "0"
                this.selectedCentroVotacion = "0"

                let res = await axios.get("{{ url('/api/municipios') }}")
                this.municipios = res.data

            },
            async getParroquias(){
                
                this.selectedParroquia = "0"
                this.selectedCentroVotacion = "0"

                let res = await axios.get("{{ url('/api/parroquias') }}"+"/"+this.selectedMunicipio)
                this.parroquias = res.data

            },

            async getCentroVotacion(){
                this.selectedCentroVotacion = "0"
                let res = await axios.get("{{ url('/api/centro-votacion') }}"+"/"+this.selectedParroquia)
                this.centrosVotacion = res.data

            },
            async generate(){

                this.loading = true

                this.searchedCentroVotacion = this.selectedCentroVotacion
                this.searchedParroquia = this.selectedParroquia
                this.searchedMunicipio = this.selectedMunicipio

                let paramsCentroVotacion = this.searchedCentroVotacion
                let paramsParroquia = this.searchedParroquia
                let paramsMunicipio = this.searchedMunicipio
                

                let res = await axios.post("{{ url('api/reporte-dashboard/generate') }}", {
                    centroVotacion: paramsCentroVotacion,
                    parroquia: paramsParroquia,
                    municipio: paramsMunicipio
                })

                this.loading = false
                this.secondaryInfo = res.data.entities
                this.type = res.data.type
                
                this.metaGeneral = res.data.data.participacion
                this.cargados = res.data.data.movilizacion
                console.log(this.metaGeneral,this.cargados);
                
                KTApexChartsDemo.init(this.metaGeneral == 0 && this.cargados == 0 ? 1 : this.metaGeneral, this.cargados, this.clickCount > 0 ? false : true, "#chart_12");
                //KTApexChartsDemo.init(0, 1, this.clickCount > 0 ? false : true, "#chart_12");
                //
                this.clickCount++
                
            },
            async fetch(link){

                this.loading = true

                let paramsCentroVotacion = this.searchedCentroVotacion
                let paramsParroquia = this.searchedParroquia
                let paramsMunicipio = this.searchedMunicipio
                

                let res = await axios.post(link.url, {
                    centroVotacion: paramsCentroVotacion,
                    parroquia: paramsParroquia,
                    municipio: paramsMunicipio
                })

                this.loading = false
                
                this.centroVotacionMetas = res.data.data.centroVotacionMetas.data

                this.links = res.data.data.centroVotacionMetas.links
                this.currentPage = res.data.data.centroVotacionMetas.current_page
                this.totalPages = res.data.data.centroVotacionMetas.last_page

            },


            generateCharts(entities){

                for(var i = 0; i < entities.length; i++){

                    KTApexChartsDemo.init(entities[i].meta, entities[i].cargados, true, "#graphic-"+i);

                }

                this.secondaryGraphic++;

            },
            
            validateCorreo(email){
                const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                return re.test(String(email).toLowerCase());
            },

            downloadExcel(){

                let paramsCentroVotacion = this.selectedParroquia != "0" ? "&centroVotacion="+this.selectedCentroVotacion : "&centroVotacion=0"
                let paramsParroquia = this.selectedMunicipio != "0" ? "&parroquia="+this.selectedParroquia : "&parroquia=0"
                let paramsMunicipio = "municipio="+this.selectedMunicipio

                window.location.href="{{ url('api/reporte-carga/download') }}"+"?"+paramsMunicipio+paramsParroquia+paramsCentroVotacion

            }


        },
        async created() {

            await this.getMunicipios()
            this.selectedMunicipio = "0"
            this.selectedParroquia = "0"
            this.selectedCentroVotacion = "0"
            this.selectedMunicipio = this.authMunicipio

            if(this.selectedMunicipio != "0"){
                await this.getParroquias()
            }

            setTimeout(() => {
                this.generate()
            }, 2000);
            
        }
    });
</script>
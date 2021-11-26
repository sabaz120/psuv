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
                selectedTipoJefe:"1",

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
                selectedComunidad:"0",
                searchedMunicipio:"0",
                searchedParroquia:"0",
                searchedCentroVotacion:"0",
                municipios:[],
                parroquias:[],
                comunidades:[],
                
                loading:false,
                authMunicipio:"{{ \Auth::user()->municipio_id ? \Auth::user()->municipio_id : 0}}"
            }
        },
        methods: {

            async getMunicipios(){

                this.selectedParroquia = "0"
                this.selectedComunidad = "0"

                let res = await axios.get("{{ url('/api/municipios') }}")
                this.municipios = res.data

            },
            async getParroquias(){
                
                this.selectedParroquia = "0"
                this.selectedComunidad = "0"

                let res = await axios.get("{{ url('/api/parroquias') }}"+"/"+this.selectedMunicipio)
                this.parroquias = res.data

            },


            downloadExcel(){

                let paramsComunidad = this.selectedParroquia != "0" ? "&comunidad="+this.selectedComunidad : "&comunidad=0"
                let paramsParroquia = this.selectedMunicipio != "0" ? "&parroquia="+this.selectedParroquia : "&parroquia=0"
                let paramsMunicipio = "municipio="+this.selectedMunicipio

                window.location.href="{{ url('api/listado-jefe/download') }}"+"?"+paramsMunicipio+paramsParroquia+paramsComunidad+"&type="+this.selectedTipoJefe

            },

            async getComunidades(){

                let res = await axios.get("{{ url('/api/comunidades') }}"+"/"+this.selectedParroquia)
                this.comunidades = res.data

            },


        },
        async created() {

            await this.getMunicipios()
            this.selectedMunicipio = "0"
            this.selectedParroquia = "0"
            this.selectedComunidad = "0"
            this.selectedMunicipio = this.authMunicipio

            if(this.selectedMunicipio != "0"){
                await this.getParroquias()
            }


        }
    });
</script>
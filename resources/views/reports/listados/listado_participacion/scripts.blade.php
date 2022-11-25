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
                selectedTipo:"UBCH",

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
                selectedCalle:"0",
                municipios:[],
                parroquias:[],
                comunidades:[],
                calles:[],
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
            async getComunidades(){
                let res = await axios.get("{{ url('/api/comunidades') }}"+"/"+this.selectedParroquia)
                this.comunidades = res.data
            },
            async getCalles(){
                let filters = {
                    comunidad_id:this.selectedComunidad,
                    order_by:"nombre",
                    order_direction:"ASC"
                }
                let res = await axios.get("{{ route('api.calles.index') }}"+"/",{
                    params: filters
                })
                this.calles = res.data
            },
            downloadExcel(){
                let params={
                    municipio_id:this.selectedMunicipio,
                    parroquia_id:this.selectedParroquia,
                    comunidad_id:this.selectedComunidad,
                    calle_id:this.selectedCalle,
                    type:this.selectedTipo
                };
                this.loading=true;
                axios({
                    url: `api/raas/report/participation`,
                    method: 'GET',
                    params:params,
                    responseType: 'blob' // important
                }).then((response) => {
                    const url = window.URL.createObjectURL(new Blob([response.data]))
                    const link = document.createElement('a')
                    link.href = url
                    link.setAttribute('download', 'reporte-participacion.xlsx')
                    document.body.appendChild(link)
                    link.click()
                })
                this.loading=false;
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
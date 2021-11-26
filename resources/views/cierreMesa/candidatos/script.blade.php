<script type="text/javascript">

    var sync = false;
    var date = "";

    const app = new Vue({
        el: '#dev-ubch',
        data() {
            return {

                linkClass:"page-link",
                activeLinkClass:"page-link active-link bg-main",
                searchText:"",

                searchLoading:false,
                loading:false,

                centrosVotacion:[],
                resultados:[],
                currentPage:1,
                links:"",
                totalPages:"",

                authMunicipio:"{{ \Auth::user()->municipio_id ? \Auth::user()->municipio_id : 0}}"
            }
        },
        methods: {
            
            async getMesas(){
                this.loading = true
                let res = await axios.get("{{ url('/api/cierre-mesa/candidato/get-mesas-cerradas') }}"+"?municipio_id="+this.authMunicipio)
                this.loading = false
                this.centrosVotacion = res.data.data

                this.links = res.data.links
                this.currentPage = res.data.current_page
                this.totalPages = res.data.last_page

            },
            
            async fetch(url){
                let res = null
                this.loading = true
                if(this.searchText == ""){
                    res = await axios.get(url.url+"&municipio_id="+this.authMunicipio)
                }else{
                    res = await axios.get(url.url+"&searchText="+this.searchText+"&municipio_id="+this.authMunicipio)
                }
                this.loading = false

                
                this.centrosVotacion = res.data.data

                this.links = res.data.links
                this.currentPage = res.data.current_page
                this.totalPages = res.data.last_page

            },
            async search(){

                this.searchLoading = true
                let res = await axios.get("{{ url('api/cierre-mesa/candidato/search-centro-votacion') }}",  {"params": {
                        "search": this.searchText,
                        "municipio_id": this.authMunicipio
                }})
                this.searchLoading = false

                this.centrosVotacion = res.data.data
                this.links = res.data.links
                this.currentPage = res.data.current_page
                this.totalPages = res.data.last_page
                

            },
            hourFormat(hour){

                let splitted = hour.split(":")
                let finalText = ""
                let splittedHour = splitted[0]
                let meridian = "AM"

                if(parseInt(splitted[0]) > 12){
                    splittedHour = splittedHour - 12
                    splittedHour = splittedHour < 10 ? "0"+splittedHour : splittedHour
                    meridian = "PM"
                }

                finalText = splittedHour+":"+splitted[1]+" "+meridian
                return finalText

            },

            async getResultados(mesa_id){

                let res = await axios.get("{{ url('api/cierre-mesa/candidato/get-resultadods/') }}"+"/"+mesa_id)
                this.resultados = res.data
                

            }
            

        },
        created() {

            this.getMesas()

        }
    });
</script>
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

                loading:false,
                emailLoading:false,
                searchText:"",

                centrosVotacion:[],
                
                authMunicipio:"{{ \Auth::user()->municipio_id ? \Auth::user()->municipio_id : 0}}"
            }
        },
        methods: {

            async getCentrosVotacion(){

                let res = await axios.get("{{ url('/api/gestionar-votos/get-centros') }}"+"?municipio_id="+this.authMunicipio)
                this.centrosVotacion = res.data.data

                this.links = res.data.links
                this.currentPage = res.data.current_page
                this.totalPages = res.data.last_page

            },
            async search(){

                let res = await axios.get("{{ url('/api/gestionar-votos/search-centros') }}"+"?municipio_id="+this.authMunicipio+"&searchText="+this.searchText)
                this.centrosVotacion = res.data.data

                this.links = res.data.links
                this.currentPage = res.data.current_page
                this.totalPages = res.data.last_page

            },
            async fetch(url){
                let res = null
                if(this.searchText == ""){
                    res = await axios.get(url.url+"&municipio_id="+this.authMunicipio)
                }else{
                    res = await axios.get(url.url+"&searchText="+this.searchText)
                }

                
                this.centrosVotacion = res.data.data

                this.links = res.data.links
                this.currentPage = res.data.current_page
                this.totalPages = res.data.last_page

            }


        },
        async created() {

            await this.getCentrosVotacion()


        }
    });
</script>
<script type="text/javascript">

    var sync = false;
    var date = "";

    const app = new Vue({
        el: '#dev-ubch',
        data() {
            return {

                linkClass:"page-link",
                activeLinkClass:"page-link active-link bg-main",
                candidatosSearchText:"",

                searchLoading:false,
                storeLoading:false,
                storeResultsLoading:false,

                centrosVotacion:[],
                municipios:[],
                parroquias:[],
                mesas:[],
                errors:[],
                candidatos:[],

                selectedMunicipio:"",
                selectedParroquia:"",
                selectedCentroVotacion:"",
                selectedMesa:"",
                hora:"",
                minuto:"",
                meridiano:"",
                currentPage:1,
                links:"",
                totalPages:"",

                finalMesaId:"",

                authMunicipio:"{{ \Auth::user()->municipio_id ? \Auth::user()->municipio_id : 0}}",
                disabledMunicipio:false
            }
        },
        methods: {
            
            clear(){

                this.hora = ""
                this.minuto = ""
                this.meridiano = ""
                this.selectedParroquia = ""
                this.selectedCentroVotacion = ""
                this.selectedMunicipio = this.authMunicipio

            },

            async getMunicipios(){

                this.selectedParroquia = ""
                this.selectedCentroVotacion = ""
                this.selectedMesa = ""

                let res = await axios.get("{{ url('/api/municipios') }}")
                this.municipios = res.data

            },
            async getParroquias(){
    
                this.selectedCentroVotacion = ""
                this.selectedMesa = ""

                let res = await axios.get("{{ url('/api/parroquias') }}"+"/"+this.selectedMunicipio)
                this.parroquias = res.data

            },

            async getCentrosVotacion(){

                this.selectedMesa = ""

                let res = await axios.get("{{ url('/api/centro-votacion') }}"+"/"+this.selectedParroquia)
                this.centrosVotacion = res.data

            },

            async getMesas(){

                let res = await axios.get("{{ url('/api/cierre-mesa/candidato/get-mesas/') }}"+"/"+this.selectedCentroVotacion)
                this.mesas = res.data

            },

            async getCandidatos(municipioId){

                let res = await axios.get("{{ url('/api/cierre-mesa/candidato/get-candidatos/') }}", {
                    params:{
                        municipio_id: municipioId
                    }
                })
                this.candidatos = res.data

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
                let res = await axios.get("{{ url('api/cierre-mesa/candidato/search-candidato') }}",  {"params": {
                        "search": this.candidatosSearchText,
                        "municipio_id": this.authMunicipio
                }})
                this.searchLoading = false

                this.candidatos = res.data.data

            },

            async update(){

                this.storeLoading = true
                let res = await axios.post("{{ url('api/cierre-mesa/candidato/mesa/update') }}", {
                    "mesaId": this.selectedMesa,
                    "hora": this.hora,
                    "minuto": this.minuto,
                    "meridiano": this.meridiano,
                })
                this.storeLoading = false

                if(res.data.success == true){

                    swal({
                        "text": res.data.msg,
                        "icon": "success"
                    }).then(ans => {
                        
                        this.getCandidatos(this.selectedMunicipio)
                        this.finalMesaId = this.selectedMesa
                    })

                }else{

                    swal({
                        "text": res.data.msg,
                        "icon": "error"
                    })

                }

            },

            gatherData(){

                let results = []

                for(let i = 0; i < this.candidatos.length; i++){

                    results.push(
                        {"id": this.candidatos[i].id, "votos": $("#voto-"+this.candidatos[i].id).val()}
                    )

                }
                return results

            },

            async storeResults(){

                this.storeResultsLoading = true
                let res = await axios.post("{{ url('api/cierre-mesa/candidato/store-results') }}", {
                    "results": this.gatherData(),
                    "mesaId": this.finalMesaId
                })
                this.storeResultsLoading = false

                if(res.data.success == true){

                    swal({
                        "text": res.data.msg,
                        "icon": "success"
                    }).then(ans => {
                        
                        window.location.href="{{ route('cierre-mesa.candidatos') }}"
                    })

                }else{

                    swal({
                        "text": res.data.msg,
                        "icon": "error"
                    })

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
            }
            
            

        },
        created() {

            this.selectedMunicipio = this.authMunicipio
            this.getMunicipios()

            if(this.selectedMunicipio != ""){
                this.getParroquias()
            }

            if(this.authMunicipio != 0){
                this.disabledMunicipio = true
            }

        }
    });
</script>
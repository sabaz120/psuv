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
                storeLoading:false,

                participaciones:[],
                mesas:[],
                errors:[],
                
                hora:"01",
                minuto:"00",
                meridiano:"AM",
                cantidadVotos:"0",
                selectedMesa:"",
                
                centroVotacionId:"{{ $centro_votacion_id }}",
                currentPage:1,
                links:"",
                totalPages:"",

                authMunicipio:"{{ \Auth::user()->municipio_id ? \Auth::user()->municipio_id : 0}}"
            }
        },
        methods: {
            
            async getParticipaciones(){
                this.loading = true
                let res = await axios.get("{{ url('/api/votaciones/centro-votacion/mesa/participaciones/get-participaciones') }}"+"?centro_votacion_id="+this.centroVotacionId)
                this.loading = false
                this.participaciones = res.data.data

                this.links = res.data.links
                this.currentPage = res.data.current_page
                this.totalPages = res.data.last_page

            },
            
            async fetch(url){
                let res = null
                this.loading = true
                if(this.searchText == ""){
                    res = await axios.get(url.url+"&centro_votacion_id="+this.centroVotacionId)
                }else{
                    res = await axios.get(url.url+"&searchText="+this.searchText+"&centro_votacion_id="+this.centroVotacionId)
                }
                this.loading = false
                this.participaciones = res.data.data

                this.links = res.data.links
                this.currentPage = res.data.current_page
                this.totalPages = res.data.last_page

            },
            async search(){

                this.searchLoading = true
                let res = await axios.get("{{ url('api/votaciones/centro-votacion/mesa/participaciones/search-mesa') }}",  {"params": {
                        "search": this.searchText,
                        "centro_votacion_id": this.centroVotacionId
                }})
                this.searchLoading = false

                this.participaciones = res.data.data
                this.links = res.data.links
                this.currentPage = res.data.current_page
                this.totalPages = res.data.last_page
                

            },
            async getMesas(){
                
                let res = await axios.get("{{ url('api/votaciones/centro-votacion/mesa') }}"+"/"+this.centroVotacionId)
                this.mesas = res.data

            },
            clear(){

                this.hora = "01"
                this.minuto = "00"
                this.meridiano = "AM"
                this.selectedMesa = ""
                this.cantidadVotos = 0


            },
            async store(){

                try{
                    this.errors = []
                    this.storeLoading = true
                    let res = await axios.post("{{ url('api/votaciones/centro-votacion/mesa/store') }}", {
                        "hora": this.hora,
                        "minuto": this.minuto,
                        "meridiano": this.meridiano,
                        "mesa": this.selectedMesa,
                        "cantidad_votos": this.cantidadVotos
                    })
                    this.storeLoading = false

                    if(res.data.success == true){

                        swal({
                            text:res.data.msg,
                            icon:"success"
                        })

                        this.clear()
                        this.getParticipaciones()

                    }else{

                        swal({
                            text:res.data.msg,
                            icon:"error"
                        })

                    }

                }catch(err){
                    this.storeLoader = false
                    swal({
                        text:"Hay algunos campos que debes revisar",
                        icon: "error"
                    })

                    this.errors = err.response.data.errors

                }
                this.storeLoading = false

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
            async remove(id){

                swal({
                    title: "¿Estás seguro?",
                    text: "Eliminarás esta participación!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then(async (willDelete) => {

                    if (willDelete) {
                        let res = await axios.post("{{ url('api/votaciones/centro-votacion/mesa/delete') }}", {id: id})

                        if(res.data.success == true){

                            swal({
                                text:res.data.msg,
                                icon: "success"
                            })

                            let url = {
                                url:""
                            }
                            url.url = "{{ url('/api/votaciones/centro-votacion/mesa/participaciones/get-participaciones') }}"+"?page="+this.currentPage
                            this.fetch(url)

                        }else{
                    
                            swal({
                                text:res.data.msg,
                                icon: "error"
                            })

                        }
                    }


                })


            },
            

        },
        created() {

            this.getParticipaciones()
            this.getMesas()

        }
    });
</script>
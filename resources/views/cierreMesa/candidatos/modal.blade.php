<!-- Modal-->
<div class="modal fade marketModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Resultados</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">

                        <table class="table">
                            <thead>
                                <tr>
                                    <th style="width: 120px;">Foto</th>
                                    <th>Candidato</th>
                                    <th>Cargo</th>
                                    <th>Total votos</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="resultado in resultados">
                                    <td>
                                        <img :src="resultado.candidato.foto" alt="" class="w-100">
                                    </td>
                                    <td>
                                        @{{ resultado.candidato.nombre }} @{{ resultado.candidato.apellido }}
                                    </td>
                                    <td>
                                        @{{ resultado.candidato.cargo_eleccion }}
                                    </td>
                                    <td>
                                        @{{ resultado.cantidad_voto }}
                                    </td>
                                </tr>
                            
                            </tbody>
                        </table>
                
                    </div>
                </div>                    
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Cerrar</button>
               
            </div>
        </div>
    </div>
</div>
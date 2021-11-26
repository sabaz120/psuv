<!-- Modal-->
<div class="modal fade marketModal"  data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Visualizar puntos rojos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="float-right">
                                <div class="form-group">
                                    
                                    <div class="d-flex">
                                        
                                        <a target="_blank" :href="'{{ url('api/votaciones/centro-votacion/export-personal-punto-rojo/') }}'+'/'+selectedCentroVotacion" class="btn btn-warning">
                                            Exportar
                                        </a>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">Municipio</label>
                                <input type="text" class="form-control" v-model="puntoRojoMunicipio" disabled>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">Parroquia</label>
                                <input type="text" class="form-control" v-model="puntoRojoParroquia" disabled>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">Punto rojo</label>
                                <input type="text" class="form-control" v-model="puntoRojoUBCH" disabled>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">Jefe de UBCH</label>
                                <input type="text" class="form-control" v-model="puntoRojoJefeUBCH" disabled>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">Teléfono</label>
                                <input type="text" class="form-control" v-model="puntoRojoTelefono" disabled>
                            </div>
                        </div>
                
                    </div>


                    <div class="row">
                        <div class="col-12">
                            <div class="float-right">
                                <div class="form-group">
                                    <label>Buscar</label>
                                    <div class="d-flex">
                                        <input class="form-control" placeholder="Nombre personal" v-model="personalSearchText">
                                        <button class="btn btn-primary" @click="searchPersonal()">
                                            <i class="fas fa-search"></i>
                                        </button>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>CV</th>
                                        <th>Nombre</th>
                                        <th>Teléfono principal</th>
                                        <th>Teléfono secundario</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="personalPuntoRojo in personalPuntoRojoData">
                                        <td>@{{ personalPuntoRojo.centro_votacion.nombre }}</td>
                                        <td>@{{ personalPuntoRojo.fullName }}</td>
                                        <td>@{{ personalPuntoRojo.telefono_principal }}</td>
                                        <td>@{{ personalPuntoRojo.telefono_secundario }}</td>
                                    </tr>
                                
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12 col-md-5">
                            <div class="dataTables_info" id="kt_datatable_info" role="status" aria-live="polite">Mostrando página @{{ personalPuntoRojoCurrentPage }} de @{{ personalPuntoRojoTotalPages }}</div>
                        </div>
                        <div class="col-sm-12 col-md-7">
                            <div class="dataTables_paginate paging_full_numbers" id="kt_datatable_paginate">
                                <ul class="pagination">
                                    
                                    <li class="paginate_button page-item active" v-for="(link, index) in personalPuntoRojoLinks">
                                        <a style="cursor: pointer" aria-controls="kt_datatable" tabindex="0" :class="link.active == false ? linkClass : activeLinkClass":key="index" @click="personalFetch(link)" v-html="link.label.replace('Previous', 'Anterior').replace('Next', 'Siguiente')"></a>
                                    </li>
                                    
                                    
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>                    
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
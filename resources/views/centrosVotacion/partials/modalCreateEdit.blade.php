<!-- Modal-->
<div class="modal fade marketModal"  data-backdrop="static" tabindex="1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Gestionar mesa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="clearForm()">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">

                    <div class="col-md-6">
                            <div class="form-group">
                                <label >Número de mesa</label>
                                <input type="text" class="form-control" disabled v-model="formMesa.numero_mesa" >
                            </div>
                    </div>

                    <div class="col-md-6">
                            <div class="form-group">
                                <label >Observación (opcional)</label>
                                <input type="text" class="form-control" v-model="formMesa.observacion" maxlength="160" >
                            </div>
                        </div>

                    </div>

                    <div class="col-md-12 text-center">

                        <button type="button" class="btn btn-primary font-weight-bold"  @click="store()" v-if="action == 'create' && !loading">Crear</button>
                        <button type="button" class="btn btn-light-primary font-weight-bold"  @click="clearForm()" v-if="action == 'edit' && !loading">Cancelar edición</button>
                        <button type="button" class="btn btn-primary font-weight-bold"  @click="update()" v-if="action == 'edit' && !loading">Actualizar</button>
                        <div class="spinner spinner-primary ml-1 mr-13 mt-2" v-if="loading"></div>

                    </div>

                    <div class="col-md-12 my-5">
                        <span class="text-center">
                            <hr>
                            <h3>Listado de mesas</h3>
                        </span>
                        <!--begin: Datatable-->
                        <div class="datatable datatable-bordered datatable-head-custom datatable-default datatable-primary datatable-loaded" >
                        
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>

                                        <th class="datatable-cell datatable-cell-sort">
                                            <span>Número de mesa</span>
                                        </th>

                                        <th class="datatable-cell datatable-cell-sort">
                                            <span>Observación</span>
                                        </th>

                                        <th class="datatable-cell datatable-cell-sort">
                                            <span>Acción</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="mesa in mesas">
                                        <td>@{{ mesa.numero_mesa }}</td>
                                        <td>@{{ mesa.observacion }}</td>
                                        <td>
                                            <button title="Editar" class="btn btn-success" @click="edit(mesa)">
                                                <i class="far fa-edit"></i>
                                            </button>
                                            <button title="Suspender" class="btn btn-secondary" @click="suspend(mesa.id)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr v-if="mesas.length==0">
                                        <td colspan="3">No posee mesas registradas.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                       
                        </div>
                    <!--end: Datatable-->

                    </div>


                </div>                    
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal" @click="clearForm()">Cerrar</button>
            </div>
        </div>
    </div>
</div>
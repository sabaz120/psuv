<!-- Modal-->
<div class="modal fade puntoRojoModal"  data-backdrop="static" tabindex="2" role="dialog" aria-labelledby="puntoRojoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="puntoRojoModalLabel">Gestionar personal punto rojo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="clearFormPuntoRojo()">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="cedula">Cédula</label>
                            <div class="d-flex">
                                <div>
                                    <input type="tel" class="form-control"  v-model="formPuntoRojo.cedula" maxlength="8" @keypress="isNumber($event)" :readonly="entityPuntoRojoId">
                                </div>  
                            </div>
                        </div>
                    </div>

                    
                    <div class="col-md-4">
                            <div class="form-group">
                                <label for="nombre">Nombre</label>
                                <input type="text" class="form-control" v-model="formPuntoRojo.nombre">
                            </div>
                    </div>
                                   
                                        
                    <div class="col-md-4">
                            <div class="form-group">
                                <label for="nombre">Apellido</label>
                                <input type="text" class="form-control" v-model="formPuntoRojo.apellido">
                            </div>
                    </div>

                    <div class="col-md-4">
                            <div class="form-group">
                                <label for="nombre">Teléfono principal</label>
                                <input type="text" class="form-control" v-model="formPuntoRojo.telefono_principal" maxlength="11" @keypress="isNumber($event)">
                            </div>
                    </div>

                                       
                    <div class="col-md-4">
                            <div class="form-group">
                                <label for="nombre">Teléfono secundario</label>
                                <input type="text" class="form-control" v-model="formPuntoRojo.telefono_secundario" maxlength="11" @keypress="isNumber($event)">
                            </div>
                    </div>

                    <div class="col-md-12 text-center">

                        <button type="button" class="btn btn-primary font-weight-bold"  @click="storePuntoRojo()" v-if="actionPuntoRojo == 'create' && !loading">Crear</button>
                        <button type="button" class="btn btn-light-primary font-weight-bold"  @click="clearFormPuntoRojo()" v-if="actionPuntoRojo == 'edit' && !loading">Cancelar edición</button>
                        <button type="button" class="btn btn-primary font-weight-bold"  @click="updatePuntoRojo()" v-if="actionPuntoRojo == 'edit' && !loading">Actualizar</button>
                        <div class="spinner spinner-primary ml-1 mr-13 mt-2" v-if="loading"></div>

                    </div>

                    <div class="col-md-12 my-5">
                        <span class="text-center">
                            <hr>
                            <h3>Listado de personal punto rojo</h3>
                        </span>
                        <!--begin: Datatable-->
                        <div class="datatable datatable-bordered datatable-head-custom datatable-default datatable-primary datatable-loaded" >
                        
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>

                                        <th class="datatable-cell datatable-cell-sort">
                                            <span>Nombre completo</span>
                                        </th>

                                        <th class="datatable-cell datatable-cell-sort">
                                            <span>Cédula</span>
                                        </th>
                                        
                                        <th class="datatable-cell datatable-cell-sort">
                                            <span>Teléfono principal</span>
                                        </th>
                                                             
                                        <th class="datatable-cell datatable-cell-sort">
                                            <span>Teléfono secundario</span>
                                        </th>

                                        <th class="datatable-cell datatable-cell-sort">
                                            <span>Acción</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="persona in personal">
                                        <td>@{{ persona.fullName }}</td>
                                        <td>@{{ persona.cedula }}</td>
                                        <td>@{{ persona.telefono_principal }}</td>
                                        <td>@{{ persona.telefono_secundario }}</td>
                                        <td>
                                            <button title="Editar" class="btn btn-success" @click="editPuntoRojo(persona)">
                                                <i class="far fa-edit"></i>
                                            </button>
                                            <button title="Suspender" class="btn btn-secondary" @click="suspendPuntoRojo(persona.id)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr v-if="personal.length==0">
                                        <td colspan="5">No posee personal registrados.</td>
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
                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal" @click="clearFormPuntoRojo()">Cerrar</button>
            </div>
        </div>
    </div>
</div>
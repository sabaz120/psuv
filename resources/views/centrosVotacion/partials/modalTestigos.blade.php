<div class="modal fade testigoModal"  data-backdrop="static" tabindex="1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Gestionar testigos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="clearFormTestigo()">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                    <!--  -->
                    <div class="col-md-4">
                            <div class="form-group">
                                <label >Mesa</label>
                                <select class="form-control" v-model="formTestigo.mesa_id" :disabled="actionTestigo=='edit'">
                                    <option value="">Seleccione</option>
                                    <option :value="mesa.id" v-for="mesa in mesas">@{{mesa.numero_mesa}}</option>
                                </select>
                            </div>
                    </div>
                    <!--  -->


                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="cedula">Cédula</label>
                            <div class="d-flex">
                                <div>
                                    <input type="tel" class="form-control" id="cedula" v-model="cedula_testigo" maxlength="8" @keypress="isNumber($event)" :readonly="entityTestigoId">
                                    <small class="text-danger" v-if="cedula_testigo_error">@{{ cedula_testigo_error }}</small>
                                </div>
                                <div >
                                    <button class="btn btn-primary" @click="obtenerElector()" v-if="!loading" :disabled="entityTestigoId">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    <div class="spinner spinner-primary ml-1 mr-13 mt-5" v-if="loading"></div>
                                </div>      
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                            <div class="form-group">
                                <label for="nombre">Nombre</label>
                                <input type="text" class="form-control" v-if="formTestigo.personal_caracterizacion" v-model="formTestigo.personal_caracterizacion.full_name" readonly>
                                <input type="text" class="form-control" v-else readonly>
                            </div>
                    </div>

                                     
                    <div class="col-md-4">
                            <div class="form-group">
                                <label for="nombre">Teléfono principal</label>
                                <input type="text" class="form-control" v-if="formTestigo.personal_caracterizacion" v-model="formTestigo.telefono_principal" maxlength="11" @keypress="isNumber($event)">
                                <input type="text" class="form-control" v-else readonly>
                            </div>
                    </div>

                                       
                    <div class="col-md-4">
                            <div class="form-group">
                                <label for="nombre">Teléfono secundario</label>
                                <input type="text" class="form-control" v-if="formTestigo.personal_caracterizacion" v-model="formTestigo.telefono_secundario" maxlength="11" @keypress="isNumber($event)">
                                <input type="text" class="form-control" v-else readonly>
                            </div>
                    </div>

                    <div class="col-md-12 text-center">

                        <button type="button" class="btn btn-primary font-weight-bold"  @click="storeTestigo()" v-if="actionTestigo == 'create' && !loading">Crear</button>
                        <button type="button" class="btn btn-light-primary font-weight-bold"  @click="clearFormTestigo()" v-if="actionTestigo == 'edit' && !loading">Cancelar edición</button>
                        <button type="button" class="btn btn-primary font-weight-bold"  @click="updateTestigo()" v-if="actionTestigo == 'edit' && !loading">Actualizar</button>
                        <div class="spinner spinner-primary ml-1 mr-13 mt-2" v-if="loading"></div>

                    </div>

                    
                    <div class="col-md-12 my-5">
                        <span class="text-center">
                            <hr>
                            <h3>Listado de testigos</h3>
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
                                            <span>Testigo</span>
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
                                    <tr v-for="testi in testigos">
                                        <td>@{{ testi.mesa.numero_mesa }}</td>
                                        <td>@{{ testi.personal_caracterizacion.full_name }}</td>
                                        <td>@{{ testi.personal_caracterizacion.telefono_principal }}</td>
                                        <td>@{{ testi.personal_caracterizacion.telefono_secundario }}</td>
                                        <td>
                                            <button title="Editar" class="btn btn-success" @click="editTestigo(testi)">
                                                <i class="far fa-edit"></i>
                                            </button>
                                            <button title="Suspender" class="btn btn-secondary" @click="suspendTestigo(testi.id)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr v-if="mesas.length==0">
                                        <td colspan="5">No posee testigos registrados.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                       
                        </div> <!--end: Datatable-->

                    </div>



                    </div> <!-- row -->
                </div> <!-- container fluid -->
            </div> <!-- modal body -->
        
            <div class="modal-footer">
                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal" @click="clearFormTestigo()">Cerrar</button>
            </div>
        </div>
    </div>
</div>
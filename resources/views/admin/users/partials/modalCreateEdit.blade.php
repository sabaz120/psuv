<!-- Modal-->
<div class="modal fade marketModal"  data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">@{{ modalTitle }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="clearForm()">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="calle">Rol</label>
                                <select class="form-control" v-model="form.role_id" v-if="roles.length>0">
                                    <option value="">Seleccione</option>
                                    <option :value="rol.id" v-for="rol in roles">@{{ rol.name }}</option>
                                </select>
                                <select class="form-control" v-else disabled>
                                    <option value="" selected>Seleccione</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="calle">Municipio</label>
                                <select class="form-control" v-model="form.municipio_id" v-if="municipios.length>0">
                                    <option value="">Todos</option>
                                    <option :value="municipio.id" v-for="municipio in municipios">@{{ municipio.nombre }}</option>
                                </select>
                                <select class="form-control" v-else disabled>
                                    <option value="" selected>Seleccione</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group text-center">
                                <label for="calle">Instituciones</label>
                                <select class="form-control" v-model="form.instituciones" v-show="instituciones.length>0" multiple>
                                    <option :value="institucion.id" v-for="institucion in instituciones">@{{ institucion.nombre }}</option>
                                </select>
                                <select class="form-control" v-show="instituciones.length==0" disabled>
                                    <option value="" selected>Seleccione</option>
                                </select>
                                <button type="buttton" @click="form.instituciones=[];" class="btn btn-light-primary font-weight-bold mt-3">Limpiar</button>
                            </div>
                        </div>

                        
                        <div class="col-md-6">
                            <div class="form-group text-center">
                                <label for="calle">Movimientos</label>
                                <select class="form-control" v-model="form.movimientos" v-show="movimientos.length>0" multiple>
                                    <option :value="movimiento.id" v-for="movimiento in movimientos">@{{ movimiento.nombre }}</option>
                                </select>
                                <select class="form-control"  v-show="movimientos.length==0" disabled>
                                    <option value="" selected>Seleccione</option>
                                </select>
                                <button type="buttton" @click="form.movimientos=[];" class="btn btn-light-primary font-weight-bold mt-3">Limpiar</button>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label >Nombre</label>
                                <input type="text" class="form-control" maxlength="25" v-model="form.name" >
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label >Apellido</label>
                                <input type="text" class="form-control" maxlength="25" v-model="form.last_name" >
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label >Correo electrónico</label>
                                <input type="email" class="form-control" maxlength="30" v-model="form.email" :disabled="action=='edit'">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label >Contraseña</label>
                                <input type="password" class="form-control" maxlength="14" v-model="form.password" >
                            </div>
                        </div>

                    </div>
                </div>                    
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal" @click="clearForm()">Cerrar</button>
                <button type="button" class="btn btn-light-warning font-weight-bold" @click="clearForm()" v-if="action == 'create' && !loading">Limpiar</button>
                <button type="button" class="btn btn-primary font-weight-bold"  @click="store()" v-if="action == 'create' && !loading">Crear</button>
                <button type="button" class="btn btn-primary font-weight-bold"  @click="update()" v-if="action == 'edit' && !loading">Actualizar</button>
                <button type="button" class="btn btn-primary font-weight-bold"  @click="suspend()" v-if="action == 'suspend' && !loading">Eliminar</button>
                <div class="spinner spinner-primary ml-1 mr-13 mt-2" v-if="loading"></div>
            </div>
        </div>
    </div>
</div>
<!-- Modal-->
<div class="modal fade marketModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">@{{ modalTitle }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="municipio">Municipio</label>
                                <select class="form-control" v-model="selectedMunicipio" id="municipio" :disabled="readonlyMunicipio">
                                    <option value="">Seleccione</option>
                                    <option :value="municipio.id" v-for="municipio in municipios">@{{ municipio.nombre }}</option>
                                </select>
                                <small class="text-danger" v-if="errors.hasOwnProperty('municipio')">@{{ errors['municipio'][0] }}</small>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="cedula">Cédula</label>
                                <input type="tel" class="form-control" id="cedula" v-model="cedula" :disabled="readonlyCedula" maxlength="8" @keypress="isNumber($event)">
                                <small class="text-danger" v-if="errors.hasOwnProperty('cedula')">@{{ errors['cedula'][0] }}</small>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="nombre">Nombre</label>
                                <input type="text" class="form-control" id="nombre" v-model="nombre">
                                <small class="text-danger" v-if="errors.hasOwnProperty('nombre')">@{{ errors['nombre'][0] }}</small>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="apellido">Apellido</label>
                                <input type="text" class="form-control" id="apellido" v-model="apellido">
                                <small class="text-danger" v-if="errors.hasOwnProperty('apellido')">@{{ errors['apellido'][0] }}</small>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="telefono">Teléfono</label>
                                <input type="tel" class="form-control" id="telefono" v-model="telefono" maxlength="11" @keypress="isNumber($event)">
                                <small class="text-danger" v-if="errors.hasOwnProperty('cedula')">@{{ errors['telefono'][0] }}</small>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="rol">Rol</label>
                                <select class="form-control" v-model="rol">
                                    <option value="jefe de sala">Jefe de sala</option>
                                    <option value="soporte it">Soporte IT</option>
                                    <option value="operador de punto rojo">Operador de punto rojo</option>
                                    <option value="operador">Operador</option>
                                    <option value="operador centro de votacion">Operador centro de votación</option>
                                </select>
                                <small class="text-danger" v-if="errors.hasOwnProperty('rol')">@{{ errors['rol'][0] }}</small>
                            </div>
                        </div>
                
                        
                
                    </div>
                </div>                    
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-light-warning font-weight-bold" @click="create()" v-if="action == 'create' && !storeLoader">Limpiar</button>
                <button type="button" class="btn btn-primary font-weight-bold"  @click="store()" v-if="action == 'create' && !storeLoader">Crear</button>
                <button type="button" class="btn btn-primary font-weight-bold"  @click="update()" v-if="action == 'edit' && !updateLoader">Actualizar</button>
                <div class="spinner spinner-primary ml-1 mr-13 mt-2" v-if="storeLoader"></div>
                <div class="spinner spinner-primary ml-1 mr-13 mt-2" v-if="updateLoader"></div>
            </div>
        </div>
    </div>
</div>
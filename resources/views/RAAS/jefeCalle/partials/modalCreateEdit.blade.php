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

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="cedula">Cédula Jefe</label>
                                <div class="d-flex">
                                    <div>
                                        <input type="tel" class="form-control" id="cedula" v-model="cedula_jefe"  maxlength="8" @keypress="isNumber($event)">
                                        <small class="text-danger" v-if="cedula_jefe_error">@{{ cedula_jefe_error }}</small>
                                    </div>
                                    <div >
                                        <button class="btn btn-primary" @click="obtenerJefe()" v-if="!loading">
                                            <i class="fas fa-search"></i>
                                        </button>
                                        <div class="spinner spinner-primary ml-1 mr-13 mt-5" v-if="loading"></div>
                                    </div>      
                                </div>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="nombre">Nombre Jefe</label>
                                <input type="text" class="form-control" v-if="form.personal_caraterizacion" v-model="form.personal_caraterizacion.full_name" readonly>
                                <input type="text" class="form-control" v-else readonly>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="calle">Parroquia</label>
                                <select class="form-control" v-model="form.parroquia_id" @change="getComunidades();" :disabled="entityId">
                                    <option value="0">Seleccione</option>
                                    <option :value="parroquia.id" v-for="parroquia in parroquias">@{{ parroquia.nombre }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="calle">Comunidad</label>
                                <select class="form-control" v-model="form.comunidad_id" v-if="comunidades.length>0" @change="obtenerCalles()" :disabled="entityId">
                                    <option value="0">Seleccione</option>
                                    <option :value="comunidad.id" v-for="comunidad in comunidades">@{{ comunidad.nombre }}</option>
                                </select>
                                <select class="form-control" v-else disabled>
                                    <option value="" selected>Seleccione</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="calle">Calle</label>
                                <select class="form-control" v-model="form.calle_id" v-if="calles.length>0" :disabled="entityId">
                                    <option value="0">Seleccione</option>
                                    <option :value="calle.id" v-for="calle in calles">@{{ calle.nombre }}</option>
                                </select>
                                <select class="form-control" v-else disabled>
                                    <option value="" selected>Seleccione</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label >Teléfono principal</label>
                                <input type="text" class="form-control" maxlength="11" v-if="form.personal_caraterizacion" v-model="form.telefono_principal" @keypress="isNumber($event)">
                                <input type="tel" class="form-control" v-else disabled>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="telefonoSecundario">Teléfono secundario</label>
                                <input type="text" class="form-control" maxlength="11" v-if="form.personal_caraterizacion" v-model="form.telefono_secundario" @keypress="isNumber($event)">
                                <input type="tel" class="form-control" v-else disabled>
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
                <button type="button" class="btn btn-primary font-weight-bold"  @click="suspend()" v-if="action == 'suspend' && !loading">Suspender</button>
                <div class="spinner spinner-primary ml-1 mr-13 mt-2" v-if="loading"></div>
            </div>
        </div>
    </div>
</div>
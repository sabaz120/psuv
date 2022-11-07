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
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="municipio">Municipio</label>
                                <select class="form-control" v-model="selectedMunicipio" id="municipio" @change="getParroquias()" :disabled="( action === 'edit' || authMunicipio != '')">
                                    <option value="">Seleccione</option>
                                    <option :value="municipio.id" v-for="municipio in municipios">@{{ municipio.nombre }}</option>
                                </select>
          
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="parroquia">Parroquia</label>
                                <select class="form-control" v-model="selectedParroquia" id="parroquia" @change="getCentroVotacion()" :disabled="action === 'edit'">
                                    <option value="">Seleccione</option>
                                    <option :value="parroquia.id" v-for="parroquia in parroquias">@{{ parroquia.nombre }}</option>
                                </select>
                                <small class="text-danger" v-if="errors.hasOwnProperty('parroquia_id')">@{{ errors['parroquia_id'][0] }}</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="centroVotacion">UBCH</label>
                                <select class="form-control" v-model="selectedCentroVotacion" id="centroVotacion" :disabled="action === 'edit'">
                                    <option value="">Seleccione</option>
                                    <option :value="centroVotacion.id" v-for="centroVotacion in centroVotaciones">@{{ centroVotacion.nombre }}</option>
                                </select>
                                <small  class="text-danger" v-if="errors.hasOwnProperty('centro_votacion_id')">@{{ errors['centro_votacion_id'][0] }}</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="comunidad">Nombre comunidad</label>
                                <input v-model="nombre" class="form-control">
                                <small class="text-danger" v-if="errors.hasOwnProperty('nombre')">@{{ errors['nombre'][0] }}</small>
                            </div>
                        </div>
                
                        
                
                    </div>
                </div>                    
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary font-weight-bold"  @click="store()" v-if="action == 'create' && !storeLoader">Crear</button>
                <button type="button" class="btn btn-primary font-weight-bold"  @click="update()" v-if="action == 'edit' && !updateLoader">Actualizar</button>
                
                <div class="spinner spinner-primary ml-1 mr-13 mt-2" v-if="storeLoader"></div>
                <div class="spinner spinner-primary ml-1 mr-13 mt-2" v-if="updateLoader"></div>
               
            </div>
        </div>
    </div>
</div>
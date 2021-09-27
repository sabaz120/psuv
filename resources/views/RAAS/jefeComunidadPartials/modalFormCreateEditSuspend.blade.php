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
                                <label for="cedula">Cédula Jefe UBCH</label>
                                <div class="d-flex">
                                    <div>
                                        <input type="tel" class="form-control" id="cedula" v-model="cedulaJefeUBCH" :readonly="readonlyJefeCedula">
                                        <small class="text-danger" v-if="errors.hasOwnProperty('cedulaJefe')">@{{ errors['cedulaJefe'][0] }}</small>
                                    </div>
                                    <div >
                                        <button class="btn btn-primary" @click="searchJefeCedula()" v-if="!cedulaJefeSearching">
                                            <i class="fas fa-search"></i>
                                        </button>
                                        <div class="spinner spinner-primary ml-1 mr-13 mt-5" v-if="cedulaJefeSearching"></div>
                                    </div>      
                                </div>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="nombre">Nombre Jefe UBCH</label>
                                <input type="text" class="form-control" id="nombre" v-model="nombreJefeUBCH" readonly>
                            </div>
                        </div>


                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="cedula">Cédula</label>
                                <div class="d-flex">
                                    <div>
                                        <input type="tel" class="form-control" id="cedula" v-model="cedula" :readonly="readonlyCedula">
                                        <small class="text-danger" v-if="errors.hasOwnProperty('cedula')">@{{ errors['cedula'][0] }}</small>
                                    </div>
                                    <div >
                                        <button class="btn btn-primary" @click="searchCedula()" v-if="!cedulaSearching">
                                            <i class="fas fa-search"></i>
                                        </button>
                                        <div class="spinner spinner-primary ml-1 mr-13 mt-5" v-if="cedulaSearching"></div>
                                    </div>      
                                </div>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="nombre">Nombre Jefe</label>
                                <input type="text" class="form-control" id="nombre" v-model="nombre" readonly>
                                <small v-if="errors.hasOwnProperty('nombre')">@{{ errors['nombre'][0] }}</small>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="comunidad">Comunidad</label>
                                <select class="form-control" v-model="selectedComunidad" id="comunidad">
                                    <option value="">Seleccione</option>
                                    <option :value="comunidad.id" v-for="comunidad in comunidades">@{{ comunidad.nombre }}</option>
                                </select>
                                <small class="text-danger" v-if="errors.hasOwnProperty('comunidad')">@{{ errors['comunidad'][0] }}</small>
                            </div>
                        </div>
                        

                        

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="tipoVoto">Tipo de voto</label>
                                <select class="form-control" v-model="tipoVoto">
                                    <option value="duro">Duro</option>
                                    <option value="blando">Blando</option>
                                </select>
                                <small  class="text-danger" v-if="errors.hasOwnProperty('tipo_voto')">@{{ errors['tipo_voto'][0] }}</small>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="telefonoPrincipal">Teléfono principal</label>
                                <input type="tel" class="form-control" id="telefonoPrincipal" v-model="telefonoPrincipal">
                                <small  class="text-danger" v-if="errors.hasOwnProperty('telefono_principal')">@{{ errors['telefono_principal'][0] }}</small>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="telefonoSecundario">Teléfono secundario</label>
                                <input type="tel" class="form-control" id="telefonoSecundario" v-model="telefonoSecundario">
                                <small  class="text-danger" v-if="errors.hasOwnProperty('telefono_secundario')">@{{ errors['telefono_secundario'][0] }}</small>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="partidoPolitico">Partido político</label>
                                <select class="form-control" v-model="selectedPartidoPolitico" id="partidoPolitico">
                                    <option value="">Seleccione</option>
                                    <option :value="partidoPolitico.id" v-for="partidoPolitico in partidosPoliticos">@{{ partidoPolitico.nombre }}</option>
                                </select>
                                <small  class="text-danger" v-if="errors.hasOwnProperty('partido_politico_id')">@{{ errors['partido_politico_id'][0] }}</small>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="movilizacion">Movilizaciones</label>
                                <select class="form-control" v-model="selectedMovilizacion" id="movilizacion">
                                    <option value="">Seleccione</option>
                                    <option :value="movilizacion.id" v-for="movilizacion in movilizaciones">@{{ movilizacion.nombre }}</option>
                                </select>
                                <small class="text-danger" v-if="errors.hasOwnProperty('movilizacion_id')">@{{ errors['movilizacion_id'][0] }}</small>
                            </div>
                        </div>
                
                        
                
                    </div>
                </div>                    
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-light-warning font-weight-bold" @click="clearForm()" v-if="action == 'create' && !storeLoader">Limpiar</button>
                <button type="button" class="btn btn-primary font-weight-bold"  @click="store()" v-if="action == 'create' && !storeLoader">Crear</button>
                <button type="button" class="btn btn-primary font-weight-bold"  @click="update()" v-if="action == 'edit' && !updateLoader">Actualizar</button>
                <button type="button" class="btn btn-primary font-weight-bold"  @click="remove()" v-if="action == 'suspend' && !suspendLoader">Suspender</button>
                <div class="spinner spinner-primary ml-1 mr-13 mt-2" v-if="storeLoader"></div>
                <div class="spinner spinner-primary ml-1 mr-13 mt-2" v-if="updateLoader"></div>
                <div class="spinner spinner-primary ml-1 mr-13 mt-2" v-if="suspendLoader"></div>
            </div>
        </div>
    </div>
</div>
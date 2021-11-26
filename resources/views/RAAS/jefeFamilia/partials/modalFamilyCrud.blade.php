<!-- Modal-->
<div v-if="entity" class="modal fade familyModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Núcleo Familiar de: @{{entity.personal_caracterizacion.full_name}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="clearForm()">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="cedula">Cédula del familiar</label>
                                <div class="d-flex">
                                    <div>
                                        <input type="tel" class="form-control" v-model="cedula_familiar" maxlength="8" @keypress="isNumber($event)" :readonly="entityFamily" >
                                        <small class="text-danger" v-if="cedula_familiar_error">@{{ cedula_familiar_error }}</small>
                                    </div>
                                    <div >
                                        <button class="btn btn-primary" @click="obtenerFamiliar()" v-if="!loading" :disabled="entityFamily">
                                            <i class="fas fa-search"></i>
                                        </button>
                                        <div class="spinner spinner-primary ml-1 mr-13 mt-5" v-if="loading"></div>
                                    </div>      
                                </div>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="nombre">Nombre</label>
                                <input type="text" class="form-control" v-if="familyForm.personal_caracterizacion" v-model="familyForm.personal_caracterizacion.full_name" readonly>
                                <input type="text" class="form-control" v-else readonly>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="tipoVoto">Tipo de voto</label>
                                <select class="form-control" v-model="familyForm.tipo_voto" v-show="familyForm.personal_caracterizacion">
                                    <option value="" selected>Seleccione</option>
                                    <option v-for="tipoVoto in tipoDeVotos" :value="tipoVoto.toLowerCase()">@{{tipoVoto}}</option>
                                </select>
                                <select class="form-control" v-show="!familyForm.personal_caracterizacion" disabled>
                                    <option value="" selected>Seleccione</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label >Teléfono principal</label>
                                <input type="text" class="form-control" maxlength="11" v-if="familyForm.personal_caracterizacion" v-model="familyForm.telefono_principal"  @keypress="isNumber($event)">
                                <input type="tel" class="form-control" v-else disabled>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="telefonoSecundario">Teléfono secundario</label>
                                <input type="text" class="form-control" maxlength="11" v-if="familyForm.personal_caracterizacion" v-model="familyForm.telefono_secundario"  @keypress="isNumber($event)">
                                <input type="tel" class="form-control" v-else disabled>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="partidoPolitico">Partido político</label>
                                <select class="form-control" v-model="familyForm.partido_politico_id" v-show="familyForm.personal_caracterizacion" >
                                    <option value="" selected>Seleccione</option>
                                    <option :value="partidoPolitico.id" v-for="partidoPolitico in partidosPoliticos">@{{ partidoPolitico.nombre }}</option>
                                </select>
                                <select class="form-control" v-show="!familyForm.personal_caracterizacion" disabled>
                                    <option value="" selected>Seleccione</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="movilizacion">Tipo de movilización</label>
                                <select class="form-control" v-model="familyForm.movilizacion_id" v-show="familyForm.personal_caracterizacion">
                                    <option value="" selected>Seleccione</option>
                                    <option :value="movilizacion.id" v-for="movilizacion in tiposDeMovilizacion">@{{ movilizacion.nombre }}</option>
                                </select>
                                <select class="form-control" v-show="!familyForm.personal_caracterizacion" disabled>
                                    <option value="" selected>Seleccione</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12 text-center">

                            <button type="button" class="btn btn-primary font-weight-bold"  @click="storeFamily()" v-if="actionFamily == 'create' && !loading">Crear</button>
                            <button type="button" class="btn btn-light-primary font-weight-bold"  @click="clearFormFamily()" v-if="actionFamily == 'edit' && !loading">Cancelar edición</button>
                            <button type="button" class="btn btn-primary font-weight-bold"  @click="updateFamily()" v-if="actionFamily == 'edit' && !loading">Actualizar</button>
                        </div>

                        <div class="col-md-12 my-5">
                            <span class="text-center">
                                <hr>
                                <h3>Lista de familiares</h3>
                            </span>
                                  <!--begin: Datatable-->
                    <div class="datatable datatable-bordered datatable-head-custom datatable-default datatable-primary datatable-loaded" >
                        
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>

                                        <th class="datatable-cell datatable-cell-sort">
                                            <span>Jefe de Familia</span>
                                        </th>

                                        <th class="datatable-cell datatable-cell-sort">
                                            <span>Teléfono</span>
                                        </th>

                                        <th class="datatable-cell datatable-cell-sort">
                                            <span>Tipo voto</span>
                                        </th>

                                        <th class="datatable-cell datatable-cell-sort">
                                            <span>¿Movilización?</span>
                                        </th>

                                        <th class="datatable-cell datatable-cell-sort">
                                            <span>Acción</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="family in families">
                                        <td>@{{ family.full_name }}</td>
                                        <td>@{{ family.telefono_principal }}</td>
                                        <td>@{{ family.tipo_voto }}</td>
                                        <td>@{{ family.movilizacion.nombre }}</td>
                                        <td>
                                            <button title="Editar" class="btn btn-success" @click="editFamily(family)">
                                                <i class="far fa-edit"></i>
                                            </button>
                                            <button title="Suspender" class="btn btn-secondary" @click="suspendFamily(family)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr v-if="families.length==0">
                                        <td colspan="5">No posee personas en su núcleo familiar.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                       
                    </div>
                    <!--end: Datatable-->

                        </div>
                
                    </div>
                </div>                    
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal" @click="clearFormFamily();entity=null;">Cerrar</button>
                <button type="button" class="btn btn-primary font-weight-bold"  @click="suspendFamily()" v-if="action == 'suspend' && !loading">Suspender</button>
                <div class="spinner spinner-primary ml-1 mr-13 mt-2" v-if="loading"></div>
            </div>
        </div>
    </div>
</div>
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
                                <label >Nombre</label>
                                <input type="text" class="form-control" maxlength="25" v-model="form.nombre" >
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label >Apellido</label>
                                <input type="text" class="form-control" maxlength="25" v-model="form.apellido" >
                            </div>
                        </div>
                        
                        <div class="col-md-12 text-center">
                            <div class="form-group">
                                <label >Foto</label>
                                <br>
                                <img :src="form.foto" v-if="hasImage" class="img-responsive my-3" style="max-width:100%;">
                                <image-uploader
                                    :debug="1"
                                    :maxHeight="200"
                                    :maxWidth="200"
                                    :quality="0.7"
                                    :autoRotate=true
                                    outputFormat="verbose"
                                    :preview=false
                                    :className="['fileinput', { 'fileinput--loaded' : hasImage }]"
                                    capture="environment"
                                    accept="image/*"
                                    doNotResize="['gif', 'svg']"
                                    @input="setImage"
                                    @onUpload="startImageResize"
                                    @onComplete="endImageResize"
                                ></image-uploader>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="calle">Cargo</label>
                                <select class="form-control" v-model="form.cargo_eleccion" >
                                    <option value="">Seleccione</option>
                                    <option :value="cargo" v-for="cargo in cargos">@{{cargo }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="calle">Municipio</label>
                                <select class="form-control" v-model="form.municipio_id" v-if="municipios.length>0">
                                    <option value="">Seleccione</option>
                                    <option :value="municipio.id" v-for="municipio in municipios">@{{ municipio.nombre }}</option>
                                </select>
                                <select class="form-control" v-else disabled>
                                    <option value="" selected>Seleccione</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group text-center">
                                <label for="calle">Partidos</label>
                                <select class="form-control" v-model="form.partidos_politicos" v-show="partidosPoliticos.length>0" multiple>
                                    <option :value="partido.id" v-for="partido in partidosPoliticos">@{{ partido.nombre }}</option>
                                </select>
                                <select class="form-control" v-show="partidosPoliticos.length==0" disabled>
                                    <option value="" selected>Seleccione</option>
                                </select>
                                <button type="buttton" @click="form.partidos_politicos=[];" class="btn btn-light-primary font-weight-bold mt-3">Limpiar</button>
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
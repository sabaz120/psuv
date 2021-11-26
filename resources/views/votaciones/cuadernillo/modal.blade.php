<!-- Modal-->
<div class="modal fade REPmodal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Enviar correo electrónico</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="clearForm()">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">

                        <div class="col-md-12">
                            <p class="font-weight-bold">Debido a la gran cantidad de registros el proceso de exportación tardará más de lo normal. Así que cuando el cuadernillo esté disponible será enviado al correo electrónico introducido a continuación</p>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="cedula">Correo electrónico </label>
                             
                                <div>
                                    <input type="tel" class="form-control" id="email" v-model="email">
                                </div>   
                               
                            </div>
                        </div>
                
                    </div>
                </div>                    
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-light-success font-weight-bold" v-if="!emailLoading" @click="enviarCorreo()">Enviar</button>
                <div class="spinner spinner-primary ml-1 mr-13 mt-2" v-if="emailLoading"></div>
            </div>
        </div>
    </div>
</div>
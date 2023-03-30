<template>
    <div class="card">
        <div class="card-header">
            Agregar tipo de dispostivo
        </div>
        <div class="card-body">
            <div class="container">                
                <form @submit.prevent="validateBeforeSubmit" class="row mt-0">
                    <div class="col-xs-12 col-md-12 margin-none" v-show="validated.status == false">
                        <div class="alert alert-danger animated fadeIn" role="alert">{{ validated.message }}</div>
                    </div> 

                    <div class="col-xs-12 col-md-12">
                        <div :class="{'form-group': true, 'ig-danger': errors.has('type')}">
                        <label for="exampleInputEmail1">Tipo:</label>
                        
                        <input type="text" name="type" class="form-control"
                            v-model="type"
                            placeholder="Típo"
                            data-vv-as="tipo"
                            v-validate="'required|alpha'"
                        >
                        <span rol="message" v-show="errors.has('type')" class="message-alert">{{ errors.first('type') }}</span>
                        </div>
                    </div>

                    <div class="col-xs-12 col-md-12">
                        <div :class="{'form-group': true, 'ig-danger': errors.has('description')}">
                        <label for="exampleInputEmail1">Descripción:</label>
                        
                        <textarea name="description" class="form-control"
                            placeholder="Descripción"
                            v-model="description"
                            data-vv-as="descripción"
                            v-validate="'required|alpha'"></textarea>

                        <span rol="message" v-show="errors.has('description')" class="message-alert">{{ errors.first('description') }}</span>
                        </div>
                    </div>  
                    
                    <input type="submit" class="hidden" id="submit-form-add-type">
                </form>
            </div>
        </div>
        <div class="card-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            <label type="button" class="btn btn-primary margin-none" for="submit-form-add-type">Guardar</label>
        </div>
    </div>
</template>

<script>
  import EventBus from "../../event-bus.js";

  export default {
        data(){
            return {
                type: null,
                description: null,

                validated: {
                    status: null,
                    message: null,
                }
            }
        },
        methods: {
            validateBeforeSubmit(){
                this.$validator.validateAll().then((result) => {
                    if (result) {
                        this.save();
                        this.validated.status = null;
                        this.validated.message = null;
                    }else{
                        this.validated.status = false;
                        this.validated.message = "Error: Varifica los campos";
                    }
                });
            },

            save(){
                axios.post("http://localhost/Laravel/TI/public/types", {
                    type: this.type,
                    description: this.description,
                }).then((response) => {
                    // Generamos evento
                    EventBus.$emit("type-add", {
                        "store": true
                    });

                    this.validated.message = null;
                    this.validated.message = null;
                }).catch((error) => {
                    this.validated.status = false;
                    this.validated.message = error;
                });
            }
        }
    }
</script>

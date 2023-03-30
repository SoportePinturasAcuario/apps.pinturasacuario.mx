<style>
    .image{
        width: 125px;
        height: 125px;
    }
</style>

<template>
    <div class="row">
        <div class="card">
            <div class="card-header">
                Agregar equipo
            </div>
            <div class="card-body">
                <div class="container">                
                    <form @submit.prevent="validateBeforeSubmit" class="row">
                        
                        <div class="col-xs-12 col-md-12 margin-none" v-show="validated.status == false">
                            <div class="alert alert-danger animated fadeIn" role="alert">{{ validated.message }}</div>
                        </div>

                        <div class="col-xs-12 col-md-12 justify-content-center">
                            <img :src="image" alt="" v-show="image != null" class="image animated bounceIn">
                        </div>

                        <div class="col-xs-12 col-md-6">
                            <div :class="{'form-group': true, 'ig-danger': errors.has('type')}">
                            <label for="exampleInputEmail1">Tipo:</label>
                            
                            <select name="type" class="form-control" v-model="type" v-validate="'required'" data-vv-as="tipo">
                                <option value="null" selected disabled>Selecciona</option>
                                <option v-bind:value="type.id" v-for="type in types">{{ type.type }}</option>
                            </select>
                            <span rol="message" v-show="errors.has('type')" class="message-alert">{{ errors.first('type') }}</span>
                            </div>
                        </div>

                        <div class="col-xs-12 col-md-6">
                            <div :class="{'form-group': true, 'ig-danger': errors.has('name')}">
                                <label for="exampleInputEmail1">Nombre:</label>
                                <input 
                                    type="text" 
                                    placeholder="Nombre" 
                                    name="name" 
                                    v-model="name" 
                                    class="form-control"
                                    data-vv-as="nombre"
                                    v-validate="'required|alpha_dash'"
                                />
                                <span rol="message" v-show="errors.has('name')" class="message-alert">{{ errors.first('name') }}</span>
                            </div>
                        </div>     


                        <div class="col-xs-12 col-md-6">
                            <div :class="{'form-group': true, 'ig-danger': errors.has('model')}">
                                <label >Modelo:</label>
                                <input type="text"
                                    class="form-control"
                                    placeholder="Modelo"
                                    name="model"
                                    v-model="model"
                                    data-vv-as="modelo"
                                    v-validate="'required|alpha_dash|min:5'"
                                />
                                <span rol="message" v-show="errors.has('model')" class="message-alert">{{ errors.first('model') }}</span>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-6">
                            <div :class="{'form-group': true, 'ig-danger': errors.has('brand')}">
                                <label >Marca:</label>
                                <input type="text" class="form-control" placeholder="Marca" name="brand" v-model="brand"
                                    data-vv-as="marca"
                                    v-validate="'required|alpha|min:2'"
                                />
                                <span rol="message" v-show="errors.has('brand')" class="message-alert">{{ errors.first('brand') }}</span>
                            </div>
                        </div>

                        <div class="col-xs-12 col-md-12">
                            <div :class="{'form-group': true, 'ig-danger': errors.has('serial_number')}">
                                <label >Numero de serie:</label>
                                <input type="text" class="form-control" placeholder="SN" name="serial_number" v-model="serial_number"
                                    data-vv-as="numero de serie"
                                    v-validate="'required|alpha_dash|min:6'"
                                />
                                <span rol="message" v-show="errors.has('serial_number')" class="message-alert">{{ errors.first('serial_number') }}</span>
                            </div>
                        </div>


                        <div class="col-xs-12 col-md-12">
                            <label >Imagen:</label>
                            <div :class="{'input-group': true, 'ig-danger': errors.has('serial_number')}">
                            <div class="input-group-prepend">
                                <span class="input-group-text">URL:</span>
                            </div>

                            <input type="text" class="form-control" name="image"
                                v-model="image"
                                data-vv-as="imagen"
                                v-validate="'required|url'"
                            />
                            <div class="input-group-append">
                                <!-- <span class="input-group-text">Set</span> -->
                                <label class="btn btn-primary margin-none">pegar</label>
                            </div>
                            </div>
                            <span rol="message" v-show="errors.has('image')" class="message-alert">{{ errors.first('image') }}</span>
                        </div>                    

                        <input type="submit" class="hidden" id="submit-device-add">
                    </form>
                </div>
            </div>
            <div class="card-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                <label type="button" class="btn btn-primary margin-none ml-1" for="submit-device-add">Guardar</label>
            </div>
        </div>
    </div>
</template>

<script>
  import EventBus from "../../event-bus.js";

    export default {
        data(){
            return {
                name: null,
                model: null,
                brand: null,
                serial_number: null,
                type: null,
                image: null,

                types: [],
                validated:{
                  status: null,
                  message: null
                }
            }
        },
        created(){
          this.getTypes();

          EventBus.$on("type-add", data => {
              this.getTypes();
          });   
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
            axios.post("http://localhost/Laravel/TI/public/equipos", {
              name: this.name,
              model: this.model,
              brand: this.brand,
              image: this.image,
              serial_number: this.serial_number,
              type: this.type,
            })
            .then((response) =>{

              // Generamos evento
              EventBus.$emit("devices-add", {
                "store": true
              });
            })
            .catch((error) =>{

              // Imprimimos posibles errores
              console.log(error);
            })
          },

          getTypes(){
              axios.get("http://localhost/Laravel/TI/public/types")
              .then((response) => {
                  this.types = response.data.types;
              })
              .catch((error) => {
                  console.log(error);
              });
          }          
        }
    }
</script>

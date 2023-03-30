<style>
    .image{
        width: 225px;
        height: 200px;
    }
</style>

<template>
    <div class="row">
        <div class="col-xs-12 col-md-12">
            <div class="card">
                <div class="card-header"><strong>Dispositivo: </strong><small>{{data.name}}</small></div>

                <div class="card-body">
                    <form action="" class="row" @submit.prevent="validateBeforeSubmit">
                        <div class="col-xs-12 col-md-4">
                            <div class="col-xs-12 col-md-12 justify-content-center">
                                <img :src="data.image" alt="" class="image">
                            </div>

                            <div class="col-xs-12 col-md-12 animated">
                                <div class="col-xs-12"><hr class="mb-1">
                                    <small>Acciones:</small>
                                </div>
                                <div class="col-xs-12 justify-content-center">
                                    <label for="" class="btn btn-sm btn-success" v-if="data.user != ''">Asignar</label>
                                    <label v-else class="btn btn-sm btn-primary ml-1">Re asignar</label>
                                    <label class="btn btn-sm btn-primary ml-1">Generar formato</label>
                                    <label class="btn btn-sm btn-danger ml-1">Eliminar</label>
                                </div>
                            </div>    
                        </div>

                        <div class="col-xs-12 col-md-8 col-lg-8">
                            <div class="col-xs-12 justify-content-center">
                                <div class="col-xs-12 col-md-3">
                                    <div :class="{'form-group': true, 'ig-danger': errors.has('name')}">
                                        <label >Nombre:</label>
                                        <input type="text"
                                        class="form-control"
                                        placeholder="Nombre"
                                        name="name"
                                        v-model="data.name"
                                        data-vv-as="nombre"
                                        v-validate="'required|alpha_dash|min:5'"
                                        :disabled="(edit) ? false : true" 

                                    
                                        />
                                        <span rol="message" v-show="errors.has('name')" class="message-alert">{{ errors.first('name') }}</span>
                                    </div>
                                </div>

                                <div class="col-xs-12 col-md-3">
                                    <div :class="{'form-group': true, 'ig-danger': errors.has('brand')}">
                                        <label >Marca:</label>
                                        <input type="text"
                                        class="form-control"
                                        placeholder="Marca"
                                        name="brand"
                                        v-model="data.brand"
                                        data-vv-as="marca"
                                        v-validate="'required|alpha_dash|min:2'"
                                        :disabled="(edit) ? false : true"
                                        />
                                        <span rol="message" v-show="errors.has('brand')" class="message-alert">{{ errors.first('brand') }}</span>
                                    </div>
                                </div>

                                <div class="col-xs-12 col-md-3">
                                    <div :class="{'form-group': true, 'ig-danger': errors.has('model')}">
                                        <label >Modelo:</label>
                                        <input type="text"
                                        class="form-control"
                                        placeholder="Modelo"
                                        name="model"
                                        v-model="data.model"
                                        data-vv-as="modelo"
                                        v-validate="'required|alpha_dash|min:5'"
                                        :disabled="(edit) ? false : true"
                                         />
                                        <span rol="message" v-show="errors.has('model')" class="message-alert">{{ errors.first('model') }}</span>
                                    </div>
                                </div>

                                <div class="col-xs-12 col-md-3">
                                    <div :class="{'form-group': true, 'ig-danger': errors.has('type')}">
                                        <label for="exampleInputEmail1">Típo:</label>

                                        <select name="type" class="form-control" v-validate="'required'" data-vv-as="tipo0"
                                            :disabled="(edit) ? false : true"
                                        >
                                            <option :value="type.id" v-for="type in types">{{ type.type }}</option>
                                        </select>
                                        <span rol="message" v-show="errors.has('type')" class="message-alert">{{ errors.first('type') }}</span>
                                    </div>
                                </div>

                            </div>

                            <div class="col-xs-12 justify-content-center">
                                <div class="col-xs-12 col-md-4">
                                    <div :class="{'form-group': true, 'ig-danger': errors.has('serial_number')}">
                                        <label >SN:</label>
                                        <input type="text"
                                        class="form-control"
                                        placeholder="SN"
                                        name="serial_number"
                                        v-model="data.serial_number"
                                        data-vv-as="sn"
                                        v-validate="'required|alpha_dash|min:5'"
                                        :disabled="(edit) ? false : true"
                                        />
                                        <span rol="message" v-show="errors.has('serial_number')" class="message-alert">{{ errors.first('serial_number') }}</span>
                                    </div>
                                </div>

                                <div class="col-xs-12 col-md-4">
                                    <div :class="{'form-group': true, 'ig-danger': errors.has('user')}">
                                        <label >Usuario:</label>
                                        <!-- <v-select label="type" :options="types" :value="id" v-model="data.type" -->
                                            <!-- data-vv-as="usuario" -->
                                            <!-- v-validate="'required'" -->
                                            <!-- :disabled="(edit) ? false : true" -->
                                        <!-- ></v-select> -->
                                        <span rol="message" v-show="errors.has('user')" class="message-alert">{{ errors.first('user') }}</span>
                                    </div>                                  
                                </div> 

                                <div class="col-xs-12 col-md-4">
                                    <div :class="{'form-group': true, 'ig-danger': errors.has('departamento')}">
                                        <label >Departamento:</label>
                                        <input type="text"
                                        class="form-control"
                                        placeholder="Departamento"
                                        name="departamento"
                                        v-model="data.departamento"
                                        data-vv-as="sn"
                                        v-validate="'required|alpha_dash|min:5'"
                                        readonly
                                        disabled
                                        />
                                        <span rol="message" v-show="errors.has('departamento')" class="message-alert">{{ errors.first('departamento') }}</span>
                                    </div>
                                </div>
                            </div>    

                            <div class="col-xs-12 col-md-12 justify-content-center" v-show="interface.details" style="padding: 0;">
                                <div class="col-xs-12 col-md-4">
                                    <div :class="{'form-group': true, 'ig-danger': errors.has('so')}">
                                        <label >Sistema Opertivo:</label>
                                        <input type="text"
                                        class="form-control"
                                        placeholder="Windows X"
                                        name="so"
                                        v-model="data.so"
                                        data-vv-as="sistema operativo"
                                        v-validate="'alpha'"
                                        :disabled="(edit) ? false : true"
                                        />
                                        <span rol="message" v-show="errors.has('so')" class="message-alert">{{ errors.first('so') }}</span>
                                    </div>
                                </div>

                                <div class="col-xs-12 col-md-4">
                                    <div :class="{'form-group': true, 'ig-danger': errors.has('ram')}">
                                        <label >Memoria RAM:</label>
                                        <input type="text"
                                        class="form-control"
                                        placeholder="4"
                                        name="ram"
                                        v-model="data.ram"
                                        data-vv-as="ram"
                                        v-validate="'alpha'"
                                        :disabled="(edit) ? false : true"
                                        />
                                        <span rol="message" v-show="errors.has('ram')" class="message-alert">{{ errors.first('ram') }}</span>
                                    </div>
                                </div>

                                <div class="col-xs-12 col-md-4">
                                    <div :class="{'form-group': true, 'ig-danger': errors.has('cpu')}">
                                        <label >Procesador:</label>
                                        <input type="text"
                                        class="form-control"
                                        placeholder="cpu"
                                        name="cpu"
                                        v-model="data.cpu"
                                        data-vv-as="procesador"
                                        v-validate="'alpha'"
                                        :disabled="(edit) ? false : true"
                                        />
                                        <span rol="message" v-show="errors.has('cpu')" class="message-alert">{{ errors.first('cpu') }}</span>
                                    </div>
                                </div>                                                                                    
                                
                            </div> 

                            <div class="col-xs-12 col-md-12 justify-content-center" v-show="interface.details" style="padding: 0;">
                                <div class="col-xs-12 col-md-4">
                                    <div :class="{'form-group': true, 'ig-danger': errors.has('ip_address')}">
                                        <label >Dirección IP:</label>
                                        <input type="text"
                                        class="form-control"
                                        placeholder="000.000.000.000"
                                        name="ip_address"
                                        v-model="data.ip_address"
                                        data-vv-as="dirección ip"
                                        v-validate="'ip'"
                                        :disabled="(edit) ? false : true"
                                        />
                                        <span rol="message" v-if="errors.has('ip_address')" class="message-alert">{{ errors.first('ip_address') }}</span>
                                    </div>
                                </div>                                                                                  
                                
                            </div>                                                                        
                 

                            <div class="col-xs-12 col-md-12 justify-content-center" v-if="!edit">
                                <small style="text-decoration: underline; cursor: pointer;" @click="interface.details = !interface.details">
                                    Ver <span v-if="!interface.details">mas</span><span v-if="interface.details">menos  </span>
                                </small>
                            </div>
                        </div>
                        <input type="submit" class="hidden" id="submit-form-device-update">
                    </form>

                    <!-- <div>{{types}}</div> -->
                </div>
                <div class="card-footer">
                    <label for="" :class="{'btn': true, 'btn-primary': true}" v-if="!edit" @click="(edit = !edit, interface.details = true)">Editar</label>

                    <label for="" class="btn btn-primary ml-1" v-if="edit" @click="(edit = !edit, interface.details = false)">Cancelar</label>
                    <label for="submit-form-device-update" class="btn btn-success ml-1" v-if="edit">Guardar</label>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import EventBus from "../../event-bus.js";

    export default {
        props: ["indice"],

        data(){
            return {
                data: [],

                types: [],

                edit: false,

                interface: {
                    details: false,
                },


                validated:{
                    status: false,
                    message: null,
                }
            }
        },
        created(){
            this.show();

            this.getTypes();            
        },
        methods: {
            show(){
                axios.get("http://localhost/Laravel/TI/public/equipos/" + this.indice)
                .then((response) => {
                    this.data = response.data.device;
                })
                .catch((error) => {
                    console.log(error);
                });
            },

            getTypes(){
                axios.get("http://localhost/Laravel/TI/public/types")
                .then((response) => {
                    this.types = response.data.types;
                })
                .catch((error) => {
                    console.log(error);
                });
            },

            validateBeforeSubmit(){
                // console.log("llega");
                this.$validator.validateAll().then((result) => {
                  if (result) {
                    this.save();
                  }else{
                    console.log("Valida los campos");
                  }
                });                
            },

          save(){
            axios.put("http://192.168.1.167/TI/public/equipos", {
              data: "ok"
            })
            .then((response) =>{

              // Generamos evento
              EventBus.$emit("devices-updated", {
                "updated": true
              });

              this.interface.details = false;

            })
            .catch((error) =>{

              // Imprimimos posibles errores
              console.log(error);
            })
          },
        }
    }
</script>


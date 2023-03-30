<template>
    <div class="card">
        <div class="card-header">
            Agregar usuario
        </div>
        <div class="card-body">
            <div class="container">                
                <form @submit.prevent="validateBeforeSubmit" class="row mt-0">
                    <div class="col-xs-12 col-md-12 margin-none" v-show="validated.status == false">
                        <div class="alert alert-danger animated fadeIn" role="alert">{{ validated.message }}</div>
                    </div> 

                    <div class="col-xs-12 col-md-6">
                        <div :class="{'form-group': true, 'ig-danger': errors.has('name')}">
                        <label>Nombre:</label>
                        <input type="text" name="name" class="form-control"
                            v-model="name"
                            placeholder="Nombre"
                            data-vv-as="nombre"
                            v-validate="'required|alpha'"
                        >
                        <span rol="message" v-show="errors.has('name')" class="message-alert">{{ errors.first('name') }}</span>
                        </div>
                    </div>

                    <div class="col-xs-12 col-md-6">
                        <div :class="{'form-group': true, 'ig-danger': errors.has('role')}">
                        <label>Rol:</label>

                        <select name="role" class="form-control"
                            v-model="role"
                            data-vv-as="rol"
                            v-validate="'required'"
                        >
                            <option :value="role.id" v-for="role in roles">{{ role.role }}</option>
                        </select>

                        <span role="message" v-show="errors.has('role')" class="message-alert">{{ errors.first('role') }}</span>
                        </div>
                    </div>                     



                    <div class="col-xs-12 col-md-6">
                        <div :class="{'form-group': true, 'ig-danger': errors.has('department')}">
                        <label>Departamento:</label>

                        <select name="department" class="form-control" 
                            v-model="department" 
                            v-validate="'required'" 
                            data-vv-as="departamento"
                        >
                            <option :value="department.id" v-for="department in departments">{{ department.department }}</option>
                        </select>

                        <span rol="message" v-show="errors.has('department')" class="message-alert">{{ errors.first('department') }}</span>
                        </div>
                    </div>  

                    <div class="col-xs-12 col-md-6">
                        <div :class="{'form-group': true, 'ig-danger': errors.has('pst')}">
                        <label>Puesto:</label>
                        <input type="text" name="pst" class="form-control"
                            v-model="pst"
                            placeholder="Puesto"
                            data-vv-as="puesto"
                            v-validate="'required|alpha'"
                        >
                        <span rol="message" v-show="errors.has('pst')" class="message-alert">{{ errors.first('pst') }}</span>
                        </div>
                    </div>     
                    
                    <div class="col-xs-12 col-md-6">
                        <div :class="{'form-group': true, 'ig-danger': errors.has('password')}">
                        <label>Contraseña:</label>
                        <input type="text" name="password" class="form-control"
                            v-model="password"
                            placeholder="Contraseña"
                            data-vv-as="contraseña"
                            v-validate="'required|alpha'"
                        >
                        <span rol="message" v-show="errors.has('password')" class="message-alert">{{ errors.first('password') }}</span>
                        </div>
                    </div>                      

                    <div class="col-xs-12 col-md-6">
                        <div :class="{'form-group': true, 'ig-danger': errors.has('email')}">
                        <label>Correo electrónico:</label>
                        <input type="text" name="email" class="form-control"
                            v-model="email"
                            placeholder="Correo electrónico"
                            data-vv-as="correo"
                            v-validate="'required|email'"
                        >
                        <span rol="message" v-show="errors.has('email')" class="message-alert">{{ errors.first('email') }}</span>
                        </div>
                    </div>                                                                                                    
                    
                    <input type="submit" class="hidden" id="submit-form-add-user">
                </form>
            </div>
        </div>
        <div class="card-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            <label type="button" class="btn btn-primary margin-none" for="submit-form-add-user">Guardar</label>
        </div>
    </div>
</template>

<script>
  import EventBus from "../../event-bus.js";

    export default {
        data(){
            return {
                name: null,
                email: null,
                password: null,
                department: null,
                role: null,

                pst: null,
                roles: [],
                departments: [],

                validated: {
                    status: null,
                    message: null,
                }
            }
        },
        created(){
            this.getRoles();

            this.getDepartments();
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
                axios.post("http://localhost/Laravel/TI/public/users", {
                    name: this.name,
                    email: this.email,
                    password: this.password,
                    department: this.department,
                    role: this.role,
                }).then((response) => {
                    EventBuss.$emit("user-add", {
                        data: response.data,
                    });
                }).catch(error => {
                    console.log(error);
                });
            },

            getRoles(){
                axios.get("http://localhost/Laravel/TI/public/roles").then((response) =>{
                    this.roles = response.data.roles;
                }).catch(error => {
                    console.log(error);
                });
            },

            getDepartments(){
                axios.get("http://localhost/Laravel/TI/public/departments").then((response) => {
                    this.departments = response.data.departments;
                }).catch(error => {
                    console.log(error);
                });
            }
        }
    }
</script>

<style>

</style>

<template>
    <div class="row">
        <div class="card">
            <div class="card-header flex justify-content-space-between">
                <div>Buscador</div>
                <div>
                    <button class="btn btn-sm btn-default">-</button>
                    <button class="btn btn-sm btn-default">X</button>
                </div>
            </div>
            <div class="card-body">
                <div class="col-xs-12 col-md-12">
                     <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Criterio de busqueda" autofocus  @keyup="search" v-model="criterion">
                        <div class="input-group-append">
                            <span class="input-group-text" style="background: #fff; cursor: pointer;" @click="criterionNull">Limpiar</span>
                        </div>
                    </div>             
                </div>
        
                <div class="col-xs-12 col-md-12 mt-4">
                    <table class="table table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">Indice</th>
                                <th scope="col">Imagen</th>
                                <th scope="col">Nombre</th>
                                <th scope="col">Usuario</th>
                                <th scope="col">Modelo</th>
                                <th scope="col">Marca</th>
                                <th scope="col">Tipo</th>
                                <th scope="col">IP</th>
        
        
                                <th scope="col">Estado</th>
                                <th scope="col">Abrir</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(result, i) in results" class="animated fadeIn">
                                <th scope="col">{{i + 1}}</th>
                                <th scope="col"><img :src="result.image" alt="Imagen"></th>
                                <th scope="col">{{ result.name }}</th>
                                <th scope="col">{{ result.name }}</th>
                                <th scope="col">{{ result.model }}</th>
                                <th scope="col">{{ result.brand }}</th>
                                <th scope="col">Serie</th>
                                <th scope="col">Tipo</th>
                                <th scope="col">Activo</th>
                                <th scope="col"><a :href="'equipos/' + result.id" class="btn btn-success">Ver</a></th>
                            </tr>
        
                            <tr v-if="results.length == 0">
                                <th scope="row" colspan="9" class="animated bounceIn"><center><span style="opacity: .4">Sin resultados</span></center></th>
                            </tr>
                        </tbody>
                    </table>  
                    
                </div>
            </div>
        </div>            
    </div>
</template>

<script>
    import EventBus from "../../event-bus.js";

    export default {
        data(){
            return {
                results: [],
                devices: [],
                criterion: "",
            }
        },
        mounted() {
            this.read();
        }, 
        created(){
            EventBus.$on("devices-add", data => {
                this.read();
            });
        },
        methods: {
            read(){
                axios.get("http://localhost/Laravel/TI/public/equipos")
                .then((response) => {
                    this.devices = response.data.devices;

                    this.search();
                })
            },

            search(){
                if (this.criterion != "") {
                    this.results = this.devices.filter((device) => {
                        return device.name.match(this.criterion);
                    })
                }else{
                    this.results = this.devices; 
                }
            },

            criterionNull(){
                this.criterion = "";
                this.search();
            }
        }
    }
</script>


<style>
    table tbody tr th img{
        width: 60px;
        height: 60px;
        margin: 0;
    }
</style>

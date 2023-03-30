require('./bootstrap');

window.Vue = require('vue');


// impo vue-select
import vSelect from 'vue-select'
Vue.component('v-select', vSelect)


// Importamos VeeValidate (libreria de valdiaicones para VUE)
import VeeValidate from 'vee-validate';
const VueValidationEs = require('vee-validate/dist/locale/es');
Vue.use(VeeValidate, {
    locale: 'es',
    dictionary: {
      es: VueValidationEs
    }
});






Vue.component('example-component', require('./components/ExampleComponent.vue'));


// Devices
Vue.component('device-seeker', require('./components/devices/seeker.vue'));
Vue.component('device-add', require('./components/devices/add.vue'));
Vue.component('devices-show', require('./components/devices/show.vue'));

// Types
Vue.component('type-add', require('./components/types/add.vue'));

// Users
Vue.component('user-add', require('./components/users/add.vue'));
Vue.component('user-seeker', require('./components/users/seeker.vue'));






const app = new Vue({
    el: '#app',

    data() {
    	return {
    		section: {
    			display: 'users',
    		}
    	}
    },

    methods: {
        asideToggel(){
            
        }
    }
});

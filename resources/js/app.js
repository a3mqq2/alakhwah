import './bootstrap';


import Vue from 'vue';
window.Vue = Vue;
import vSelect from 'vue-select'
import report from './components/report.vue';
import payments from './components/payments.vue';
import statement from './components/statement.vue';
import 'vue-select/dist/vue-select.css';

import { instance } from './instance';
Vue.prototype.$instance = instance;




import toastr from 'toastr';
import 'toastr/toastr.scss';

Vue.prototype.$toastr = toastr;
Vue.component('create-statement', statement);
Vue.component('report-component', report);
Vue.component('payments-component', payments);
Vue.component('v-select', vSelect)

const app = new Vue({
    el:'#app'
});

import Vue from 'vue';
import DashboardNew from './components/DashboardNew.vue';

Vue.component('dashboard', DashboardNew);

const app = new Vue({
    el: '#app'
});

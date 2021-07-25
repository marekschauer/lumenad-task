import Vue from 'vue'
window.Vue = Vue;
require('./bootstrap');

Vue.component('csv-display', require('./components/CsvDisplay.vue').default);
Vue.component('csv-database-search', require('./components/CsvDatabaseSearch.vue').default);
const app = new Vue({
    el: '#app',
});

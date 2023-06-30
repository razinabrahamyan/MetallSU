/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */


window.Vue = require('vue').default;

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('item-component', require('./components/items/ItemComponent.vue').default);
Vue.component('sub-item-component', require('./components/items/SubItemComponent.vue').default);
Vue.component('edit-card-component', require('./components/items/EditCardComponent.vue').default);


Vue.component('category-edit-component', require('./components/category/CategoryEditComponent.vue').default);
Vue.component('category-component', require('./components/category/CategoryComponent.vue').default);

Vue.component('add-worker-component', require('./components/worker/AddWorkerComponent.vue').default);
Vue.component('worker-page-component', require('./components/worker/WorkerPageComponent.vue').default);

Vue.component('salary-component', require('./components/salary/SalaryComponent.vue').default);




/*custom jquery based components*/
Vue.component('vue-select', require('./components/select2/Select2Component.vue').default);
Vue.component('data-table', require('./components/datatable/DataTableComponent.vue').default);
/*custom jquery based components*/





/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#main_wrapper',
});


/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

var VueCookie = require('vue-cookie');
window.Vue.use(VueCookie);

var VueCurrencyFilter = require('vue-currency-filter');
window.Vue.use(VueCurrencyFilter, {
    symbol : '$', 
    thousandsSeparator: ',',
    fractionCount: 2,
    fractionSeparator: '.',
    symbolPosition: 'front',
    symbolSpacing: false
  });

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('register-options', require('./components/RegisterOptions.vue'));


const app = new Vue({
    el: '#app'
});


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


  window.omegaquant = {
    
        submitForm: function (target, htmlId, formId, forceWidth) {
        
            if (target && target.setAttribute) {

                target.setAttribute('disabled', 'disabled');
                var width = target.clientWidth;

                console.log('inner width', target.clientWidth);
                
                var defaultContent = '<i class="fa fa-spinner fa-spin"></i>';

                var src = document.getElementById(htmlId);
                 
                target.innerHTML = src && src.innerHTML || defaultContent;

                if (forceWidth) {
                  $(target).width(width);                
                }          
                console.log('after', target.clientWidth);
            }
    
            var form = document.getElementById(formId);
            form && form.submit && form.submit();
        }
    
    };


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */



// const app = new Vue({
//     el: '#app',
//     data() {
//         return {
//             isLoading: false,
//             status: '',
//         }
//     },
//     methods: {
//         appLoading() {
//             this.isLoading = true    
//             alert(this.isLoading)        
//         }
//     }
// });

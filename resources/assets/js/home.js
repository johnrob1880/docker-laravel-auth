let Vue = require('vue')

let BootstrapPanel = require('./components/Panel.vue')

new Vue({
    el: '#home',
    components: {
        'bootstrap-panel': BootstrapPanel
    },
    data() {
        return {
        }
    },
    mounted() {
    }
})
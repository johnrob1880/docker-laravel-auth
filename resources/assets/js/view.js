let Vue = require('vue')

let BootstrapPanel = require('./components/Panel.vue')

new Vue({
    el: '#view',
    components: {
        'bootstrap-panel': BootstrapPanel
    },
    data() {
        return {
        }
    },
    mounted() {
        var self = this

        window.Holder && window.Holder.addTheme("index", {
            bg: "#315EC9",
            fg: "#ffffff",
            size: 32,
            fontweight: "normal"
          });
    }
})
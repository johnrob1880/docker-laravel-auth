let Vue = require('vue')

let LoadingIcon = require('./components/LoadingIcon.vue')
let BusyPanel = require('./components/BusyPanel.vue')
let LoremIpsum = require('./components/LoremIpsum.vue')

new Vue({
    el: '#register',
    components: {
        'loading-icon': LoadingIcon,
        'busy-panel': BusyPanel,
        'lorem-ipsum': LoremIpsum
    },
    data() {
        return {
            isLoading: false,
            status: ''
        }
    },
    mounted() {
        var self = this;

        window.onSubmit = function () {
            self.isLoading = true;
            var $form = $('#registerForm');

            $form.find(':submit').attr('disabled', 'disabled');
            $form.find(':submit').html($form.find('#working').html());
            $form.submit();
        };

        // $('form').submit(function (event) {
        //     event.preventDefault()

        //     //$("html, body").animate({ scrollTop: 0 }, "slow");
            
        //     self.isLoading = true

        //     if (!$(this).hasClass('submitted')) {
        //         $(this).addClass('submitted')
        //         $(this).find(':submit').attr('disabled', 'disabled')
        //         this.submit()
        //     }
        // });

        window.Holder && Holder.addTheme("default", {
            bg: "#F2F2F2",
            fg: "#CCCCCC",
            size: 8,
            font: "Raleway",
            fontweight: "normal"
          });
    },
    computed: {
    },
    methods: {
        busy() {
            this.isLoading = true       
        }
    }
});
<template>
    <div v-cloak>
        <slot v-if="!selected" name="heading"></slot>
        <template v-if="selected">
            <slot v-if="current == 'register'" name="register"></slot>
            <slot v-if="current == 'login'" name="login"></slot>
            <button class="btn btn-block btn-transparent" @click="reset">Choose a different option</button>
        </template>
        <template v-if="!selected">
            <br />
            <button class="btn btn-lg btn-default btn-block" @click="select('register')">I am a new customer</button>
            <br /><br />
            <button class="btn btn-lg btn-default btn-block" @click="select('login')">I already have an account</button>

        </template>
    </div>
</template>
<script>
    export default {
        props: ['view'],
        data() {
            return {
                selected: false,
                current: null
            }
        },
        methods: { 
            select: function (opt) {
                    this.selected = true
                    this.current = opt
                    this.$cookie.set('authView', opt)
            },
            reset: function () {
                this.selected = false
                this.current = null
                this.$cookie.delete('authView')
            }
        },
        mounted() {

            let view = this.$cookie.get('authView')

            console.log('Component mounted.', this.$errors)

            if (view) {
                this.selected = true;
                this.current = view
            }
        }
    }
</script>

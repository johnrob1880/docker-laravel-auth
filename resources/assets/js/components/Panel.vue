<template>
    <div v-bind:class="getPanelClass">
        <div class="panel-heading clearfix">
            <slot name="header"></slot>
        </div>
        <div class="panel-body">
            <slot name="body"></slot>
        </div>
    </div>
</template>
<script>
    export default {
        name: 'bootstrap-panel',
        props: {
            theme: {
                type: String,
                default: 'primary'
            }
        },
        computed: {
            getPanelClass: function () {
                return 'panel panel-' + this.theme;
            }
        }
    }
</script>
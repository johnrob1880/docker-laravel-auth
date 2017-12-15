<template>
    <div>
        <div v-if="busy" id="loader"></div>
        <div :style="getPanelStyle" id="panelContent">
            <slot></slot>
        </div>
    </div>
</template>
<script>
    export default {
        name: 'busy-panel',
        props: {
            busy: {
                type: Boolean,
                default: false
            },
            opacity: {
                type: String,
                default: '0'
            },
            color: {
                type: String,
                default: '#3498db'
            }
        },
        computed: {
            getPanelStyle () {
                return { 
                    'opacity': parseFloat(this.busy ? this.opacity : "1")
                }
            },
            getLoaderStyle() {
                return {
                    'border-top': this.color
                }
            }
        }
    }
</script>
<style lang="css" scoped>
    /* Center the loader */
    #loader {
        position: absolute;
        left: 50%;
        top: 50%;
        z-index: 1;
        width: 150px;
        height: 150px;
        margin: -75px 0 0 -75px;
        border: 16px solid #00539b;
        border-radius: 50%;
        border-top: 16px solid #0096d7;
        border-bottom: 16px solid #0096d7;
        width: 120px;
        height: 120px;
        -webkit-animation: spin 2s linear infinite;
        animation: spin 2s linear infinite;
    }

    @-webkit-keyframes spin {
        0% { -webkit-transform: rotate(0deg); }
        100% { -webkit-transform: rotate(360deg); }
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>
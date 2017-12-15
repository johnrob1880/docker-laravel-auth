<template>
    <div>
        <div id="loader"></div>

        <div style="display:none;" id="panelContent" class="animate-bottom" v-cloak>
            <slot></slot>
        </div>
    </div>
</template>
<script>
    export default {
        name: 'loading-panel',
        props: {
            delay: {
                type: Number,
                default: 500
            },
            color: {
                type: String,
                default: '#3498db'
            }
        },
        mounted() {
            document.body.onload = this.showPage
        },
        methods: {
            loaded() {
                setTimeout(this.showPage, this.delay)
            },
            showPage() {
                document.getElementById("loader").style.display = "none";
                document.getElementById("panelContent").style.display = "block";
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
        border: 16px solid #f8f9fb;
        border-radius: 50%;
        border-top: 16px solid #3498db;
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

    /* Add animation to "page content" */
    .animate-bottom {
        position: relative;
        -webkit-animation-name: animatebottom;
        -webkit-animation-duration: 1s;
        animation-name: animatebottom;
        animation-duration: 1s
    }

    @-webkit-keyframes animatebottom {
        from { bottom:-100px; opacity:0 } 
        to { bottom:0px; opacity:1 }
    }

    @keyframes animatebottom { 
        from { bottom:-100px; opacity:0 } 
        to { bottom:0; opacity:1 }
    }

    #panelContent {
        display: none;
        text-align: center;
    }
</style>
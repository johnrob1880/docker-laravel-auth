<template>
    <div class="date-fields">
        <div class="row">
            <div class="col-xs-4">
                <select id="date-fields-month" v-bind="month">
                    <option v-bind:key="i" v-for="i in 12" value="{{i}}">{{ __('forms.years.' + i) }}</option>
                </select>
            </div>
            <div class="col-xs-4">
                <select id="date-fields-day">
                    <option v-bind:key="i" v-for="i in 31" value="{{i}}">{{ __('forms.days.' + i) }}</option>
                </select>
            </div>
            <div class="col-xs-4">
                <select id="date-fields-year">
                    
                </select>
            </div>
        </div>
        <input id="date-fields-date" type="hidden" />
    </div>
</template>
<script>
    export default {
        name: 'date-fields',
        props: {
            date: {
                type: String,
                default: false
            }
        },
        data: function () {
            return {
                month: 1,
                day: null,
                year: null
            }
        },
        mounted() {
            
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
    
</style>
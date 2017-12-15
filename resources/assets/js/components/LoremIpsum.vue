<template>
    <div>
        <p class="lorem-ipsum" v-for="i in paragraphs" v-bind:key="i">
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque at lacus rutrum, ornare ipsum eu, sollicitudin libero. Vestibulum nunc justo, mattis sed augue vitae, auctor ultricies lectus. Donec ultricies venenatis sapien, blandit luctus dui aliquam sit amet. Donec ac euismod sem, vel elementum nibh. Donec at nibh turpis. Phasellus fermentum malesuada sagittis. Mauris lacinia tellus nisl, in condimentum sapien interdum vitae.
        </p>
    </div>
</template>

<script>
    export default {
        props: {
            paragraphs: {
                type: Number,
                default: 1
            }
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>

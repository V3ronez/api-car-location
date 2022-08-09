<template>
    <div :class="alertStyle" role="alert">
        {{ title }}
        <hr>
        <p>{{ details.message }}</p>
        <br>
        <ul v-if="details.data">
            <li v-for="(valueErro, key) in details.data" :key="key">{{ valueErro[0] }}</li>
        </ul>

    </div>
</template>

<script>
export default {
    computed: {
        alertStyle() {
            return 'alert alert-' + this.styleAlert;
        }
    },
    props: ['styleAlert', 'details', 'title']
}
</script>

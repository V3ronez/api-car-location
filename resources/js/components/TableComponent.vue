<template>
    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col" v-for="(value, key) in titles" :key="key">{{ value.title }}</th>
                <th v-if="view.visible || update.visible || remove"></th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(obj, key) in filterData" :key="key">
                <td v-for="(valueObj, keyObj) in obj" :key="keyObj">
                    <span v-if="titles[keyObj].type == 'text'">{{ valueObj }}</span>
                    <span v-if="titles[keyObj].type == 'image'"><img :src="'\\storage\\' + valueObj" width="35"
                            height="30"></span>
                    <span v-if="titles[keyObj].type == 'date'">{{ valueObj | formatDate }}</span>
                </td>
                <td v-if="view.visible || update.visible || remove.visible">
                    <button v-if="view.visible" class="btn btn-outline-primary sm" :data-toggle="view.dataToggle"
                        :data-target="view.dataTarget" @click="setStore(obj)">View</button>
                    <button v-if="update.visible" class="btn btn-outline-primary sm" :data-toggle="update.dataToggle"
                        :data-target="update.dataTarget" @click="setStore(obj)">Update</button>
                    <button v-if="remove" :data-toggle="remove.dataToggle" :data-target="remove.dataTarget"
                        class="btn btn-outline-danger sm" @click="setStore(obj)">Remove</button>
                </td>
            </tr>
        </tbody>
    </table>
</template>

<script>
export default {
    props: ['data', 'titles', 'view', 'update', 'remove'],

    methods: {
        setStore(obj) {
            this.$store.state.feedbackApi.status = '';
            this.$store.state.feedbackApi.message = '';
            this.$store.state.feedbackApi.data = '';

            this.$store.state.item = obj
        }
    },
    computed: {
        filterData() {
            let keysTitles = Object.keys(this.titles);
            let dataFilted = []

            this.data.map((item) => {
                let itemFilted = {}

                keysTitles.forEach(index => {
                    itemFilted[index] = item[index]
                })
                dataFilted.push(itemFilted);
            })
            return dataFilted;
        }
    }
}
</script>

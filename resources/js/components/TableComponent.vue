<template>
    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col" v-for="(value, key) in titles" :key="key">{{ value.title }}</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(obj, key) in filterData" :key="key">
                <td v-for="(valueObj, keyObj) in obj" :key="keyObj">
                    <span v-if="titles[keyObj].type == 'text'">{{ valueObj }}</span>
                    <span v-if="titles[keyObj].type == 'image'"><img :src="'\\storage\\'+ valueObj"  width="35" height="30"></span>
                    <span v-if="titles[keyObj].type == 'date'">{{'...'+valueObj }}</span>
                </td>
            </tr>
        </tbody>
    </table>
</template>

<script>
export default {
    props: ['data', 'titles'],
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

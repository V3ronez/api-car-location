<template>
    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col" v-for="(value, key) in title" :key="key">{{ value }}</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(brand, key) in data" :key="key">
                <th scope="row">{{ brand.id }}</th>
                <td>{{ brand.name }}</td>
                <td><img :src="'\\storage\\' + brand.image" width="35" height="30"></td>
            </tr>
        </tbody>
    </table>
</template>

<script>
export default {
    props: ['data', 'title']
}
</script>

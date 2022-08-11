<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- card search -->
                <card-component title="Brands">
                    <template v-slot:content-body>
                        <div class="form-row">
                            <div class="mb-3 col">
                                <input-container-component id="inputId" title="ID" id-help="idHelp"
                                    text-help="Search for brand id">
                                    <input type="number" class="form-control" id="inputId" aria-describedby="idHelp">
                                </input-container-component>
                            </div>
                            <div class="mb-3 col">
                                <input-container-component id="nameInput" title="Brand name" id-help="nameHelp"
                                    text-help="Search for brand name">
                                    <input type="text" class="form-control" id="inputName" aria-describedby="nameHelp">
                                </input-container-component>
                            </div>
                        </div>
                    </template>
                    <template v-slot:footer>
                        <button type="submit" class="btn btn-dark btn-sm float-right">Search</button>
                    </template>
                </card-component>
                <!-- end card search -->
                <!-- card list brands -->
                <card-component title="Brands Relationship">
                    <template v-slot:content-body>
                        <table-component :data="brands.data" :titles="{
                            id: {title:'Id', type:'text'},
                            name: {title: 'Name', type:'text'},
                            image: {title: 'Image', type: 'image'},
                            created_at: {title: 'Created at', type: 'date'}
                        }"></table-component>
                    </template>
                    <!-- footer -->
                    <template v-slot:footer>
                        <div class="row">
                            <div class="col-10">
                                <paginate-component>
                                    <li v-for="(link, key) in brands.links" :key="key" @click="paginate(link)"
                                        :class="link.active ? 'page-item active': 'page-item'">
                                        <a class="page-link" v-html="link.label"></a>
                                    </li>
                                </paginate-component>
                            </div>
                            <div class="col">
                                <button type="button" class="btn btn-dark btn-sm float-right" data-toggle="modal"
                                data-target="#modalBrand">Add</button>
                            </div>
                        </div>
                    </template>
                        <!-- end footer -->
                </card-component>
                <!-- end card list brands -->
            </div>
        </div>
        <!-- Modal -->
        <modal-component id="modalBrand" title="Add new brand">
            <template v-slot:alert>
                <alert-component style-alert="success" :details="messageResponse" title="Success brand register"
                    v-if="statusResponse == 'ok'"></alert-component>
                <alert-component style-alert="danger" :details="messageResponse" title="Error brand register"
                    v-if="statusResponse == 'error'"></alert-component>
            </template>
            <template v-slot:content-body>
                <input-container-component id="brandName" title="Brand name" id-help="brandNameHelp"
                    text-help="ex: Ford">
                    <input type="text" class="form-control" v-model="nameBrand" id="brandName"
                        aria-describedby="brandNameHelp">
                </input-container-component>
                <input-container-component id="brandImage" title="Brand image" id-help="brandImageHelp"
                    text-help="allowed extensions: JPG, PNG, JPEG">
                    <input type="file" class="form-control-file" id="brandImage" @change="loadImage($event)"
                        aria-describedby="brandImageHelp">
                </input-container-component>
            </template>
            <template v-slot:footer-modal>
                <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" @click="save()">Add</button>
            </template>
        </modal-component>
        <!-- end modal -->
    </div>

</template>

<script>
import axios from 'axios';


export default {
    mounted() {
        this.getBrands();
    },

    computed: {
        token() {
            let token = document.cookie.split(';').find(index => {
                return index.includes('token=');
            })
            token = token.split('=')[1];
            token = `Bearer ${token}`;
            return token;
        }
    },
    data() {
        return {
            uriBase: 'http://localhost:8000/api/v1/brand',
            brands: {data:[]},
            nameBrand: '',
            imageBrand: [],
            statusResponse: '',
            messageResponse: {},
        }
    },
    methods: {
        getBrands() {
            let config = {
                headers: {
                    'Accept': 'application/json',
                    'Authorization': this.token,
                }
            }
            axios.get(this.uriBase, config)
                .then(response => {
                    this.brands = response.data
                })
                .catch(errors => {
                    console.log(errors);
                })
        },

        loadImage(event) {
            this.imageBrand = event.target.files
        },

        save() {
            let formData = new FormData();
            formData.append('name', this.nameBrand);
            formData.append('image', this.imageBrand[0]);

            let config = {
                headers: {
                    'Content-Type': 'multipart/form-data',
                    'Accept': 'application/json',
                    'Authorization': this.token,
                }
            }

            axios.post(this.uriBase, formData, config)
                .then(response => {
                    this.statusResponse = 'ok';
                    this.messageResponse = {
                        message: 'Brand: ' + response.data.brand.name + ' register successfully'
                    }
                })
                .catch(errors => {
                    this.statusResponse = 'error';
                    this.messageResponse = {
                        message: errors.response.data.message,
                        data: errors.response.data.errors
                    }
                })
        },

        paginate(link) {
            if(link.url){
                this.uriBase = link.url;
                this.getBrands();
            }
        }
    }
}
</script>

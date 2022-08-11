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
                                    <input type="number" class="form-control" id="inputId" v-model="query.id"
                                        aria-describedby="idHelp">
                                </input-container-component>
                            </div>
                            <div class="mb-3 col">
                                <input-container-component id="nameInput" title="Brand name" id-help="nameHelp"
                                    text-help="Search for brand name">
                                    <input type="text" class="form-control" id="inputName" v-model="query.name"
                                        aria-describedby="nameHelp">
                                </input-container-component>
                            </div>
                        </div>
                    </template>
                    <template v-slot:footer>
                        <button type="submit" @click="search" class="btn btn-dark btn-sm float-right">Search</button>
                    </template>
                </card-component>
                <!-- end card search -->
                <!-- card list brands -->
                <card-component title="Brands Relationship">
                    <template v-slot:content-body>
                        <table-component :data="brands.data" :view="{
                            visible: true,
                            dataToggle: 'modal',
                            dataTarget: '#modalBrandView'
                        }" :update="true" :remove="true" :titles="{
    id: { title: 'Id', type: 'text' },
    name: { title: 'Name', type: 'text' },
    image: { title: 'Image', type: 'image' },
    created_at: { title: 'Created at', type: 'date' }
}"></table-component>
                    </template>
                    <!-- footer -->
                    <template v-slot:footer>
                        <div class="row">
                            <div class="col-10">
                                <paginate-component>
                                    <li v-for="(link, key) in brands.links" :key="key" @click="paginate(link)"
                                        :class="link.active ? 'page-item active' : 'page-item'">
                                        <a class="page-link" v-html="link.label"></a>
                                    </li>
                                </paginate-component>
                            </div>
                            <div class="col">
                                <button type="button" class="btn btn-dark btn-sm float-right" data-toggle="modal"
                                    data-target="#modalBrand">Add brand</button>
                            </div>
                        </div>
                    </template>
                    <!-- end footer -->
                </card-component>
                <!-- end card list brands -->
            </div>
        </div>
        <!-- Modal include brand-->
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
        <!-- end modal include brand -->
        <!-- start modal view brand -->
        <modal-component id="modalBrandView" title="View brand">
            <template v-slot:alert>
                teste
            </template>
            <template v-slot:content-body>
                teste
            </template>
            <template v-slot:footer-modal>
                <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" @click="save()">Add</button>
            </template>
        </modal-component>
        <!-- end modal view brand -->
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
            urnPaginate: '',
            urnFilter: '',
            brands: { data: [] },
            nameBrand: '',
            imageBrand: [],
            statusResponse: '',
            messageResponse: {},
            query: { id: '', name: '' }
        }
    },
    methods: {
        getBrands() {
            let url = `${this.uriBase}?${this.urnPaginate}${this.urnFilter}`
            let config = {
                headers: {
                    'Accept': 'application/json',
                    'Authorization': this.token,
                }
            }
            axios.get(url, config)
                .then(response => {
                    this.brands = response.data
                })
                .catch(errors => {
                    console.log(errors);
                })
        },
        search() {
            let params = '';
            for (let key in this.query) {
                if (this.query[key]) {
                    if (params != '') {
                        params += ';';
                    }
                    params += key + ':like:' + this.query[key];
                }
            }

            if (params != '') {
                this.urnPaginate = 'page=1';
                this.urnFilter = '&filter=' + params + '%';
            } else {
                this.urnFilter = '';
            }

            this.getBrands();
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
            if (link.url) {
                this.urnPaginate = link.url.split('?')[1];
                this.getBrands();
            }
        }
    }
}
</script>

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
                        <table-component></table-component>
                    </template>
                    <template v-slot:footer>
                        <button type="button" class="btn btn-dark btn-sm float-right" data-toggle="modal"
                            data-target="#modalBrand">Add</button>
                    </template>
                </card-component>
                <!-- end card list brands -->
            </div>
        </div>
        <!-- Modal -->
        <modal-component id="modalBrand" title="Add new brand">
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
            urlBase: 'http://localhost:8000/api/v1/brand',
            nameBrand: '',
            imageBrand: []
        }
    },
    methods: {
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
            axios.post(this.urlBase, formData, config)
                .then(response => {
                    console.log(response)
                })
                .catch(error => {
                    console.log(error);
                })
        }
    }
}
</script>

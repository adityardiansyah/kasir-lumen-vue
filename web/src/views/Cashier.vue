<template>
<div>
    <navbar></navbar>
    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="input-group flex-nowrap">
                            <input type="text" class="form-control" placeholder="Cari barang disini..." v-model="search" @keyup="submitSearch">
                            <span class="input-group-text" id="addon-wrapping">
                                <i class="fa fa-search"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card mt-2">
                    <div class="card-body">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Barang</th>
                                    <th>Stok</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in items.data">
                                    <td>{{ index+1 }}</td>
                                    <td>{{ item.name }}</td>
                                    <td>{{ item.stock }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-primary" v-on:click="addCart(item.id)">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="row justify-content-center" v-if="pagination.next_page_url !== null">
                            <div class="col-5">
                                <button class="btn btn-sm btn-primary" @click="getResult(pagination.prev_page_url)"
                                        :disabled="!pagination.prev_page_url">
                                    Previous
                                </button>
                                <span class="m-4">Page {{pagination.current_page}} of {{pagination.last_page}}</span>
                                <button class="btn btn-sm btn-primary" @click="getResult(pagination.next_page_url)"
                                        :disabled="!pagination.next_page_url">Next
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <h6 class="card-header">
                        <i class="fa fa-shopping-cart"></i>
                        Keranjang
                    </h6>
                    <div class="card-body">

                    </div>
                    <hr>
                    <div class="card-body">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</template>
<script>
import Navbar from './partials/Navbar.vue'
import axios from 'axios'
import { onMounted, ref, toRef } from 'vue'


var user = JSON.parse(localStorage.getItem('user'));
var token_store = JSON.parse(localStorage.getItem('store'));
export default {
    mounted(){
        this.getItems()
    },
    data: () => ({
        items: [],
        search: '',
        pagination: {}
    }),
    components: {
        Navbar
    },
    created(){
        this.getItems()
    },
    methods: {
        getResult: function(page_url){
            this.getItems(this.search, page_url)
        },
        submitSearch: function(){
            this.getItems(this.search)
        },
        getItems(query = '', page_url){
            page_url = page_url || 'http://127.0.0.1:8000/products'
            axios.get(
                page_url,
                {
                    headers: {
                        'Authorization': 'Bearer '+user.api_token,
                        'Store': token_store.token
                    },
                    params:{
                        search: query
                    }
                }
            )
            .then((res) => {
                this.items = res.data.data
                this.makePaginate(res.data.data)
            })
            .catch((err) => {
                console.log(err.response);
            })
        },
        makePaginate: function(data){
            let pag = {
                current_page: data.current_page,
                last_page: data.last_page,
                next_page_url: data.next_page_url,
                prev_page_url: data.prev_page_url
            }
            this.pagination = pag
            console.log(pag);
        }
    },
}
</script>

<style>

</style>
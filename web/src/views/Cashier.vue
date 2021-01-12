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
                <div class="card mt-2 mb-2">
                    <div class="card-body">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Barang</th>
                                    <th>Harga</th>
                                    <th>Stok</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in items.data">
                                    <td>{{ index+1 }}</td>
                                    <td>{{ item.name }}</td>
                                    <td>{{ uang(item.price_sell) }}</td>
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
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama Barang</th>
                                    <th>Qty</th>
                                    <th>Harga</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(cart, index) in carts">
                                    <td>{{ index+1 }}</td>
                                    <td>{{ cart.name }}</td>
                                    <td>
                                        <button class="btn btn-sm" v-on:click="update(cart.id, 'minus')">
                                            <i class="fa fa-minus"></i>
                                        </button>
                                        {{ cart.qty }}
                                        <button class="btn btn-sm" v-on:click="update(cart.id, 'plus')">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </td>
                                    <td>{{ uang(cart.sub_total) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <hr>
                    <div class="card-body">
                        <form @submit.prevent="checkout()">
                            <table class="table">
                                <tr>
                                    <td colspan="2">
                                        <b>Sub Total :</b>
                                    </td>
                                    <td style="text-align:right;">
                                        <b>Rp. {{ uang(sub_total_cart) }}</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <b>Fee Reseller :</b>
                                    </td>
                                    <td style="text-align:right;">
                                        <b>Rp. {{ uang(fee) }}</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <b>Total :</b>
                                    </td>
                                    <td style="text-align:right;">
                                        <b>Rp. {{ uang(total_cart) }}</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <b>
                                        Dibayar :
                                        </b>
                                    </td>
                                    <td>
                                        <input type="text" class="input-payment form-control form-control-sm mt-2" style="text-align: right; " v-model="payment" @keyup="countCashback">
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <b>Kembalian :</b>
                                    </td>
                                    <td style="text-align:right;">
                                        <b>Rp. {{ cashback }}</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3">
                                        <button type="submit" class="btn btn-sm btn-outline-primary pull-right mt-2">
                                            <i class="fa fa-money"></i>
                                            BAYAR
                                        </button>
                                    </td>
                                </tr>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</template>
<script>
import uang from '../lib/script.js'
import Navbar from './partials/Navbar.vue'
import axios from 'axios'
import { onMounted, ref, toRef, reactive } from 'vue'


var user = JSON.parse(localStorage.getItem('user'));
var token_store = JSON.parse(localStorage.getItem('store'));
var header = {
    'Authorization': 'Bearer '+user.api_token,
    'Store': token_store.token
};

export default {
    mounted(){
        this.getItems()
        this.getCart()
    },
    data: () => ({
        items: [],
        search: '',
        pagination: {},
        carts: [],
        sub_total_cart: 0,
        total_cart: 0,
        fee: 0,
        pay: {},
        payment: '',
        cashback:0
    }),
    components: {
        Navbar
    },
    created(){
        this.getItems()
    },
    methods: {
        countCashback: function(){
            this.payment = uang.currency(this.payment, '.') 
            this.cashback = this.payment.split('.').join('') - this.total_cart
        },
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
                    headers: header,
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
        },
        addCart: function(id) {
            axios.post('http://127.0.0.1:8000/cart', {product_id: id}, { headers: header })
            .then((res) => {
                this.getItems()
                this.getCart("plus",id)
            })
            .catch((req) => {
                console.log(req);
            })
        },
        getCart: function(type = '', id = ''){
            axios.get('http://127.0.0.1:8000/cart', {headers: header}).then((res) => {
                const total = res.data.data
                if(total.length === 0){
                    this.cashback
                }
                let sum = total.reduce((a,b) => {
                    return a + b.sub_total;
                }, 0)
                this.carts = res.data.data
                this.sub_total_cart = sum
                this.fee = total.reduce((a,b) => {
                    return a + (b.fee_reseller * b.qty)
                }, 0)
                this.total_cart = sum + this.fee
            })
            .catch((err) => {
                console.log(err);
            })
        },
        update: function(id, type) {
            axios.put('http://127.0.0.1:8000/cart', {id: id, type: type}, { headers: header })
            .then((res) => {
                this.getCart(type, res.data.data.product_id)
                this.getItems()
            })
            .catch((err) => {
                console.log(err.response);
            })
        },
        uang: function(rp){
            return uang.formatRupiah(rp)
        },
        checkout: function(){
            let payments = {
                customer_id: 1,
                invoice: 'INV-'+Math.floor(Math.random() * 100000),
                total: this.total_cart,
                list_data: JSON.stringify(this.carts),
                payment: this.payment,
                fee_reseller: this.fee,
                cashback: this.cashback,
            }
            axios.post('http://127.0.0.1:8000/checkout', payments, {headers:header})
            .then((res) => {
                console.log(res.data);
            })
            .catch((err) => {
                console.log(err.response);
            })
        }
    },
}
</script>

<style>
.input-payment{
    border: none;
    border-bottom: 1px solid #d9d9d9!important;
}
</style>
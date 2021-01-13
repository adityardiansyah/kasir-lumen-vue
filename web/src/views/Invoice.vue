<template>
  <div>
      <navbar></navbar>
      <div class="container-fluid mt-4">
        <div class="row justify-content-md-center">
            <div class="col col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="text-center">{{ name_store.name.name }}</h4>
                        <p class="text-center">
                            {{ invoice.time }} <br>
                            <b>{{ invoice.no_invoice }}</b>
                        </p>
                        <table class="table">
                            <tbody>
                                <tr v-for="(data, index) in invoice.list_items">
                                    <td>{{ data.name }}</td>
                                    <td style="text-align:right">Rp. {{ formatUang.currency(data.price_sell) }}</td>
                                </tr>
                                <tr>
                                    <td>
                                        <b>Total</b>
                                    </td>
                                    <td style="text-align:right">Rp. {{ invoice.total }}</td>
                                </tr>
                                <tr>
                                    <td>
                                        Dibayar
                                    </td>
                                    <td style="text-align:right">Rp. {{ formatUang.currency(invoice.payment) }}</td>
                                </tr>
                                <tr>
                                    <td>
                                        Kembali
                                    </td>
                                    <td style="text-align:right">Rp. {{ formatUang.currency(invoice.cashback) }}</td>
                                </tr>
                            </tbody>
                        </table>
                        <div>
                            <p>
                                <small>
                                Terima Kasih telah berbelanja<br>
                                {{ name_store.name.name }} :)
                                </small>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="text-center">
                    <router-link :to="{name: 'cashier'}" class="btn btn-primary btn-sm shadow mt-4">Kembali</router-link>
                </div>
            </div>
        </div>
      </div>
  </div>
</template>

<script>
import uang from '../lib/script.js'
import Navbar from './partials/Navbar.vue'
import { reactive, onMounted, ref } from 'vue'
import axios from 'axios'
var store = JSON.parse(localStorage.getItem('store'));
var user = JSON.parse(localStorage.getItem('user'));
var header = {
    'Authorization': 'Bearer '+user.api_token,
    'Store': store.token
};

export default {
    components: { Navbar },
    setup(){
        const formatUang = uang;
        const name_store = reactive({
            name: store
        })
        const invoice = reactive({
            no_invoice: '',
            time: '',
            list_items: '',
            total: '',
            payment: '',
            cashback: '',
        })
        var url = window.location.pathname.split('/');

        onMounted(() => {
            axios.get('http://127.0.0.1:8000/order/'+url[2], {headers: header})
            .then((res) => {
                console.log(JSON.parse(res.data.data.list_order));
                // console.log(res.data);
                invoice.no_invoice = res.data.data.invoice
                invoice.time = res.data.data.time
                invoice.list_items = JSON.parse(res.data.data.list_order)
                invoice.total = uang.currency(res.data.data.total)
                invoice.payment = res.data.data.payment
                invoice.cashback = res.data.data.cashback
            })
            .catch((err) => {
                console.log(err);
            })
        })
        
        return {
            name_store,
            invoice,
            formatUang
        }
    }
}
</script>

<style>

</style>
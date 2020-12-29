<template>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Amanah Cashier</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Kasir</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Tambah Barang</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" v-on:click="logout()">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</template>

<script>
import axios from 'axios'
import { useRouter } from 'vue-router'

var user = JSON.parse(localStorage.getItem('user'));
var token_store = JSON.parse(localStorage.getItem('store'));

    export default {
        setup(){
            const router = useRouter()

            function logout() {
                axios.get('http://127.0.0.1:8000/logout', {
                    headers: {
                        'Authorization': 'Bearer '+user.api_token,
                        'Store': token_store.token
                    }
                })
                .then((res) => {
                    localStorage.removeItem('user');
                    localStorage.removeItem('store');
                    router.push({name: 'login'});
                })
                .catch((err) => {
                    console.log(err.response);
                })
            }
            return {
                logout,
                router
            }
        }
    }
</script>

<style>

</style>
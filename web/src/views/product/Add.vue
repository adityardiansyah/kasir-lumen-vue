<template>
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-8">
                <div class="card rounded shadow">
                    <div class="card-header">
                        Tambah Product
                    </div>
                    <div class="card-body">
                        <form @submit.prevent="submit()">
                            <div class="mb-3">
                                <label for="" class="form-label">Nama Barang</label>
                                <input type="text" class="form-control" v-model="product.name">
                                <div class="text-danger" v-if="validation.name">
                                    {{ validation.name[0] }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="" class="form-label">Harga Beli Barang</label>
                                        <input type="text" class="form-control" v-model="product.price_buy">
                                        <div class="text-danger" v-if="validation.price_buy">
                                            {{ validation.price_buy[0] }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="" class="form-label">Harga Jual Barang</label>
                                        <input type="text" class="form-control" v-model="product.price_sell">
                                        <div class="text-danger" v-if="validation.price_sell">
                                            {{ validation.price_sell[0] }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="" class="form-label">Jumlah</label>
                                        <input type="text" class="form-control" v-model="product.stock">
                                        <div class="text-danger" v-if="validation.stock">
                                            {{ validation.stock[0] }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="" class="form-label">Kategori Barang</label>
                                        <select class="form-control" v-model="product.category_id">
                                            <option value="">Pilih Kategori</option>
                                            <option v-for="(category, index) in categories.data" :value="category.id">{{ category.name }}</option>
                                        </select>
                                        <div class="text-danger" v-if="validation.category_id">
                                            {{ validation.category_id[0] }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" v-model="checkbox_reseller">
                                            <label class="form-check-label" for="reseller">
                                                Fee Reseller?
                                            </label>
                                        </div>
                                        <div v-if="checkbox_reseller">
                                            <input type="text" class="form-control" v-model="product.fee_reseller" placeholder="Jumlah Fee. Rp. 2000">
                                            <div class="text-danger" v-if="validation.fee_reseller">
                                                {{ validation.fee_reseller[0] }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="" class="form-label">Gambar</label>
                                        <input type="file" class="form-control" v-on:change="uploadFile">
                                        <div class="text-danger" v-if="validation.image">
                                            {{ validation.image[0] }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-primary shadow pull-right">Tambah</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { reactive, ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'
var stores = JSON.parse(localStorage.getItem('store'));
var user = JSON.parse(localStorage.getItem('user'));
var header = {
    'Authorization': 'Bearer '+user.api_token,
    'Store': stores.token
};

export default {
setup() {
    const product = reactive({
        name: '',
        price_sell: '',
        price_buy: '',
        category_id: '',
        stock: '',
        fee_reseller: '',
        image: ''
    })
    let categories = reactive({
        data: []
    });
    const validation = ref([]);
    const checkbox_reseller = ref(false);

    onMounted(() => {
        axios.get('http://127.0.0.1:8000/category', {headers: header})
        .then((res) => {
            categories.data = res.data.data
        })
        .catch((err) => {
            console.log(err);
        })
    })
    async function submit() {
        await axios.post('http://127.0.0.1:8000/products', product,{headers:header})
        .then((res) => {
            console.log(res);
        })
        .catch((err) => {
            console.log(err);
        })
    }
    const uploadFile = (e) => {
        let files = e.target.files || e.dataTransfer.files;
        if(!files.length)
        return;
        createImage(files[0]);
    }
    const createImage = (file) => {
        let reader = new FileReader();
        reader.onload = (e) => {
            product.image = e.target.result;
        };
        reader.readAsDataURL(file);
    }
    
    return {
        product,
        validation,
        checkbox_reseller,
        categories,
        submit,
        uploadFile,
        createImage
    }
}
}
</script>

<style>

</style>
<template>
  <div class="container mt-4">
    <div class="row justify-content-center">
      <div class="col-6">
        <h3 class="text-center">Selamat Datang Dulur</h3>
        <form @submit.prevent="masuk()">
          <label for="">Pilih Toko Anda :</label>
          <select class="form-control mb-2" v-model="form.id">
            <option selected>Pilih Toko</option>
            <option v-for="toko in stores.store" :value="toko.id">{{ toko.name }}</option>
          </select>
          <button type="submit" class="btn btn-primary btn-block pull-right">Masuk</button>
        </form>
        <!-- <div class="row h-100 text-center">
          <div class="col-12">
            <router-link :to="{name: 'add-product'}">Tambah Barang</router-link>
            |
            <router-link :to="{name: 'cashier'}">Kasir</router-link>
          </div>
        </div> -->
      </div>
    </div>
  </div>
</template>

<script>
  import axios from 'axios'
  import {
    reactive,
    ref,
    onMounted
  } from 'vue'
  import {
    useRouter,
    useRoute
  } from 'vue-router'
  var user = JSON.parse(localStorage.getItem('user'));

  export default {
    setup() {
      const router = useRouter()
      let stores = reactive({
        'store': ''
      })
      let form = reactive({
        'id': ''
      })

      onMounted(() => {
        axios.get('http://127.0.0.1:8000/store', {
            headers: {
              'Authorization': 'Bearer ' + user.api_token
            }
          })
          .then((res) => {
            stores.store = res.data.data
          })
          .catch((err) => {
            console.log(err.response);
          })
      })

      function masuk() {
        //  console.log('form '+form);
        axios.post('http://127.0.0.1:8000/token-store', form, {
            headers: {
              'Authorization': 'Bearer ' + user.api_token
            }
          })
          .then((res) => {
            // console.log(res.data.data);
            localStorage.setItem('store', JSON.stringify(res.data.data));
            router.push({
              name: 'cashier'
            });
          })
          .catch((err) => {
            console.log(err.response);
          })
      }

      return {
        masuk,
        stores,
        form,
        router
      }

    }
  }
</script>

<style>

</style>
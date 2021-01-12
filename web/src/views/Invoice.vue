<template>
  <div>
      <navbar></navbar>
      <div class="container-fluid mt-4">
        <div class="row justify-content-md-center">
            <div class="col col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="text-center">{{ name_store.name.name }}</h4>
                        <i>17 Januari 2021 17:00</i>
                    </div>
                </div>
            </div>
        </div>
      </div>
  </div>
</template>

<script>
import Navbar from './partials/Navbar.vue'
import { reactive } from 'vue'
var store = JSON.parse(localStorage.getItem('store'));

export default {
    components: { Navbar },
    setup(){
        const name_store = reactive({
            name: store
        })
        return {
            name_store
        }
    }
}
</script>

<style>

</style>
<template>
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-8">
                <div class="card rounded shadow">
                    <div class="card-header">
                        Buat Toko
                    </div>
                    <div class="card-body">
                        <form @submit.prevent="store()">
                            <div class="mb-3">
                                <label for="" class="form-label">Nama Toko</label>
                                <input type="text" class="form-control" v-model="stores.name">
                                <div v-if="validation.name" class="text-danger">
                                    {{ validation.name[0] }}
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">No. Telepon</label>
                                <input type="number" class="form-control" v-model="stores.phone">
                                <div v-if="validation.phone" class="text-danger">
                                    {{ validation.phone[0] }}
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">Address</label>
                                <input type="text" class="form-control"  v-model="stores.address">
                                <div v-if="validation.address" class="text-danger">
                                    {{ validation.address[0] }}
                                </div>
                            </div>
                            <button class="btn btn-outline-primary pull-right">
                                Submit
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios'
import { reactive, ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'

export default {
    setup(){
        let user = JSON.parse(localStorage.getItem('user'));
        let stores = reactive({
            name: '',
            phone: '',
            address: ''
        });

        const validation = ref([]);

        const router = useRouter();
        function store() {
            axios.post('http://127.0.0.1:8000/store',stores, {
                headers : {
                    'Authorization' : 'Bearer '+ user.api_token,
                }
            })
            .then((res) => {
                localStorage.setItem('store', res.data.data)
                router.push({
                    name: 'cashier'
                })
            })
            .catch((err) => {
                validation.value = err.response.data
            });
        }
        return {
            stores,
            validation,
            store,
            router
        }
    }
}
</script>

<style>

</style>
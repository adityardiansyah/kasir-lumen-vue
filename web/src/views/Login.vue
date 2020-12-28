<template>
    <div class="container d-flex justify-content-center" style="margin-top:12%">
        <div class="card border-0">
            <div class="row d-flex vertical-center">
                <div class="col image-login">
                    <div class="card1 pb-5">
                        <div class="row px-3 justify-content-center mt-4 mb-5 border-line"> <img
                                src="https://i.imgur.com/uNGdWHi.png" class="image"> </div>
                    </div>
                </div>
                <div class="col">
                    <form @submit.prevent="store()">
                        <div class="card border-0 px-4 py-5">
                            <div v-if="validation.status == 404" class="alert alert-danger" role="alert">
                                {{ validation.data.message }}
                            </div>
                            <div class="row px-3"> <label class="mb-1">
                                    <h6 class="mb-0 text-sm">Email Address</h6>
                                </label> <input class="input" type="text" placeholder="Enter a valid email address" autocomplete="off" v-model="akun.email">
                                     <div v-if="validation.email" class="text-danger">
                                        {{ validation.email[0] }}
                                    </div>
                                     </div>
                            <div class="row px-3 mt-4"> <label class="mb-1">
                                    <h6 class="mb-0 text-sm">Password</h6>
                                </label> <input type="password" class="input" v-model="akun.password" placeholder="Enter password">
                                <div v-if="validation.password" class="text-danger">
                                    {{ validation.password[0] }}
                                </div>
                            </div>
                            <div class="row px-3 mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        <h6 class="mb-0 text-sm">Remember me</h6>
                                    </label>
                                </div>
                                <!-- <a href="#" class="ml-auto mb-0 text-sm">Forgot Password?</a> -->
                            </div>
                            <div class="row mb-3 px-3"> 
                                <button class="btn btn-primary text-center">Login</button> 
                            </div>
                            <div class="row mb-4 px-3"> <small class="font-weight-bold">Don't have an account? <a class="text-danger ">Register</a></small> </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios'
import { reactive, ref } from 'vue'
import { useRouter } from 'vue-router'

export default {
    setup(){
        const akun = reactive({
            email: '',
            password: ''
        })
        const validation = ref([])
        const router = useRouter()

        function store() {
            axios.post(
                'http://127.0.0.1:8000/login',
                akun
            )
            .then((res) => {
                // console.log(res.data.data);
                localStorage.setItem('user', JSON.stringify(res.data.data));
                
                if(localStorage.getItem('user') != null){
                    router.push({
                        name: 'index'
                    })
                }
            })
            .catch((err) => {
                if(err.response.status == 422){
                    validation.value = err.response.data.data
                }else{
                    validation.value = err.response
                }
            })
        }

        return {
            akun,
            validation,
            router,
            store
        }
    }
}
</script>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat&display=swap');

    body {
        color: #000;
        overflow-x: hidden;
        height: 100%;
        background-color: #ffffff;
        background-repeat: no-repeat;
        font-family: 'Montserrat', sans-serif !important;
    }

    .border-line {
        border-right: 1px solid #EEEEEE
    }


    .or {
        width: 10%;
        font-weight: bold
    }

    .text-sm {
        font-size: 14px !important
    }

    ::placeholder {
        color: #BDBDBD;
        opacity: 1;
        font-weight: 300
    }

    :-ms-input-placeholder {
        color: #BDBDBD;
        font-weight: 300
    }

    ::-ms-input-placeholder {
        color: #BDBDBD;
        font-weight: 300
    }

    .input,
    textarea {
        padding: 10px 12px 10px 12px;
        border: 1px solid lightgrey;
        border-radius: 2px;
        margin-bottom: 5px;
        margin-top: 2px;
        width: 100%;
        box-sizing: border-box;
        color: #2C3E50;
        font-size: 14px;
        letter-spacing: 1px;
        font-family: 'Montserrat';
    }

    input:focus,
    textarea:focus {
        -moz-box-shadow: none !important;
        -webkit-box-shadow: none !important;
        box-shadow: none !important;
        border: 1px solid #304FFE;
        outline-width: 0
    }

    a {
        color: inherit;
        cursor: pointer
    }

    .btn-blue {
        background-color: #1A237E;
        width: 150px;
        color: #fff;
        border-radius: 2px
    }

    .btn-blue:hover {
        background-color: #000;
        cursor: pointer
    }

    .bg-blue {
        color: #fff;
        background-color: #1A237E
    }

    @media screen and (max-width: 991px) {
        .logo {
            margin-left: 0px
        }

        .image {
            width: 300px;
            height: 220px
        }

        .image-login {
            display: none;
        }

        .border-line {
            border-right: none
        }
    }
</style>
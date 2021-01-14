import axios from 'axios'
import { reactive, ref } from 'vue'

var store = JSON.parse(localStorage.getItem('store'));
var user = JSON.parse(localStorage.getItem('user'));
var header = {
    'Authorization': 'Bearer '+user.api_token,
    'Store': store.token
};

export default async function getCategory(){
    await axios.get('http://127.0.0.1:8000/category', {headers: header})
    .then((res) => {
        categories.value = res.data.data
    })
    .catch((err) => {
        console.log(err);
    })
}   
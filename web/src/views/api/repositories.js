import axios from 'axios'
import { reactive, ref } from 'vue'



async function getCategory() {
    const categories = ref([]);

    await axios.get('http://127.0.0.1:8000/category', {headers: header})
    .then((res) => {
        categories.value = res.data.data
    })
    .catch((err) => {
        console.log(err);
    })
    return {categories}
}

export { getCategory }


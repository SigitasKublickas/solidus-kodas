<script setup lang="ts">
import axios from 'axios';
import { resetFileInput } from '~/helpers';
const file = ref<File | null>(null);
const fileName = ref<string>("");
const formXmlData = ref("");
const category = ref<Number| null>(null);

const showAlertSuccess = ref(false);
const showAlertError = ref(false);

const categories = ref<{value:string, name:string}[]>([]);

(async ()=>{
    try{
        const {data: result} = await useFetch<{value:string, name:string}[]>('http://localhost:8000/categories/get/withoutChild');
        if(result&& result.value){
            categories.value = result.value;
        }
    }catch(e){
        console.error(e);
    }
})();

function showSuccess () {
    showAlertSuccess.value = true;
    formXmlData.value = '';
    file.value = null;
    category.value = null;
    setTimeout(() => showAlertSuccess.value = false, 3000);
}
function showError () {
    showAlertError.value = true;
    setTimeout(() => showAlertError.value = false, 3000);
}


const uploadImage = async () => {
    try {
        const fileValue = file.value;
        if (!fileValue) {
        throw new Error('No file selected.');
        }
        const formData = new FormData();
        formData.append("file" , fileValue , fileName.value);

        const response = await axios.post<{ filename: string, filepath: string }>('/api/upload', formData, {
        headers: {
            'Content-Type': 'multipart/form-data'
        }
        });
        return response;
    } catch (error: any) {
        return {status:error.request.status , data:error.request.statusText};
    }
}

async function onSubmitXML(e:any){
    e.preventDefault();
    await useFetch(
        "http://localhost:8000/sanctum/csrf-cookie",
        {credentials :"include"}
    );

    try{
        const {data,error} = await useFetch(
        "http://localhost:8000/products/store/xml",
        {
            credentials:"include",
            headers:{
               
                'Content-Type': 'application/xml',
                "x-xsrf-token" :useCookie("XSRF-TOKEN")
            },
            method: "POST",
            body:formXmlData.value
        }
    );
        if(data&&  data.value){
            uploadImage();
            showSuccess();
            resetFileInput();
        }else if(error){
            showError();
        }
    }
    catch(error){
        showError();
        console.error(error);
    }
}
let xmlSample = "";

const onFileChange = (event: Event) => {
  const target = event.target as HTMLInputElement;
  if (target.files && target.files.length > 0) {
    file.value = target.files[0];
    fileName.value =  `${Date.now()}-${file.value?.name}`
  }
};
watch(file, ()=>{
    if(file.value){
        xmlSample=`
                <products>
                    <product>
                        <name>Pavadinimas</name>
                        <desc>Aprašymas</desc>
                        <price>Kaina (skaičius)</price>
                        <delivery_time>Pristatymo laikas (skaičius)</delivery_time>
                        <stock>Kiekis (skaičius)</stock>
                        //automatiškai užpildyta
                        <img_url>${fileName.value}</img_url>
                        <category_id>Kategorijos id</category_id>
                        <brand>Prekės ženklas</brand>
                        <model>Modelis</model>
                        <condition>Būklė</condition>
                    </product>
                </products>
            `;
    }
})

</script>

<template>
   <div class="flex justify-center content-center w-full h-auto flex-col flex-wrap pt-12">
    <div class="container flex justify-center items-center flex-col">
            <h1 class="text-gray-900 text-3xl title-font font-medium mb-1 pt-12">Sukurti Produktą Su XML</h1>
            <NuxtLink to="/product/create" class="text-white mt-12 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Sukurti su įvestimis</NuxtLink>
            <div class="container pt-12 gap-6 mb-6 md:grid-cols-2 p-2">
            <div class="pt-12">
                <label for="img_url" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Produkto Nuotrauka</label>
                <input type="file" accept="image/png , image/jpeg" @change="onFileChange" id="file" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required />
            </div>
            <div v-if="file">
                XML pavizdys:
                <p>{{ xmlSample }}</p>
            </div>
            <div v-if="file" class="mb-6 pt-4">
                <label for="category_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kategorija</label>
                <select  v-model="category" name="category_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option  v-for='item in categories' :key="item.name" :value="item.value">{{ item.name }}</option>
                </select>
            </div> 
            <p v-if="category">Jūsu pasirinktos kategorijos id: {{ category }}</p>
            <form @submit="onSubmitXML" class="pt-12" v-if="file">
                <label for="product" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Produkto xml</label>
                <textarea v-model="formXmlData" name="textarea" id="product" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Produkto xml"></textarea>
                <div class="mb-6 p-2">
                    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Sukurti</button>
                </div> 
            </form>
            </div>
        </div>
    </div>
        <div class="relative w-full h-12 flex flex-wrap content-center items-center">
            <AlertSuccess v-if="showAlertSuccess" msg="Produktas sukurtas sėkmingai!" />
            <AlertError v-if="showAlertError" msg="Patikrinkite užpildytus laukus!" />
        </div>
</template>
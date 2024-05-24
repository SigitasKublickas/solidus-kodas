<script setup lang="ts">
import axios from 'axios';

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

const conditionArr=[{value:"New",name:"Naujas"},{value:"Used",name:"Naudotas"},{value:"Refurbished",name:"Atnaujintas"}];

interface Indexable {
  [key: string]: string | number; 
}

const file = ref<File | null>(null);

const emptyFormData = {
  name: "",
  desc: "",
  price: 0.1,
  delivery_time: 1,
  stock: 1,
  brand: "",
  model: "",
  condition: "New",
  category_id: 0,
}

const formData = ref<any>({...emptyFormData});
const showAlertSuccess = ref(false);
const showAlertError = ref(false);

function showSuccess () {
    showAlertSuccess.value = true;
    setTimeout(() => showAlertSuccess.value = false, 3000);
}
function showError () {
    showAlertError.value = true;
    setTimeout(() => showAlertError.value = false, 3000);
}

function onStringChange(value:{value:string,name:string}){
    formData.value = { ...formData.value, [value.name]: value.value };
}
function onNumberChange(value:{value:string,name:string}){
    formData.value = { ...formData.value, [value.name]: value.value };
}
async function uploadPicture (fileName:string){
    try {
        const fileValue = file.value;
        if (!fileValue) {
        throw new Error('No file selected.');
        }
        const formData = new FormData();
        formData.append("file" , fileValue , fileName);

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

async function onSubmit(e:any){
    e.preventDefault();
    const fileName = `${Date.now()}-${file.value?.name}`
       
    await useFetch(
        "http://localhost:8000/sanctum/csrf-cookie",
        {credentials :"include"}
    );
    
    const jsonBody = JSON.stringify({...formData.value ,img_url:fileName});

    try{
        
        const {data , error} = await useFetch(
        "http://localhost:8000/products",
        {
            credentials:"include",
            headers:{
                accept: "application/json",
                "x-xsrf-token" :useCookie("XSRF-TOKEN")
            },
            method: "POST",
            body:jsonBody
        }
    );
        if(data && data.value){
            const upload = await uploadPicture(fileName);
            if(upload.status ==200){
                formData.value = {...emptyFormData};
                file.value = null;
                resetFileInput();
                showSuccess(); 
            }
        }
        else if(error){
            showError();
        }
    }
    catch(error:any){
        showError();
        return {status:error.request.status , data:error.request.statusText};
    }

}

const onFileChange = (event: Event) => {
  const target = event.target as HTMLInputElement;
  if (target.files && target.files.length > 0) {
    file.value = target.files[0];
  }
};
function resetFileInput() {
  const fileInput = document.getElementById('file') as HTMLInputElement;
  if (fileInput) {
    fileInput.value = ''; 
  }
}
</script>

<template>
<div class="flex justify-center content-center w-full h-auto flex-col flex-wrap">
    <div class="container flex justify-center items-center flex-col">
    <h1 class="text-gray-900 text-3xl title-font font-medium mb-1 pt-12">Sukurti Produktą</h1>
    <NuxtLink to="/product/create/xml" class= "text-white mt-12 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Sukurti su xml</NuxtLink>
    
        <div class="container pt-12">
            <form ref="anyName" @submit="onSubmit">
            
                <div class="grid gap-6 mb-6 md:grid-cols-2 p-2">
                    
                    <InputString type="text" name="name" placeholder="Pavadinimas" @change="onStringChange" :value="formData.name"/>
                    <InputString type="text" name="desc" placeholder="Aprašymas" @change="onStringChange" :value="formData.desc" />
                    <InputNumber type="number" name="price" placeholder="Kaina" :min="0.1" :max="5" :step="0.1" @change="onNumberChange" :value="formData.price" />
                    <InputNumber type="number" name="delivery_time" placeholder="Pristatymo laikas" :min="1" :max="15" :step="1" @change="onNumberChange" :value="formData.delivery_time" />
                    <div>
                        <label for="img_url" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Produkto Nuotrauka</label>
                        <input id="file" type="file" accept="image/png , image/jpeg" @change="onFileChange" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required />
                    </div>
                    <InputNumber type="number" name="stock" placeholder="Kiekis" :min="1" :max="100" :step="1" @change="onNumberChange" :value="formData.stock" />
                    <InputString type="text" name="brand" placeholder="Prekės ženklas" @change="onStringChange" :value="formData.brand"/>
                    <InputString type="text" name="model" placeholder="Modelis" @change="onStringChange" :value="formData.model"/>
                    <div>
                    <label for="condition" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Būklė</label>
                    <select  v-model="formData.condition" name="condition" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option  v-for='item in conditionArr' :key="item.name" :value="item.value">{{ item.name }}</option>
                    </select>
                </div>
                </div>
                <div class="mb-6 p-2">
                    <label for="category_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kategorija</label>
                    <select  v-model="formData.category_id" name="category_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option  v-for='item in categories' :key="item.name" :value="item.value">{{ item.name }}</option>
                    </select>
                </div> 
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
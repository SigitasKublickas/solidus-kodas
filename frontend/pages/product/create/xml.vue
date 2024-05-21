<script setup lang="ts">
import axios from 'axios';
const file = ref<File | null>(null);
const formXmlData = ref();
    async function onSubmitXML(e:any){
    e.preventDefault();

    let fName = "";
    await useFetch(
        "http://localhost:8000/sanctum/csrf-cookie",
        {credentials :"include"}
    );

    try {
        const response = await axios.post<{ filename: string, filepath: string }>('/api/upload', {file:file.value}, {
        headers: {
            'Content-Type': 'multipart/form-data'
        }
        });
            fName = response.data.filename;
        } catch (error) {
            console.error('Error uploading file:', error);
    }

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
    console.log(data);
    }
    catch(error){
        console.error(error);
    }
}
let xmlSample = "";
const onFileChange = (event: Event) => {
  const target = event.target as HTMLInputElement;
  if (target.files && target.files.length > 0) {
    file.value = target.files[0];
  }
};
watch(file, ()=>{
    xmlSample=`
<products>
    <product>
        <name>Pavadinimas</name>
        <desc>Aprašymas</desc>
        <price>Kaina (skaičius)</price>
        <delivery_time>Pristatymo laikas (skaičius)</delivery_time>
        <stock>Kiekis (skaičius)</stock>
        //automatiškai užpildyta
        <img_url>${file.value?.name}</img_url>
        <category_id>Kategorijos id</category_id>
        <brand>Prekės ženklas</brand>
        <model>Modelis</model>
        <condition>Būklė</condition>
    </product>
</products>
`;
})
</script>

<template>
    <div class="flex justify-center content-center w-full h-auto flex-col">
        <div class="container pt-12 p-2 flex justify-center items-center">
            <h1 class="text-gray-900 text-3xl title-font font-medium mb-1">Sukurti Produktą Su XML</h1>
        </div>
        <div class="container pt-12 grid gap-6 mb-6 md:grid-cols-2 p-2">
            <NuxtLink to="/product/create" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Sukurti su įvestimis</NuxtLink>
        </div>
        <div class="container pt-12 gap-6 mb-6 md:grid-cols-2 p-2">
            <div class="pt-12">
                <label for="img_url" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Produkto Nuotrauka</label>
                <input type="file" accept="image/png" @change="onFileChange" id="first_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required />
            </div>
            <div v-if="file">
                XML pavizdys:
                <p>{{ xmlSample }}</p>
            </div>
            <form @submit="onSubmitXML" class="pt-12" v-if="file">
                <label for="product" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Produkto xml</label>
                <textarea v-model="formXmlData" name="textarea" id="product" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Produkto xml"></textarea>
                <div class="mb-6 p-2">
                    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Sukurti</button>
                </div> 
            </form>

        </div>
    </div>
</template>
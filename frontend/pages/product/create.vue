<script setup lang="ts">


const {data: categories} = await useFetch<{value:string, name:string}[]>('http://localhost:8000/categories/get/withoutChild');

const conditionArr=[{value:"New",name:"Naujas"},{value:"Used",name:"Naudotas"},{value:"Refurbished",name:"Atnaujintas"}];

interface Indexable {
  [key: string]: string | number; 
}

const formData = ref<Indexable>({
  name: "",
  desc: "",
  price: 0,
  delivery_time: 0,
  stock: 0,
  brand: "",
  model: "",
  img_url:"",
  condition: "New",
  category_id: 0,
});


function onChange(value:{value:string,name:string}){
    formData.value[value.name] = value.value;
}

async function onSubmit(e:any){
    e.preventDefault();

    await useFetch(
        "http://localhost:8000/sanctum/csrf-cookie",
        {credentials :"include"}
    );

    
    const jsonBody = JSON.stringify(formData.value);

    const {data,error} = await useFetch(
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
    console.log({data,error});
}

</script>

<template>
<div class="flex justify-center content-center w-full h-auto flex-col">
    <h1 class="text-gray-900 text-3xl title-font font-medium mb-1">Sukurti Produktą</h1>
    <div class="container pt-12">
        <form @submit="onSubmit">
            <div class="grid gap-6 mb-6 md:grid-cols-2 p-2">
                <Input type="text" name="name" placeholder="Pavadinimas" @change="onChange"/>
                <Input type="text" name="desc" placeholder="Aprašymas" @change="onChange" />
                <Input type="number" name="price" placeholder="Kaina" @change="onChange"  />
                <Input type="number" name="delivery_time" placeholder="Pristatymo laikas" @change="onChange"  />
                <Input type="string" name="img_url" placeholder="Pristatymo laikas" @change="onChange"  />
                <Input type="number" name="stock" placeholder="Kiekis" @change="onChange"/>
                <Input type="text" name="brand" placeholder="Prekės ženklas" @change="onChange"/>
                <Input type="text" name="model" placeholder="Modelis" @change="onChange"/>
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
                <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>
            </div> 
        </form>

    </div>
</div>
</template>
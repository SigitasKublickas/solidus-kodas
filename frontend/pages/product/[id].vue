<script setup lang="ts">
const route = useRoute();

const product = ref<Product>();
(async () =>{
  try{
    const { data: result } = await useFetch<Product>(`http://localhost:8000/products/${route.params.id}`);
    console.log(result.value);
   if(result.value){
    product.value = result.value;
   }else{
    console.log("klaida!")
   }
  }catch(e){
    console.error(e);
  }
})();

</script>

<template>
<section class="text-gray-600 body-font overflow-hidden" v-if="product">
  <div class="container px-5 py-24 mx-auto">
    <div class="lg:w-4/5 mx-auto flex flex-wrap">
      <img alt="ecommerce" class="lg:w-1/2 w-full lg:h-auto h-64 object-cover object-center rounded" :src="`/images/${product.img_url}`">
      <div class="lg:w-1/2 w-full lg:pl-10 lg:py-6 mt-6 lg:mt-0">
        <h2 class="text-sm title-font text-gray-500 tracking-widest">{{product.brand}}</h2>
        <h1 class="text-gray-900 text-3xl title-font font-medium mb-1">{{product.model}}</h1>
        <div class="flex mt-6 pb-5 border-b-2 border-gray-100 mb-5 flex-col">
            <span class=" text-m text-gray-900">Kiekis: {{product.stock}}</span>
            <span class=" text-m text-gray-900">Kondicija: {{product.condition}}</span>
            <span class=" text-m text-gray-900">Pristatymo laikas: {{product.delivery_time}}</span>
        </div>
        <p class="leading-relaxed">{{product.desc}}</p>
        <div class="flex pt-4">
            <span class="title-font font-medium text-2xl text-gray-900">{{product.price}}€</span>
          <button class="flex ml-auto text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded">Į krepšelį</button>
        </div>
      </div>
    </div>
  </div>
</section>
  <section v-else>Klaida!!</section>
</template>
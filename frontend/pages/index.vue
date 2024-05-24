<script setup lang="ts">
</script>


<template>
   <div class="main flex justify-center items-center flex-col pt-20">
      <div class="container flex justify-center items-center flex-col">
      <h1 class="mb-4 text-4xl font-extrabold leading-none tracking-tight text-gray-900 md:text-5xl lg:text-6xl dark:text-white">Sveiki atvykę!!</h1>
      <p class="mb-6 text-lg font-normal text-gray-500 lg:text-xl sm:px-16 xl:px-48 dark:text-gray-400">Elektorininė parduotuvė iki 5 eurų!!</p>
      <div class="flex flex-col gap-y-5 lg:flex-row lg:gap-x-5" >
         <NuxtLink to="/product/create" class="inline-flex items-center justify-center px-5 py-3 text-base font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-900">Sukurti Produkta</NuxtLink>
         <NuxtLink to="/category" class="inline-flex items-center justify-center px-5 py-3 text-base font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-900" >Kategorijos</NuxtLink>
      </div>
   </div>
</div>
</template>
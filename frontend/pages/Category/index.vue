<script lang="ts" setup>

const categories = ref<Category[]>([]);

(async () => {
  try{
    const {data: result} = await useFetch<Category[]>('http://127.0.0.1:8000/categories');
    result.value ?categories.value = result.value : categories.value=[];
  }
  catch(e){
    console.error(e);
  }
})();
</script>

<template>
  <section class="text-gray-600 body-font">
    <CategoryMap :cat_child="categories"/>
  </section>
</template>
<script lang="ts" setup>

const categories = ref<Category>();

(async () => {
  try{
    const {data: result} = await useFetch<Category>('http://127.0.0.1:8000/categories');
    if(result.value){categories.value = result.value}
  }
  catch(e){
    console.error(e);
  }
})();
const notFound = ref<string>("")
  watch(
  () => categories,
  (newProduct) => {
    if (!newProduct) {
      setTimeout(() => {
        notFound.value = 'Kategoriju nerasta!';
      }, 600);
    }
  },
  { immediate: true }
);
</script>

<template>
  <section class="text-gray-600 body-font">
    <div v-if="!categories">{{notFound}}</div>
    <CategoryMap v-else :category="categories"/>
  </section>
</template>
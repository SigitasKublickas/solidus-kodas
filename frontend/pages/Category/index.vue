<script lang="ts" setup>
const {data:categories}=useFetch<Category[]>('http://127.0.0.1:8000/categories');

</script>

<template>
  <section class="text-gray-600 body-font">
    <CategoryMap :cat_child="categories"/>
  </section>
</template>
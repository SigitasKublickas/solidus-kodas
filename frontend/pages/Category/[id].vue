<script setup lang="ts">

const route = useRoute();
const { data: categories }=useFetch<Category>(`http://localhost:8000/categories/${route.params.id}`);
console.log(categories);
</script>

<template>
  <div>
    <div v-if=" categories && categories.cat_childs.length !==0 ">
      <CategoryMap :category="categories" />
    </div>
    <div v-else>
      <SidebarFilter />
    </div>
  </div>
</template>

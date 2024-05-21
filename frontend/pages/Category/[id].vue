<script setup lang="ts">

const route = useRoute();
const { data: categories }=useFetch<Category[]>(`http://localhost:8000/categories/${route.params.id}`);

</script>

<template>
  <div>
    <div v-if="categories && categories.length !==0">
      <CategoryMap :cat_child="categories" />
    </div>
    <div v-else>
      <SidebarFilter />
    </div>
  </div>
</template>

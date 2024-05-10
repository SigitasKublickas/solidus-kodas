<script setup lang="ts">

const route = useRoute();
const {data:data}=useFetch<{type:string, data:Product[]|Category[]}>(`http://localhost:8000/categories/showChildCategories/${route.params.id}`);

console.log(data);
</script>

<template>
  <div>
    <div v-if="data && data.type === 'child_categories'">
      <!-- Display child categories -->
      <CategoryMap :cat_child="data.data" />
    </div>
    
    <div v-else-if="data && data.type === 'products'">
      <!-- Display products -->
      <ProductMap :products="data.data" />
    </div>
    
    <div v-else>
      Loading...
    </div>
  </div>
</template>

<script setup lang="ts">

const route = useRoute();

const categories = ref<Category>();

(async () => {
  try{
    const {data: result} = await useFetch<Category>(`http://localhost:8000/categories/${route.params.id}`);
    if(result.value){categories.value = result.value}
  }
  catch(e){
    console.error(e);
  }
})();


</script>

<template>
  
    <div v-if=" categories && categories.cat_childs.length !==0 ">
      <CategoryMap :category="categories" />
    </div>
    <div v-else>
      <SidebarFilter />
    </div>
</template>

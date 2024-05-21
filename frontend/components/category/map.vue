<script setup lang="ts">
const props = defineProps({
  cat_child:{
    type: Array<Category>,
    required:true
  }
});

</script>
<template>
     <div class="container px-5 py-24 mx-auto">
      <div class="flex parent flex-wrap -m-4" >
          <div class="p-4 md:w-1/3 w-full" v-for="item in props.cat_child" :key="item.id">
            <CategoryBlock :name="item.name" :img="`/images/${item.img_url}`" :path="item.path" :cat_childs="item.cat_childs"/>
          </div>
      </div>
    </div>
</template>
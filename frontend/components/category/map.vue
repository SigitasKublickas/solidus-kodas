<script setup lang="ts">
const props = defineProps<{
  category: Category | Category [];
}>();
</script>
<template>
     <div class="container px-5 py-24 mx-auto">
      <!-- <h1 class="mb-20 text-4xl font-bold leading-none tracking-tight text-gray-900 md:text-5xl lg:text-4xl dark:text-white" >{{ props.category.name }}</h1> -->
      <div class="flex parent flex-wrap -m-4" >
          <div v-if="Array.isArray(props.category)" class="p-4 md:w-1/3 w-full" v-for="item in props.category" :key="item.id">
            <CategoryBlock :name="item.name" :img="`/images/${item.img_url}`" :path="item.path" :cat_childs="item.cat_childs"/>
          </div>
          <div v-else  class="p-4 md:w-1/3 w-full" v-for="item_child in props.category.cat_childs" :key="item_child.id">
            <CategoryBlock :name="item_child.name" :img="`/images/${item_child.img_url}`" :path="item_child.path" :cat_childs="item_child.cat_childs"/>
          </div>
      </div>
    </div>
</template>
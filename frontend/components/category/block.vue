<script setup lang="ts">
const props = defineProps({
  name: {
    type: String,
    required: true,
  },
  img:{
    type: String,
    required: true,
  },
  path:{
    type: String,
    required: true,
  },
  cat_childs:{
    type:Array<Category>, 
    required:false,
  }
});

</script>

<template>
      <div class="h-full border-2 border-gray-200 border-opacity-60 rounded-lg overflow-hidden">
        <NuxtLink :to="`/category/${props.path}`">
          <img class="lg:h-48 md:h-36 w-full object-contain object-center " :src='props.img':alt="`${props.name}`">
          <div class="p-6">
            <h1 class="title-font text-lg font-medium text-gray-900 mb-3">{{props.name}}</h1>
            <ul v-for="item in props.cat_childs" :key="item.id">
                <li><NuxtLink :to="`/category/${item.path}`">{{ item.name }}</NuxtLink></li>
            </ul>
          </div>
        </NuxtLink>
      </div>
</template>
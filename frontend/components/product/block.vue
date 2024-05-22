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
  desc:{
    type: String,
    required: true,
  },
  id: {
    type: Number,
    required: true,
  },
  price: {
    type: Number,
    required: true,
  },
  
});
</script>

<template>
    <div class="h-full border-2 border-gray-200 border-opacity-60 rounded-lg overflow-hidden">
        <NuxtLink :to="`/product/${props.id}`">
        <img class="lg:h-48 md:h-36 w-full object-contain object-center" :src="props.img" :alt="`${props.name}`">
            <div class="p-6">
                <h1 class="title-font text-lg font-medium text-gray-900 mb-3">{{props.name}}</h1>
                <p>{{ props.desc }}</p>
            </div>
        </NuxtLink>
    </div>
</template>
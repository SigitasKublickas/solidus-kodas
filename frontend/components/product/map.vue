<script setup lang="ts">
const props = defineProps<{
  products:Product[] |null;
}>();

</script>
<template>
     <div class="container px-5 mx-auto">
      <div class="flex parent flex-wrap -m-4" >
        <div v-if="props.products?.length ==0">Produktu nerasta!</div>
        <div v-else class="p-4 md:w-1/3 w-full" v-for="item in props.products" :key="item.id">
          <ProductBlock :name="item.name" :img="`/images/${item.img_url}`" :desc="item.desc" :id="item.id" :price="item.price"   />
        </div>
      </div>
    </div>
</template>
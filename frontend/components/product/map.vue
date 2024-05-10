<script setup lang="ts">
const props = defineProps<{
  products:Product[] | null | undefined;
}>();

</script>
<template>
     <div class="container px-5 py-24 mx-auto">
      <div class="flex parent flex-wrap -m-4" >
          <div class="p-4 md:w-1/3" v-for="item in props.products" :key="item.id">
            <ProductBlock :name="item.name" :img="item.img_url" :desc="item.desc" :id="item.id" :price="item.price"   />
          </div>
      </div>
    </div>
</template>
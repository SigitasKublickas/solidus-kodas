<script setup lang="ts">
const props = defineProps({
  name: {
    type: String,
    required: true,
  },
  options:{
    type:Array<{value:string, name:string}> ,
    required: true, 
  }
});
const arr = [{value:"", name:"Pasirinkite..."},...props.options];
</script>

<template>
    <div>
        <label for="countries" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{props.name}}</label>
        <select id="countries" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            <option v-for='item in arr' :key="item.name" :value="item.value">{{ item.name }}</option>
        </select>
    </div>
</template>
<script setup lang="ts">

const props = defineProps({
 name:{
    type:String,
    require:true
 },
 placeholder:{
    type:String,
    require:true
 },
 type:{
    type:String,
    require:true
 },
 value:{
   type:String||Number,
   require:true
 }
});

const value = ref("");

const emit = defineEmits(['change']);

const change = (value:string) =>{
   emit('change' , {value:value , name:props.name});
}
watch(()=>props.value,(newValue)=>{
   console.log(newValue);
   if(newValue){
   value.value = newValue;
   }
})


</script>

<template>
    <div>
        <label :for="props.name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{props.placeholder}}</label>
        <input step="any" v-model="value" v-on:change="change(value)" :type="$props.type" id="first_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" :placeholder="props.placeholder" required />
    </div>
</template>
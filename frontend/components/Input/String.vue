<script setup lang="ts">


const props = defineProps({
  name: {
    type: String,
    required: true
  },
  placeholder: {
    type: String,
    required: true
  },
  value: {
    type: String,
    required: true
  }
});

const value = ref<string>(props.value);

const emit = defineEmits(['change']);

const handleChange = (event: Event) => {
  const target = event.target as HTMLInputElement;
  const newValue = target.value;
  value.value = newValue;
  emit('change', { value: newValue, name: props.name });
};

watch(() => props.value, (newValue) => {
  if (newValue !== undefined) {
    value.value = newValue;
  }else{
    value.value ="";
  }
});
</script>

<template>
  <div>
    <label :for="props.name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ props.placeholder }}</label>
    <input
      step="any"
      max="255"
      v-model="value"
      @change="handleChange"
      type="text"
      :id="props.name"
      :placeholder="props.placeholder"
      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
      required
    />
  </div>
</template>
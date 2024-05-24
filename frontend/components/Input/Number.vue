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
  min:{
    type: Number,
    required: true
  },
  max:{
    type: Number,
    required: true
  },
  step:{
    type: Number,
    required: true
  },
  value: {
    type: Number,
    required: true
  }
});

const value = ref<number>(props.value ?? props.min);

const emit = defineEmits(['change']);

const handleChange = (event: Event) => {
  const target = event.target as HTMLInputElement;
  let newValue = parseFloat(target.value);
  if (isNaN(newValue)) {
    newValue = 0;
  }
  // Ensure the value stays within the allowed range
  if (newValue < props.min) {
    newValue = props.min;
  } else if (newValue > props.max) {
    newValue = props.max;
  }
  value.value = newValue;
  emit('change', { value: newValue, name: props.name });
};

watch(() => props.value, (newValue) => {
  if (newValue !== undefined) {
    value.value = newValue;
  } else {
    value.value = props.min;
  }
});
</script>

<template>
  <div>
    <label :for="props.name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ props.placeholder }}</label>
    <input
      :step="props.step"
      :min="props.min"
      :max="props.max"
      v-model="value"
      @change="handleChange"
      type="number"
      :id="props.name"
      :placeholder="props.placeholder"
      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
      required
    />
  </div>
</template>
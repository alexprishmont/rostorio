<template>
  <div
    :class="className"
    class="border border-gray-300 rounded-md px-3 py-2 shadow-sm focus-within:ring-1 focus-within:ring-cyan-600 focus-within:border-cyan-600"
  >
    <label
      :for="idName"
      class="block text-xs font-medium text-gray-900"
    >
      <slot />
    </label>

    <select
      :id="idName"
      class="block w-full border-0 p-0 text-gray-900 placeholder-gray-500 focus:ring-0 sm:text-sm"
      :required="isRequired"
      :value="modelValue"
      @input="handleInput"
    >
      <option
        v-for="item in items"
        :key="item.value"
        :value="item.value"
      >
        {{ item.title }}
      </option>
    </select>
  </div>
</template>

<script setup>
import {onMounted, ref} from 'vue';

const props = defineProps({
    modelValue: String,
    className: String,
    idName: String,
    list: Object,
    isRequired: Boolean,
});

const emit = defineEmits(['update:modelValue', 'change']);
const items = ref(props.list);

const handleInput = event => {
    emit('update:modelValue', event.target.value);
    emit('change', event);
};

onMounted(() => {
    items.value = items.value.map(item => {
        const itemTitle = item.replaceAll('_', ' ');

        return {
            title: itemTitle.charAt(0).toUpperCase() + itemTitle.slice(1),
            value: item,
        };
    });
});
</script>

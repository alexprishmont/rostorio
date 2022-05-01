<template>
  <div>
    <div
      v-show="!editing"
      :class="className"
      class="flex transition cursor-pointer hover:text-cyan-500"
      data-tip="Click to edit"
      @click="editing = true"
      @mouseover="hover = true"
      @mouseout="hover = false"
    >
      {{ showText }}

      <PencilIcon
        v-show="hover"
        class="ml-2 m-1/2 w-3 h-3"
      />
    </div>

    <div v-show="editing">
      <select
        class="form-select bg-white border border-gray-300 rounded-md shadow-sm focus-within:ring-1 focus-within:ring-cyan-600 focus-within:border-cyan-600"
        :value="modelValue"
        @input="handleInput"
        @keydown.enter="keyDown"
      >
        <option
          v-for="(option, index) in optionsList"
          :key="index"
          :value="option.name"
        >
          {{ option.name }}
        </option>
      </select>
    </div>
  </div>
</template>

<script setup>
import {ref, defineEmits} from 'vue';
import { PencilIcon } from '@heroicons/vue/solid';

const props = defineProps({
    optionsList: Array,
    name: String,
    modelValue: String,
    className: String,
});

const editing = ref(false);
const hover = ref(false);
const showText = ref(props.modelValue ?? 'Click to Add');
const emit = defineEmits(['update:modelValue', 'finish']);

const handleInput = event => {
    emit('update:modelValue', event.target.value);
    showText.value = event.target.value ?? 'Click to Add';
};

const keyDown = () => {
    editing.value = false;
    emit('finish', {
        name: props.name,
    });
};
</script>

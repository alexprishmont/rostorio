<template>
  <TransitionRoot
    :show="show"
    appear
    as="template"
  >
    <Dialog
      as="div"
    >
      <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="min-h-screen px-4 text-center">
          <TransitionChild
            as="template"
            enter="duration-300 ease-out"
            enter-from="opacity-0"
            enter-to="opacity-100"
            leave="duration-200 ease-in"
            leave-from="opacity-100"
            leave-to="opacity-0"
          >
            <DialogOverlay class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
          </TransitionChild>

          <span
            class="inline-block h-screen align-middle"
            aria-hidden="true"
          >
            &#8203;
          </span>

          <TransitionChild
            as="template"
            enter="duration-300 ease-out"
            enter-from="opacity-0 scale-95"
            enter-to="opacity-100 scale-100"
            leave="duration-200 ease-in"
            leave-from="opacity-100 scale-100"
            leave-to="opacity-0 scale-95"
          >
            <div
              class="inline-block w-full max-w-md p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl"
            >
              <DialogTitle
                as="h3"
                class="text-lg font-medium leading-6 text-gray-900"
              >
                <div class="flex justify-between">
                  <slot name="title" />

                  <XIcon
                    class="transition w-4 h-4 mt-1 text-gray-300 cursor-pointer mb-2 hover:text-gray-500"
                    @click="closeModal"
                  />
                </div>
              </DialogTitle>
              <div class="mt-2">
                <slot name="body" />
              </div>

              <div class="mt-4">
                <slot name="footer" />
              </div>
            </div>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
</template>

<script setup>
import { defineEmits } from 'vue';
import {
    TransitionRoot,
    TransitionChild,
    Dialog,
    DialogOverlay,
    DialogTitle,
} from '@headlessui/vue';
import {XIcon} from '@heroicons/vue/solid';

defineProps({
    show: Boolean,
});

const emit = defineEmits(['close']);

const closeModal = () => {
    emit('close');
};
</script>

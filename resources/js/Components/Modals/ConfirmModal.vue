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
                <p class="text-gray-500 text-sm">
                  <slot name="message" />
                </p>
              </div>

              <div class="mt-4 flex justify-between">
                <button
                  class="transition inline-flex items-center px-4 py-2 border border-cyan-500 bg-cyan-500 text-sm leading-4 font-medium rounded-full shadow-sm text-white hover:bg-white hover:text-cyan-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500"
                  type="button"
                  @click="accept"
                >
                  Confirm
                </button>

                <button
                  class="transition inline-flex items-center px-4 py-2 border border-red-500 bg-red-500 text-sm leading-4 font-medium rounded-full shadow-sm text-white hover:bg-white hover:text-red-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                  type="button"
                  @click="deny"
                >
                  Decline
                </button>
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

const emit = defineEmits(['close', 'accept', 'deny']);

const closeModal = () => {
    emit('close');
};

const accept = () => {
    emit('accept');
};

const deny = () => {
    emit('deny');
};
</script>

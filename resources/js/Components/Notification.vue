<template>
  <main
    v-if="show"
    class="fixed inset-0 items-end px-4 py-6 pointer-events-none sm:p-6 sm:items-start"
  >
    <section class="w-full flex flex-col items-center space-y-4 sm:items-end">
      <div class="max-w-sm w-full bg-white shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden">
        <div class="p-4">
          <div class="flex items-start">
            <div class="flex-shrink-0">
              <component
                :is="notification.type === 'success' ? CheckCircleIcon : notification.type === 'error' ? ExclamationCircleIcon : ExclamationIcon"
                :class="{
                  'h-6 w-6 text-green-400': notification.type === 'success',
                  'h-6 w-6 text-red-400': notification.type === 'error',
                  'h-6 w-6 text-orange-400': notification.type === 'warning',
                }"
              />
            </div>
            <div class="ml-3 w-0 flex-1 pt-0.5">
              <p class="text-sm font-medium text-gray-900">
                {{ notification.header }}
              </p>
              <p class="mt-1 text-sm text-gray-500">
                {{ notification.text }}
              </p>
            </div>
            <div class="ml-4 flex-shrink-0 flex">
              <button
                class="bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500"
                @click="show = false"
              >
                <span class="sr-only">Close</span>
                <XIcon class="h-5 w-5" />
              </button>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
</template>

<script setup>
import {defineProps, ref, onMounted, watch} from 'vue';
import { CheckCircleIcon, ExclamationCircleIcon, XIcon,  ExclamationIcon} from '@heroicons/vue/solid';

const props = defineProps({
    notification: Object,
});

const show = ref(false);

onMounted(() => {
    if (props.notification) {
        show.value = true;
        setTimeout(() => show.value = false, 3500);
    }
});

watch(() => props.notification, () => {
    if (props.notification) {
        show.value = true;
        setTimeout(() => show.value = false, 3500);
    }
});
</script>

<template>
  <div
    v-show="Object.keys($page.props.errors).length > 0"
    class="rounded-md bg-red-50 p-4"
  >
    <div class="flex">
      <div class="flex-shrink-0">
        <XCircleIcon class="h-5 w-5 text-red-400" />
      </div>
      <div class="ml-3">
        <h3 class="text-sm font-medium text-red-800">
          <span v-if="Object.keys($page.props.errors).length === 1">There is one error</span>
          <span v-else>There were {{ Object.keys($page.props.errors).length }} errors.</span>
        </h3>
        <div class="mt-2 text-sm text-red-700">
          <ul
            role="list"
            class="list-disc pl-5 space-y-1"
          >
            <li
              v-for="error in $page.props.errors"
              :key="error"
            >
              {{ error }}
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import {XCircleIcon} from '@heroicons/vue/solid';
import {watch} from 'vue';
import {usePage} from '@inertiajs/inertia-vue3';

</script>

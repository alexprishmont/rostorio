<template>
  <div
    v-show="Object.keys($page.props.report).length > 0"
    class="rounded-md bg-orange-50 p-4"
  >
    <div class="flex">
      <div class="flex-shrink-0">
        <ExclamationCircleIcon class="h-5 w-5 text-orange-400" />
      </div>
      <div class="ml-3">
        <h3 class="text-sm font-medium text-orange-800">
          <span v-if="Object.keys($page.props.report).length === 1">Reikalingas dėmesys</span>
          <span v-else>Ataskaita</span>
        </h3>
        <div class="mt-2 text-sm text-orange-700">
          <ul
            role="list"
            class="list-disc pl-5 space-y-1"
          >
            <li
              v-for="report in $page.props.report"
              :key="report"
            >
              {{ report }}
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import {ExclamationCircleIcon} from '@heroicons/vue/solid';
</script>

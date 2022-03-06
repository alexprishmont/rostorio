<template>
  <nav class="flex" aria-label="Breadcrumb">
      <ol role="list" class="flex items-center space-x-4">
          <li>
              <div>
                  <Link href="/dashboard" class="transition text-white hover:text-cyan-100">
                      <HomeIcon class="flex-shrink-0 h-5 w-5"/>
                  </Link>
                  <span class="sr-only">Dashboard</span>
              </div>
          </li>
          <li v-for="breadcrumb in $page.props.breadcrumbs" :key="breadcrumb.title">
              <div class="flex items-center">
                <ChevronRightIcon class="flex-shrink-0 h-5 w-5 text-gray-400"/>
                  <Link :href="breadcrumb.url" class="transition ml-4 text-sm font-medium text-white hover:text-cyan-100">
                      {{ breadcrumb.title }}
                  </Link>
              </div>
          </li>
      </ol>
  </nav>
</template>

<script setup>
import {HomeIcon, ChevronRightIcon} from '@heroicons/vue/solid';
</script>

<template>
    <label for="search" class="sr-only">Search</label>
    <div class="relative text-white focus-within:text-gray-600">
        <div class="pointer-events-none absolute inset-y-0 left-0 pl-3 flex items-center">
            <SearchIcon class="h-5 w-5"/>
        </div>
        <input
            id="search"
            class="block w-full text-white bg-white bg-opacity-20 py-2 pl-10 pr-3 border border-transparent rounded-md leading-5 focus:text-gray-900 placeholder-white focus:outline-none focus:bg-opacity-100 focus:border-transparent focus:placeholder-gray-500 focus:ring-0 sm:text-sm"
            placeholder="Search"
            type="search"
            name="search"
        >
    </div>
</template>

<script setup>
import {SearchIcon} from '@heroicons/vue/solid';
</script>

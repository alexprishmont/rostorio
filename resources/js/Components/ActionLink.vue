<template>
    <Link
        :href="link"
        class="transition focus:outline-none flex p-5 border-b hover:border-sky-500 hover:shadow-lg hover:shadow-sky-500/20"
    >
        <div class="max-w-12 max-h-12 rounded-lg inline-flex p-3 bg-sky-50 text-sky-800 ring-4 ring-white">
            <component :is="icon" class="w-6 h-6"/>
        </div>
        <div class="px-3">
            <h3 class="text-lg font-medium">
                <slot name="title"/>
            </h3>
            <p class="text-sm text-gray-500 font-normal">
                <slot name="description"/>
            </p>
        </div>
    </Link>
</template>

<script setup>
    defineProps({
        link: String,
        icon: Function,
    });
</script>

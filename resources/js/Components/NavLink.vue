<template>
    <Link
        :href="link"
        :class="{
            'text-white bg-opacity-10': $page.url.startsWith(link),
            'text-cyan-100': !$page.url.startsWith(link),
        }"
        class="text-sm font-medium rounded-md bg-white bg-opacity-0 px-3 py-2 hover:bg-opacity-10"
    >
        <slot/>
    </Link>
</template>

<script setup>
defineProps({
    link: String,
})
</script>

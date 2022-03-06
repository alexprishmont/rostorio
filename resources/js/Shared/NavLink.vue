<template>
    <Link
        :href="link"
        :class="{
            'text-white bg-opacity-10': $page.component === component,
            'text-cyan-100': $page.component !== component,
        }"
        class="text-sm font-medium rounded-md bg-white bg-opacity-0 px-3 py-2 hover:bg-opacity-10"
    >
        <slot/>
    </Link>
</template>

<script setup>
defineProps({
    component: String,
    link: String,
})
</script>

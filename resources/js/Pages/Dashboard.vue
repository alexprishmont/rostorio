<template>
    <Head title="Dashboard"/>
    <TwoColumnsLayout showProfileSummary="true">
        <template v-slot:left>
            <h1>Signed!</h1>
        </template>
    </TwoColumnsLayout>
</template>

<script setup>
    import TwoColumnsLayout from '@/Layouts/Authenticated/TwoColumnsLayout';
</script>

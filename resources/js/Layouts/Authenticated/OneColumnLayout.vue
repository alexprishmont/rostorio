<template>
    <Notification :notification="$page.props.flash"/>
    <InitialSetupProcess/>
    <div class="min-h-full">
        <Header/>
        <main class="-mt-24 pb-8">
            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:max-w-7xl lg:px-8">
                <div class="mb-5">
                    <Breadcrumbs/>
                </div>

                <slot/>
            </div>
        </main>
        <Footer/>
    </div>
</template>

<script setup>
    import Notification from '@/Shared/Notification';
    import Header from '@/Shared/Top/Header';
    import Footer from '@/Shared/Bottom/Footer';
    import Breadcrumbs from '@/Shared/Breadcrumbs';
    import InitialSetupProcess from '@/Shared/Account/InitialSetupProcess';
</script>

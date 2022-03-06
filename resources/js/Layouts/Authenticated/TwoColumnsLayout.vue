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
                <div class="grid grid-cols-1 gap-4 items-start lg:grid-cols-3 lg:gap-8">
                    <div class="grid grid-cols-1 gap-4 lg:col-span-2">
                        <ProfileSummary v-if="showProfileSummary"/>
                        <slot name="left"/>
                    </div>
                    <div class="grid grid-cols-1 gap-4">
                        <slot name="right"/>
                    </div>
                </div>
            </div>
        </main>
        <Footer/>
    </div>
</template>

<script setup>
    import Notification from '@/Shared/Notification';
    import Header from '@/Shared/Top/Header';
    import Footer from '@/Shared/Bottom/Footer';
    import ProfileSummary from '@/Shared/Profile/ProfileSummary';
    import InitialSetupProcess from '@/Shared/Account/InitialSetupProcess';
    import Breadcrumbs from '@/Shared/Breadcrumbs';

    defineProps({
        showProfileSummary: Boolean,
    });
</script>

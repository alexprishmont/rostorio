<template>
    <Notification :notification="$page.props.flash"/>

    <div class="min-h-full">
        <Header/>
        <main class="-mt-24 pb-8">
            <div class="max-w-screen-xl mx-auto pb-6 px-4 sm:px-6 lg:pb-16 lg:px-8">
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="divide-y divide-gray-200 lg:grid lg:grid-cols-12 lg:divide-y-0 lg:divide-x">
                        <aside class="py-6 lg:col-span-3">
                            <nav class="space-y-1">
                                <slot name="navigation"/>
                            </nav>
                        </aside>

                        <div class="divide-y divide-gray-200 lg:col-span-9" >
                            <div class="py-6 px-4 sm:p-6 lg:pb-8">
                                <slot name="content"/>
                            </div>
                        </div>
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
</script>

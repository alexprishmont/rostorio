<template>
    <Head title="Sign In"/>
    <div class="w-full flex flex-wrap">
        <div class="w-full md:w-1/2 flex flex-col">
            <div class="flex justify-center md:justify-start pt-12 md:pl-12 md:-mb-24">
                <div class="bg-black text-white font-bold text-xl p-4">
                    Rostor.<span class="text-orange-500">io</span>
                </div>
            </div>

            <div class="flex flex-col justify-center md:justify-start my-auto pt-8 md:pt-0 px-8 md:px-24 lg:px-32">
                <p class="text-center text-3xl">Welcome.</p>
                <form class="flex flex-col pt-3 md:pt-8" @submit.prevent="submit">
                    <div class="flex flex-col pt-4">
                        <label for="email" class="text-lg">Email</label>
                        <input
                            v-model="form.email"
                            type="email"
                            id="email"
                            placeholder="your@email.com"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mt-1 leading-tight focus:outline-none focus:shadow-outline"
                            required
                        >
                    </div>

                    <div class="flex flex-col pt-4">
                        <label for="password" class="text-lg">Password</label>
                        <input
                            v-model="form.password"
                            type="password"
                            id="password"
                            placeholder="Password"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mt-1 leading-tight focus:outline-none focus:shadow-outline"
                            required
                        >
                    </div>

                    <div
                        v-if="form.hasErrors"
                        class="flex flex-col pt-4"
                    >
                        <div class="rounded-md bg-red-50 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <XCircleIcon class="h-5 w-5 text-red-400"/>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-500" v-text="form.errors.email"></h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button
                        type="submit"
                        class="transition border border-orange-500 bg-orange-500 text-white font-bold text-lg hover:bg-white hover:text-orange-500 p-2 mt-8"
                        :disabled="form.processing"
                    >
                        Sign In
                    </button>
                </form>
                <div class="text-center pt-12 pb-12">
                    <p>
                        Don't have an account?
                        <Link href="sign-up" class="transition font-semibold text-orange-500 hover:text-orange-700">Sign Up</Link>
                    </p>
                </div>
            </div>

        </div>

        <div class="w-1/2 shadow-2xl">
            <img class="object-cover w-full h-screen hidden md:block" src="https://source.unsplash.com/IXUM4cJynP0">
        </div>
    </div>
</template>

<script setup>
import {XCircleIcon} from '@heroicons/vue/solid';
import {useForm} from "@inertiajs/inertia-vue3";

defineProps({
    layout: null,
});

const form = useForm({
    email: '',
    password: '',
});

const submit = () => {
    form.post('/sign-in');
};
</script>

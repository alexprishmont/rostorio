<template>
    <Head title="Sign In"/>
    <Authentication>

        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Sign in to your account
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Or
                <Link href="/sign-up" class="font-medium text-cyan-600 hover:text-sky-500">
                    start for free
                </Link>
            </p>
        </div>

        <ErrorAlert/>

        <form class="mt-8 space-y-6" @submit.prevent="submit">
            <input type="hidden" name="remember" value="true">
            <div class="rounded-md shadow-sm -space-y-px">
                <div>
                    <label for="email-address" class="sr-only">Email address</label>
                    <input id="email-address" name="email" type="email" autocomplete="email" required
                           class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-cyan-500 focus:border-cyan-500 focus:z-10 sm:text-sm"
                           placeholder="Email address"
                           v-model="form.email"
                    >
                </div>
                <div>
                    <label for="password" class="sr-only">Password</label>
                    <input id="password" name="password" type="password" autocomplete="current-password" required
                           class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-cyan-500 focus:border-cyan-500 focus:z-10 sm:text-sm"
                           placeholder="Password"
                           v-model="form.password"
                    >
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember-me" name="remember-me" type="checkbox"
                           class="h-4 w-4 text-cyan-600 focus:ring-cyan-500 border-gray-300 rounded"
                           v-model="form.remember"
                    >
                    <label for="remember-me" class="ml-2 block text-sm text-gray-900">
                        Remember me
                    </label>
                </div>

                <div class="text-sm">
                    <Link
                        href="#"
                        class="font-medium text-cyan-600 hover:text-sky-500"
                    >
                        Forgot your password?
                    </Link>
                </div>
            </div>

            <div>
                <button type="submit"
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-cyan-600 hover:bg-cyan-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <LockClosedIcon class="h-5 w-5 text-cyan-500 group-hover:text-cyan-400"/>
                    </span>
                    Sign In
                </button>
            </div>
        </form>

    </Authentication>
</template>

<script setup>
import {LockClosedIcon} from '@heroicons/vue/solid';
import {useForm} from "@inertiajs/inertia-vue3";
import Authentication from '@/Layouts/Authentication.vue';
import ErrorAlert from '@/Shared/ErrorAlert';

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post('/sign-in');
};
</script>

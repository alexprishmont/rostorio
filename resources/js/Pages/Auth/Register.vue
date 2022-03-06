<template>
    <Head title="Sign Up"/>
    <Authentication>

        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Create an account
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Already enjoying Rostor.io?
                <Link href="/sign-up" class="font-medium text-cyan-600 hover:text-sky-500">
                    Sign In
                </Link>
            </p>
        </div>

        <ErrorAlert/>

        <form class="mt-8 space-y-6" @submit.prevent="submit">
            <input type="hidden" name="remember" value="true">
            <div class="mt-1">
                <Input idName="firstname"
                       type="text"
                       v-model="form.firstname"
                >
                    First name
                </Input>
            </div>

            <div class="mt-1">
                <Input idName="lastname"
                       type="text"
                       v-model="form.lastname"
                >
                    Last name
                </Input>
            </div>

            <div class="mt-1">
                <Input idName="email"
                       type="email"
                       v-model="form.email"
                >
                    Email address
                </Input>
            </div>

            <div class="mt-1">
                <Input idName="password"
                       type="password"
                       v-model="form.password"
                >
                    Password
                </Input>
            </div>

            <div>
                <button type="submit"
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-cyan-600 hover:bg-cyan-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500">
                    Sign Up
                </button>
            </div>
        </form>

    </Authentication>
</template>



<script setup>
import {useForm} from "@inertiajs/inertia-vue3";
import Authentication from '@/Layouts/Authentication.vue';
import ErrorAlert from '@/Shared/ErrorAlert';
import Input from '@/Shared/Inputs/Input';

const form = useForm({
    email: '',
    password: '',
    firstname: '',
    lastname: '',
});

const submit = () => {
    form.post('/sign-up');
};
</script>

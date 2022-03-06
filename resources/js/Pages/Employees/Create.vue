<template>
    <Head title="Add employee"/>
    <OneColumnLayout>
        <section class="rounded-lg bg-white overflow-hidden shadow space-y-8 divide-y divide-gray-200">
            <form class="p-6 space-y divide-y divide-gray-200 sm:space-y-5" @submit.prevent="submit">
                <div>
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Add New Employee</h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">
                            Add a new employee to your organization
                        </p>
                    </div>

                    <ErrorAlert/>

                    <div class="mt-6 grid grid-cols-2 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <div class="sm:col-span-3">
                            <Input className="ml-3"
                                   idName="first-name"
                                   type="text"
                                   v-model="form.firstname"
                            >
                                First name
                            </Input>
                        </div>
                        <div class="sm:col-span-3">
                            <Input className="ml-3"
                                   idName="last-name"
                                   type="text"
                                   v-model="form.lastname"
                            >
                                Last name
                            </Input>
                        </div>
                    </div>

                    <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <div class="sm:col-span-6">
                            <Select
                                className="ml-3"
                                idName="role"
                                v-model="form.role"
                                :list="$page.props.roles.existing"
                            >
                                Job position
                            </Select>
                        </div>
                    </div>

                    <div class="mt-6 grid grid-cols-2 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <div class="sm:col-span-3">
                            <Input className="ml-3"
                                   idName="email"
                                   type="email"
                                   v-model="form.email"
                            >
                                Email address
                            </Input>
                        </div>
                        <div class="sm:col-span-3">
                            <Input className="ml-3"
                                   idName="phone-number"
                                   type="text"
                                   v-model="form.phone"
                            >
                                Phone number
                            </Input>
                        </div>
                    </div>

                    <div class="mt-6 ml-3 text-sm text-gray-500">
                        <p>
                            Please note, that the initial password for the account would be the employee's first name and last name written together (all in lowercase).
                        </p>
                        <p>
                            It should be changed immediately.
                        </p>
                    </div>

                    <div class="mt-6 ml-3 grid grid-cols-2 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <div class="sm:col-span-2">
                            <button
                                type="submit"
                                class="transition inline-flex items-center px-6 py-3 border border-cyan-500 bg-cyan-500 text-base leading-4 font-medium rounded-full shadow-sm text-white hover:bg-white hover:text-cyan-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500"
                            >
                                Save
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </section>
    </OneColumnLayout>
</template>

<script setup>
import OneColumnLayout from '@/Layouts/Authenticated/OneColumnLayout';
import Input from '@/Shared/Inputs/Input';
import Select from '@/Shared/Inputs/Select';
import {useForm, usePage} from "@inertiajs/inertia-vue3";
import ErrorAlert from '@/Shared/ErrorAlert'

const page = usePage();
const pageProps = page.props.value;

const form = useForm({
    firstname: '',
    lastname: '',
    role: pageProps.roles.existing[0],
    email: '',
    phone: '',
    created_by_organization: true,
});

const submit = () => {
    form.post('/employees', {
        preserveState: true,
    });
};
</script>

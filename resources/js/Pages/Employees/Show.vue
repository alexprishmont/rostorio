<template>
    <Head title="Employee overview"/>
    <TwoColumnsLayout>
        <template v-slot:left>
            <section class="rounded-lg bg-white overflow-hidden shadow space-y-8 divide-y divide-gray-200">
                <div class="p-6 space-y divide-y divide-gray-200 sm:space-y-5">
                    <div class="sm:flex sm:items-end sm:space-x-5">
                        <div class="flex">
                            <div>
                                <img
                                    class="h-24 w-24 rounded-full ring-4 ring-white sm:h-32 sm:w-32"
                                    :src="$page.props.auth.photo" :alt="employee.email"/>
                            </div>
                            <div class="p-5">
                                <h3 class="text-2xl font-bold text-gray-900 truncate">
                                    {{ employee.firstname }} {{ employee.lastname }}
                                </h3>
                                <p class="mb-2 text-sm font-normal text-gray-500 truncate">
                                    {{ employee.email }}
                                </p>
                                <div>
                                    <span
                                        class="px-3 py-1 bg-sky-100 text-slate-500 rounded-full"
                                        v-for="role in employee.roles" :key="role.id"
                                    >
                                        {{ role.name }}
                                    </span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </section>
        </template>
    </TwoColumnsLayout>
</template>

<script setup>
import TwoColumnsLayout from '@/Layouts/Authenticated/TwoColumnsLayout';
import {MailIcon} from '@heroicons/vue/solid';

defineProps({
    employee: Object,
})

</script>

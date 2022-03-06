<template>
    <TransitionRoot appear :show="isOpen" as="template">
        <Dialog as="div">
            <div class="fixed inset-0 z-10 overflow-y-auto">
                <div class="min-h-screen px-4 text-center">
                    <TransitionChild
                        as="template"
                        enter="duration-300 ease-out"
                        enter-from="opacity-0"
                        enter-to="opacity-100"
                        leave="duration-200 ease-in"
                        leave-from="opacity-100"
                        leave-to="opacity-0"
                    >
                        <DialogOverlay class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"/>
                    </TransitionChild>

                    <span class="inline-block h-screen align-middle" aria-hidden="true">&#8203;</span>

                    <TransitionChild
                        as="template"
                        enter="duration-300 ease-out"
                        enter-from="opacity-0 scale-95"
                        enter-to="opacity-100 scale-100"
                        leave="duration-200 ease-in"
                        leave-from="opacity-100 scale-100"
                        leave-to="opacity-0 scale-95"
                    >
                        <div
                            class="inline-block w-full max-w-md p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl"
                        >
                            <DialogTitle
                                as="h3"
                                class="text-lg font-medium leading-6 text-gray-900"
                            >
                                Please give us your company name
                            </DialogTitle>

                            <form @submit.prevent="save">
                                <div class="mt-2">
                                    <ErrorAlert/>
                                </div>
                                <div class="mt-2">
                                    <div
                                        class="border border-gray-300 rounded-md px-3 py-2 shadow-sm focus-within:ring-1 focus-within:ring-cyan-600 focus-within:border-cyan-600">
                                        <label for="organization-name" class="block text-xs font-medium text-gray-900">Company
                                            name</label>
                                        <input
                                            v-model="company.name"
                                            type="text" name="name" id="organization-name"
                                            class="block w-full border-0 p-0 text-gray-900 placeholder-gray-500 focus:ring-0 sm:text-sm"
                                            required
                                        >
                                    </div>
                                </div>

                                <div class="mt-2">
                                    <div
                                        class="border border-gray-300 rounded-md px-3 py-2 shadow-sm focus-within:ring-1 focus-within:ring-cyan-600 focus-within:border-cyan-600">
                                        <label for="organization-role" class="block text-xs font-medium text-gray-900">Job
                                            title</label>
                                        <select
                                            v-model="company.role"
                                            id="organization-role"
                                            class="block w-full border-0 p-0 text-gray-900 placeholder-gray-500 focus:ring-0 sm:text-sm"
                                            required
                                        >
                                            <option v-for="role in $page.props.roles.existing" :value="role">
                                                {{ role }}
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <button
                                        type="submit"
                                        class="inline-flex justify-center px-4 py-2 text-sm font-medium text-blue-900 bg-blue-100 border border-transparent rounded-md hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-blue-500"
                                    >
                                        Finish
                                    </button>
                                </div>
                            </form>
                        </div>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>

</template>

<script setup>
import {ref} from 'vue'
import {Dialog, DialogOverlay, DialogTitle, TransitionChild, TransitionRoot,} from '@headlessui/vue';
import {useForm, usePage} from "@inertiajs/inertia-vue3";
import ErrorAlert from '@/Shared/ErrorAlert';

const isOpen = ref(true);
const page = usePage();

if (page.props.value.auth.user.company_id && page.props.value.auth.user.roles) {
    isOpen.value = false;
}

const company = useForm({
    name: '',
    role: page.props.value.roles.existing[0],
});

const save = () => {
    company.post('/company/initialSetup', {
        onSuccess: () => isOpen.value = false,
    });
};
</script>

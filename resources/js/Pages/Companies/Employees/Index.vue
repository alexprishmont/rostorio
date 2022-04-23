<template>
  <Head title="Workers" />
  <TwoColumnsLayout>
    <template #left>
      <div class="flex flex-col">
        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
          <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th
                      scope="col"
                      class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                    >
                      Name
                    </th>
                    <th
                      scope="col"
                      class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                    >
                      Title
                    </th>
                    <th
                      scope="col"
                      class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                    >
                      Status
                    </th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr
                    v-for="user in employees"
                    :key="user.id"
                    class="cursor-pointer hover:bg-black hover:bg-opacity-10"
                    @click="openWorker(user.id)"
                  >
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10">
                          <img
                            class="h-10 w-10 rounded-full"
                            :src="$page.props.auth.photo"
                          >
                        </div>
                        <div class="ml-4">
                          <div class="text-sm font-medium text-gray-900">
                            {{ user.firstname }} {{ user.lastname }}
                          </div>
                          <div class="text-sm text-gray-500">
                            {{ user.email }}
                          </div>
                        </div>
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm text-gray-900">
                        {{ user.roles[0]?.name }}
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span
                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                        :class="{
                          'bg-green-100 text-green-800': statuses[user.id],
                          'bg-red-100 text-red-800': !statuses[user.id],
                        }"
                      >
                        <span v-if="statuses[user.id]">
                          Online
                        </span>

                        <span v-else>
                          Offline
                        </span>
                      </span>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </template>

    <template #right>
      <section class="rounded-lg bg-gray-200 overflow-hidden shadow divide-y divide-gray-200 sm:divide-y-0 sm:grid sm:grid-cols-1 sm:gap-px">
        <h2 class="sr-only">
          Actions
        </h2>
        <div class="rounded-tl-lg rounded-tr-lg sm:rounded-tr-none relative group bg-white p-6 focus-within:ring-2 focus-within:ring-inset focus-within:border-cyan-500">
          <div class="flex">
            <div class="max-w-12 max-h-12 rounded-lg inline-flex p-3 bg-teal-50 text-teal-800 ring-4 ring-white">
              <PlusIcon class="w-6 h-6" />
            </div>
            <div class="px-3">
              <h3 class="text-lg font-medium">
                <Link
                  href="/company/employees/create"
                  class="focus:outline-none"
                >
                  <span
                    class="absolute inset-0"
                    aria-hidden="true"
                  />
                  Add employee
                </Link>
              </h3>
              <p class="text-sm text-gray-500 font-normal">
                You can invite a new employee to your organization.
              </p>
            </div>
          </div>
        </div>
      </section>
    </template>
  </TwoColumnsLayout>
</template>

<script setup>
import TwoColumnsLayout from '@/Layouts/Authenticated/TwoColumnsLayout';
import {Inertia} from '@inertiajs/inertia';
import {PlusIcon} from '@heroicons/vue/solid';

defineProps({
    employees: Array,
    statuses: Object,
});

const openWorker = (workerId) => {
    Inertia.get(`/company/employees/${workerId}`);
};
</script>

<template>
  <Head title="Employee overview" />
  <TwoColumnsLayout>
    <template #left>
      <section class="rounded-lg bg-white overflow-hidden shadow space-y-8 divide-y divide-gray-200">
        <div class="p-6 space-y divide-y divide-gray-200 sm:space-y-5">
          <div class="sm:flex sm:items-end sm:space-x-5">
            <div class="flex">
              <div>
                <img
                  class="h-24 w-24 rounded-full ring-4 ring-white sm:h-32 sm:w-32"
                  :src="$page.props.auth.photo"
                  :alt="employee.email"
                >
              </div>
              <div class="p-5">
                <Editable
                  v-model="form.fullname"
                  class-name="text-2xl font-bold text-gray-900 truncate"
                  name="fullname"
                  @finish="editingFinished"
                />
                <Editable
                  v-model="form.email"
                  name="email"
                  class-name="text-sm font-normal text-gray-500 truncate"
                  @finish="editingFinished"
                />
                <div class="mt-2">
                  <span
                    v-for="role in employee.roles"
                    :key="role.id"
                    class="px-3 py-1 bg-sky-100 text-slate-500 rounded-full"
                  >
                    {{ role.name }}
                  </span>
                </div>
                <div class="mt-2 -ml-2 text-sm font-normal text-gray-500">
                  Works since {{ employee.created_at }}
                </div>
              </div>
            </div>
          </div>

          <div class="relative bg-white p-3 shadow rounded-lg overflow-hidden">
            <dt>
              <div class="absolute bg-cyan-500 rounded-md p-3">
                <ClockIcon class="h-6 w-6 text-white" />
              </div>
              <p class="ml-16 text-sm font-medium text-gray-500 truncate">
                Current month work hours
              </p>
            </dt>
            <dd class="ml-16 flex items-baseline">
              <p class="text-2xl font-semibold text-gray-900">
                {{ totalWorkHours }} hours
              </p>
            </dd>
          </div>

          <div
            v-show="form.address"
            class="mt-2"
          >
            <h1 class="mt-2 mb-2 text-xl font-semibold text-gray-900">
              Address
            </h1>
            <table class="min-w-full divide-y divide-gray-300">
              <thead class="bg-gray-50">
                <tr>
                  <th
                    scope="col"
                    class="py-3 pl-4 pr-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 sm:pl-6"
                  >
                    Country
                  </th>
                  <th
                    scope="col"
                    class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 sm:pl-6"
                  >
                    City
                  </th>
                  <th
                    scope="col"
                    class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 sm:pl-6"
                  >
                    Street
                  </th>
                  <th
                    scope="col"
                    class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 sm:pl-6"
                  >
                    House number
                  </th>
                  <th
                    scope="col"
                    class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 sm:pl-6"
                  >
                    ZIP
                  </th>
                  <th
                    scope="col"
                    class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 sm:pl-6"
                  >
                    Phone
                  </th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200 bg-white">
                <tr>
                  <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                    <Editable
                      v-model="form.address.country"
                      name="address.country"
                      @finish="editingFinished"
                    />
                  </td>
                  <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                    <Editable
                      v-model="form.address.city"
                      name="address.city"
                      @finish="editingFinished"
                    />
                  </td>
                  <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                    <Editable
                      v-model="form.address.street"
                      name="address.street"
                      @finish="editingFinished"
                    />
                  </td>
                  <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                    <Editable
                      v-model="form.address.house_number"
                      name="address.house_number"
                      @finish="editingFinished"
                    />
                  </td>
                  <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                    <Editable
                      v-model="form.address.zip"
                      name="address.zip"
                      @finish="editingFinished"
                    />
                  </td>
                  <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                    <Editable
                      v-model="form.address.phone"
                      name="address.phone"
                      @finish="editingFinished"
                    />
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <div class="mt-2">
            <h1 class="mt-2 mb-2 text-xl font-semibold text-gray-900">
              Shift requests
            </h1>

            <RequestsCalendar
              :user-id="employee.id"
              :header-toolbar="requestsHeaderToolbar"
              :initial-date="new Date().setMonth(new Date().getMonth() )"
            />
          </div>
        </div>
      </section>
    </template>

    <template #right>
      <section
        class="rounded-lg bg-gray-200 overflow-hidden shadow divide-y divide-gray-200 sm:divide-y-0 sm:grid sm:grid-cols-1 sm:gap-px"
      >
        <h2 class="sr-only">
          Actions
        </h2>
        <div
          class="rounded-tl-lg rounded-tr-lg sm:rounded-tr-none relative group bg-white p-6 focus-within:ring-2 focus-within:ring-inset focus-within:border-cyan-500"
        >
          <ActionLink
            v-for="action in availableActions"
            :key="action.title"
            :icon="action.icon"
            :link="action.url"
          >
            <template #title>
              {{ action.title }}
            </template>
            <template #description>
              {{ action.description }}
            </template>
          </ActionLink>
        </div>
      </section>
    </template>
  </TwoColumnsLayout>
</template>

<script setup>
import TwoColumnsLayout from '@/Layouts/Authenticated/TwoColumnsLayout';
import Editable from '@/Components/Editable';
import {Inertia} from '@inertiajs/inertia';
import {ref} from 'vue';
import { ClockIcon, XCircleIcon } from '@heroicons/vue/solid';
import RequestsCalendar from '@/Components/Shifts/RequestsCalendar';
import ActionLink from '@/Components/ActionLink';

const props = defineProps({
    employee: Object,
    totalWorkHours: Number,
});

const form = ref(props.employee);

const editingFinished = () => {
    const url = `/company/employees/${props.employee.id}`;

    Inertia.put(url, form.value);
};

const requestsHeaderToolbar = {
    left: 'prev next',
    center: 'title',
    right: '',
};

const availableActions = [
    {
        title: 'Delete employee',
        description: 'By pressing this button you will remove employee from the system.',
        icon: XCircleIcon,
        url: '',
    }
];

</script>

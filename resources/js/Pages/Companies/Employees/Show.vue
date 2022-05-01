<template>
  <Head title="Employee overview" />
  <TwoColumnsLayout>
    <template #left>
      <ErrorAlert />
      <section class="rounded-lg bg-white overflow-hidden shadow">
        <div class="p-6">
          <div class="sm:flex sm:items-end sm:space-x-5">
            <div class="flex mb-10">
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
                <div class="text-sm font-normal text-gray-500">
                  Dirba nuo {{ employee.created_at }}
                </div>
              </div>
            </div>
          </div>

          <div class="relative bg-white p-3 shadow rounded-lg overflow-hidden mb-10">
            <dt>
              <div class="absolute bg-cyan-500 rounded-md p-3">
                <ClockIcon class="h-6 w-6 text-white" />
              </div>
              <p class="ml-16 text-sm font-medium text-gray-500 truncate">
                Šio mėnesio darbo valandos
              </p>
            </dt>
            <dd class="ml-16 flex items-baseline">
              <p class="text-2xl font-semibold text-gray-900">
                {{ totalWorkHours }} val.
              </p>
            </dd>
          </div>

          <div
            v-show="form.address"
            class="mb-10"
          >
            <h1 class="mt-2 mb-2 text-xl font-semibold text-gray-900">
              Adresas
            </h1>
            <table class="min-w-full divide-y divide-gray-300">
              <thead class="bg-gray-50">
                <tr>
                  <th
                    scope="col"
                    class="py-3 pl-4 pr-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 sm:pl-6"
                  >
                    Šalis
                  </th>
                  <th
                    scope="col"
                    class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 sm:pl-6"
                  >
                    Miestas
                  </th>
                  <th
                    scope="col"
                    class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 sm:pl-6"
                  >
                    Gatvė
                  </th>
                  <th
                    scope="col"
                    class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 sm:pl-6"
                  >
                    Namo - būto numeris
                  </th>
                  <th
                    scope="col"
                    class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 sm:pl-6"
                  >
                    Pašto kodas
                  </th>
                  <th
                    scope="col"
                    class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 sm:pl-6"
                  >
                    Telefono numeris
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

          <div class="mb-10">
            <div class="mt-2 mb-2 flex">
              <h1 class="text-xl font-semibold text-gray-900">
                Rolės
              </h1>
              <PlusIcon
                class="w-5 h-5 cursor-pointer transition mt-1 ml-2 hover:text-gray-500"
                @click="showAddRoleModal = true"
              />
            </div>
            <table class="min-w-full divide-y divide-gray-300">
              <thead class="bg-gray-50">
                <tr>
                  <th
                    scope="col"
                    class="py-3 pl-4 pr-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 sm:pl-6"
                  >
                    ID
                  </th>
                  <th
                    scope="col"
                    class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 sm:pl-6"
                  >
                    Pavadinimas
                  </th>
                  <th />
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200 bg-white">
                <tr
                  v-for="(role, index) in employee.roles"
                  :key="index"
                >
                  <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                    {{ index + 1 }}
                  </td>
                  <td
                    class="transition hover:text-gray-900 whitespace-nowrap px-3 py-4 text-sm text-gray-500 cursor-pointer"
                  >
                    {{ role.name }}
                  </td>
                  <td>
                    <button
                      type="button"
                      class="inline-block px-6 py-2 border-2 border-red-600 text-red-600 font-medium text-xs leading-tight uppercase rounded-full hover:bg-black hover:bg-red-600 hover:text-white focus:outline-none focus:ring-0 transition duration-150 ease-in-out"
                      @click="removeRole(role)"
                    >
                      Panaikinti
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <div class="mt-2">
            <h1 class="mt-2 mb-2 text-xl font-semibold text-gray-900">
              Pamainų pageidavimai
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
          Veiksmai
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

  <FillableModal
    :show="showAddRoleModal"
    @close="showAddRoleModal = false"
  >
    <template #title>
      Pridėti rolę
    </template>

    <template #body>
      <Select
        v-model="addRoleForm.name"
        :list="rolesRef"
      >
        Pasirinkite rolę
      </Select>
    </template>

    <template #footer>
      <DefaultButton
        type="button"
        :is-loading="addRoleForm.processing"
        @click="addRole"
      >
        Pridėti
      </DefaultButton>
    </template>
  </FillableModal>
</template>

<script setup>
import TwoColumnsLayout from '@/Layouts/Authenticated/TwoColumnsLayout';
import Editable from '@/Components/Editable';
import {Inertia} from '@inertiajs/inertia';
import {ref} from 'vue';
import { ClockIcon, XCircleIcon, PlusIcon } from '@heroicons/vue/solid';
import RequestsCalendar from '@/Components/Shifts/RequestsCalendar';
import ActionLink from '@/Components/ActionLink';
import ErrorAlert from '@/Components/ErrorAlert';
import FillableModal from '@/Components/Modals/FillableModal';
import {useForm} from '@inertiajs/inertia-vue3';
import Select from '@/Components/Inputs/Select';
import DefaultButton from '@/Components/Buttons/DefaultButton';

const props = defineProps({
    employee: Object,
    totalWorkHours: Number,
    roles: Array,
});

const form = ref(props.employee);
const rolesRef = ref(props.roles.map(role => role.name));
const showAddRoleModal = ref(false);
const url = `/company/employees/${props.employee.id}`;

const addRoleForm = useForm({
    name: rolesRef.value[0],
});

const editingFinished = () => {
    Inertia.put(url, form.value);
};

const requestsHeaderToolbar = {
    left: 'prev next',
    center: 'title',
    right: '',
};

const availableActions = [
    {
        title: 'Ištrinti darbuotoją',
        description: 'Paspaudus mygtuką darbuotojo paskyra bus pilnai pašalinti iš sistemos.',
        icon: XCircleIcon,
        url: '',
    }
];

const removeRole = (role) => {
    Inertia.put(`${url}/removeRole`, role);
};

const addRole = () => {
    addRoleForm.post(`${url}/addRole`, {
        onError: () => {
            showAddRoleModal.value = false;
        },
        onSuccess: () => {
            showAddRoleModal.value = false;
        }
    });
};
</script>

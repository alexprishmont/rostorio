<template>
  <Head title="Pridėti darbuotoją" />
  <OneColumnLayout>
    <section class="rounded-lg bg-white overflow-hidden shadow space-y-8 divide-y divide-gray-200">
      <form
        class="p-6 space-y divide-y divide-gray-200 sm:space-y-5"
        @submit.prevent="submit"
      >
        <div>
          <div>
            <h3 class="text-lg leading-6 font-medium text-gray-900">
              Pridėti Naują Darbuotoją
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
              Sukurkite darbuotojo paskyrą
            </p>
          </div>

          <ErrorAlert />

          <div class="mt-6 grid grid-cols-2 gap-y-6 gap-x-4 sm:grid-cols-6">
            <div class="sm:col-span-3">
              <Input
                v-model="form.firstname"
                class-name="ml-3"
                id-name="first-name"
                type="text"
              >
                Vardas
              </Input>
            </div>
            <div class="sm:col-span-3">
              <Input
                v-model="form.lastname"
                class-name="ml-3"
                id-name="last-name"
                type="text"
              >
                Pavardė
              </Input>
            </div>
          </div>

          <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
            <div class="sm:col-span-6">
              <Select
                v-model="form.role"
                class-name="ml-3"
                id-name="role"
                :list="roles"
              >
                Darbo pozicija
              </Select>
            </div>
          </div>

          <div class="mt-6 grid grid-cols-2 gap-y-6 gap-x-4 sm:grid-cols-6">
            <div class="sm:col-span-3">
              <Input
                v-model="form.email"
                class-name="ml-3"
                id-name="email"
                type="email"
              >
                Elektroninis paštas
              </Input>
            </div>
            <div class="sm:col-span-3">
              <Input
                v-model="form.phone"
                class-name="ml-3"
                id-name="phone-number"
                type="text"
              >
                Telefono numeris
              </Input>
            </div>
          </div>

          <div class="mt-6 ml-3 text-sm text-gray-500">
            <p>
              Pažymime, kad sukurto vartotojo paskyros slaptažodis bus darbuotojo vardas ir pavardė kartu sujungti ir parašyti mažosiomis raidėmis.
            </p>
          </div>

          <div class="mt-6 ml-3 grid grid-cols-2 gap-y-6 gap-x-4 sm:grid-cols-6">
            <div class="sm:col-span-2">
              <button
                type="submit"
                class="transition inline-flex items-center px-6 py-3 border border-cyan-500 bg-cyan-500 text-base leading-4 font-medium rounded-full shadow-sm text-white hover:bg-white hover:text-cyan-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500"
              >
                Išsaugoti
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
import Input from '@/Components/Inputs/Input';
import Select from '@/Components/Inputs/Select';
import {useForm} from '@inertiajs/inertia-vue3';
import ErrorAlert from '@/Components/ErrorAlert';

const props = defineProps({
    roles: Object,
});

const form = useForm({
    firstname: '',
    lastname: '',
    role: props.roles[0],
    email: '',
    phone: '',
    created_by_organization: true,
});

const submit = () => {
    form.post('/company/employees', {
        preserveState: true,
    });
};
</script>

<template>
  <Head title="Valdyti rolės" />
  <OneColumnLayout>
    <section class="rounded-lg bg-white overflow-hidden shadow space-y-8 divide-y divide-gray-200">
      <div class="p-6 space-y divide-y divide-gray-200 sm:space-y-5">
        <div class="flex justify-between">
          <h1 class="text-xl font-bold">
            Valdyti rolės
          </h1>

          <DefaultButton @click="save">
            Išsaugoti
          </DefaultButton>
        </div>

        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th
                scope="col"
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                ID
              </th>
              <th
                scope="col"
                class="w-full px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                Pavadinimas
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr
              v-for="(role, index) in roles"
              :key="index"
              class="transition hover:bg-black hover:bg-opacity-10"
            >
              <td class="px-6 py-4 whitespace-nowrap">
                {{ index + 1 }}
              </td>
              <td
                class="px-6 py-4 whitespace-nowrap cursor-pointer"
              >
                <Editable
                  v-model="rolesRef[index].name"
                  name="name"
                />
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </section>
  </OneColumnLayout>
</template>

<script setup>
import OneColumnLayout from '@/Layouts/Authenticated/OneColumnLayout';
import DefaultButton from '@/Components/Buttons/DefaultButton';
import Editable from '@/Components/Editable';
import {ref} from 'vue';
import {Inertia} from '@inertiajs/inertia';

const props = defineProps({
    roles: Array,
});

const rolesRef = ref(props.roles);

const save = () => {
    Inertia.post('/company/roles', rolesRef.value);
};

</script>

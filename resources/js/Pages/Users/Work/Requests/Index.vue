<template>
  <Head title="Shifts schedule requests" />
  <OneColumnLayout>
    <section class="rounded-lg bg-white overflow-hidden shadow space-y-8 divide-y divide-gray-200">
      <div class="p-6 space-y divide-y divide-gray-200 sm:space-y-5">
        <RequestsCalendar
          ref="requestCalendarRef"
          selectable
          :user-id="user.id"
          :header-toolbar="{left: '', center: 'title', right: ''}"
          :initial-date="new Date().setMonth(new Date().getMonth() + 1)"
          @select="daySelect"
          @eventClick="eventClick"
        />
      </div>
    </section>



    <FillableModal
      :show="isCreateRequestWindowShown"
      @close="closeCreateRequestWindow"
    >
      <template #title>
        Pridėti pageidavimą
      </template>
      <template #body>
        <form @submit.prevent="addNewRequest">
          <div
            class="border border-gray-300 rounded-md px-3 py-2 shadow-sm focus-within:ring-1 focus-within:ring-cyan-600 focus-within:border-cyan-600"
          >
            <label
              for="type"
              class="block text-xs font-medium text-gray-900"
            >
              Pageidavimas
            </label>

            <select
              id="type"
              v-model="form.request"
              class="block w-full border-0 p-0 text-gray-900 placeholder-gray-500 focus:ring-0 sm:text-sm"
              required
            >
              <option
                v-for="(type, index) in list"
                :key="index"
                :value="index"
              >
                {{ type }}
              </option>
            </select>
          </div>

          <button
            type="submit"
            class="mt-4 transition inline-flex items-center px-4 py-2 border border-cyan-500 bg-cyan-500 text-sm leading-4 font-medium rounded-full shadow-sm text-white hover:bg-white hover:text-cyan-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500"
          >
            Pridėti
          </button>
        </form>
      </template>
    </FillableModal>
  </OneColumnLayout>
</template>

<script setup>
import OneColumnLayout from '@/Layouts/Authenticated/OneColumnLayout';
import FillableModal from '@/Components/Modals/FillableModal';
import RequestsCalendar from '@/Components/Shifts/RequestsCalendar';
import {useAvailableScheduleShiftsRequestTypes} from '@/Composables/useAvailableScheduleShiftsRequestTypes';
import {useCurrentUser} from '@/Composables/useCurrentUser';

import {useForm} from '@inertiajs/inertia-vue3';
import {onMounted, ref} from 'vue';
import {Inertia} from '@inertiajs/inertia';

const requestCalendarRef = ref();
const isCreateRequestWindowShown = ref(false);
const form = useForm({
    request: '',
    shift_at: null,
});
const list = ref({});
const { getTypes } = useAvailableScheduleShiftsRequestTypes();

const user = useCurrentUser();

onMounted(async () => {
    list.value = await getTypes();
});

const daySelect = (data) => {
    const { startStr } = data;

    isCreateRequestWindowShown.value = true;
    form.shift_at = startStr;
};

const closeCreateRequestWindow = () => {
    isCreateRequestWindowShown.value = false;
};

const addNewRequest = () => {
    form.post('/profile/work/requests', {
        onSuccess: async () => {
            isCreateRequestWindowShown.value = false;
            await requestCalendarRef.value.reloadEvents();
        },
    });
};

const eventClick = async (data) => {
    const { event } = data;
    Inertia.delete(`/profile/work/requests/${event.id}`);
    await requestCalendarRef.value.reloadEvents();
};
</script>

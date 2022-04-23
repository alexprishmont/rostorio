<template>
  <Head title="My work" />
  <TwoColumnsLayout>
    <template #left>
      <section class="rounded-lg bg-white overflow-hidden shadow space-y-8 divide-y divide-gray-200">
        <div class="p-6 space-y divide-y divide-gray-200 sm:space-y-5">
          <div>
            <h3 class="text-lg leading-6 font-medium text-gray-900">
              Shifts
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
              Organization information
            </p>
          </div>
        </div>
      </section>
      <Calendar
        :events="schedule"
        :date-click="dateClick"
        :dates-set="datesSet"
      />
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
            :icon="ClipboardListIcon"
            link="/profile/work/requests"
          >
            <template #title>
              Next month requests
            </template>
            <template #description>
              You can add your requests for the next month shifts schedule.
            </template>
          </ActionLink>
        </div>
      </section>
    </template>
  </TwoColumnsLayout>
</template>

<script setup>
import TwoColumnsLayout from '@/Layouts/Authenticated/TwoColumnsLayout';
import Calendar from '@/Components/Calendar';
import ActionLink from '@/Components/ActionLink';
import { ClipboardListIcon } from '@heroicons/vue/solid';
import { useShifts } from '@/Composables/useShifts';
import {ref} from 'vue';
import {usePage} from '@inertiajs/inertia-vue3';

const props = defineProps({
    shifts: Array,
});

const schedule = ref(props.shifts);
const currentDate = ref(null);


const dateClick = async info => {
    if (info.view.type === 'dayGridMonth') {
        info.view.calendar.changeView('timeGridDay', info.dateStr);
    }
};

const datesSet = async info => {
    if (new Date(currentDate.value).getTime() === new Date(info.start).getTime()) {
        return;
    }

    currentDate.value = info.start;

    const { getShiftByUser } = useShifts();

    schedule.value = await getShiftByUser(new Date(info.start).toLocaleDateString('lt-LT', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
    }), usePage().props.value.auth.user.id);
};

</script>

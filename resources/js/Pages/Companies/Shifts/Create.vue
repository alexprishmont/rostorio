<template>
  <Head title="Next month scheduling" />
  <OneColumnLayout>
    <section
      v-show="Object.keys($page.props.errors).length > 0"
      class="mb-3"
    >
      <ErrorAlert />
    </section>
    <ScheduleCalendar
      :accounts="accounts"
      :months-from-current="1"
      can-generate-schedule
    />
  </OneColumnLayout>
</template>

<script setup>
import OneColumnLayout from '@/Layouts/Authenticated/OneColumnLayout';
import ErrorAlert from '@/Components/ErrorAlert';
import ScheduleCalendar from '@/Components/Shifts/ScheduleCalendar';

defineProps({
    accounts: Array,
});

</script>

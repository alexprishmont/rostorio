<template>
  <Head title="Darbo grafikas" />
  <OneColumnLayout>
    <section
      v-show="Object.keys($page.props.errors).length > 0"
      class="mb-3"
    >
      <ErrorAlert />
    </section>

    <section
      v-show="Object.keys($page.props.report).length > 0"
      class="mb-3"
    >
      <ActionReport />
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
import ActionReport from '@/Components/ActionReport';

defineProps({
    accounts: Array,
});

</script>

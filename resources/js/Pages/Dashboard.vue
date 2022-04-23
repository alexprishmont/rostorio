<template>
  <Head title="Dashboard" />
  <TwoColumnsLayout show-profile-summary>
    <template #left>
      <div class="overflow-x-auto">
        <div>Hoho</div>
      </div>
    </template>
  </TwoColumnsLayout>
</template>

<script setup>
import TwoColumnsLayout from '@/Layouts/Authenticated/TwoColumnsLayout';

defineProps({
    shifts: Object,
});

</script>

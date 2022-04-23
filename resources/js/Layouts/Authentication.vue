<template>
  <Notification :notification="$page.props.flash" />

  <div class="min-h-full flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
      <slot />
    </div>
  </div>
</template>
<script setup>
import Notification from '@/Components/Notification';

</script>

<template>
  <nav :class="classProp">
    <NavLink
      link="/dashboard"
    >
      Dashboard
    </NavLink>
    <NavLink
      link="/company"
    >
      Company
    </NavLink>
    <NavLink
      link="/profile/work"
    >
      My work
    </NavLink>
  </nav>
</template>

<script setup>
import NavLink from '@/Components/NavLink';

defineProps({
    classProp: String,
});
</script>

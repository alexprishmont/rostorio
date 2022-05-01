<template>
  <nav :class="classProp">
    <NavLink
      v-for="link in links"
      :key="link.title"
      :link="link.url"
    >
      {{ link.title }}
    </NavLink>
  </nav>
</template>

<script setup>
import NavLink from '@/Components/NavLink';
import {onMounted, ref} from 'vue';
import {useCanAccess} from '@/Composables/useCanAccess';

defineProps({
    classProp: String,
});

const links = ref([
    {
        title: 'Pradinis',
        url: '/profile/work',
        whoCanAccess: 'user,admin,moderator',
    },
    {
        title: 'Įmonė',
        url: '/company',
        whoCanAccess: 'admin,moderator',
    },
]);

const { canAccess } = useCanAccess();

onMounted(() => {
    links.value = links.value.filter(link => {
        const roles = link.whoCanAccess.split(',');

        return canAccess(roles);
    });
});
</script>

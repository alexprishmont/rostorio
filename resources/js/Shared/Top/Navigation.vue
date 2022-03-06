<template>
    <nav :class="classProp">
        <NavLink
            link="/dashboard"
            component="Dashboard"
        >
            Dashboard
        </NavLink>
        <NavLink
            link="/employees"
            component="Employees/*"
        >
            Employees
        </NavLink>
    </nav>
</template>

<script setup>
import NavLink from '@/Shared/NavLink'

defineProps({
    classProp: String,
})
</script>

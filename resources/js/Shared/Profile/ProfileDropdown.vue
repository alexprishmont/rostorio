<template>
    <Menu as="div" class="relative inline-block text-left">
        <div>
            <MenuButton
                class="bg-white rounded-full flex text-sm ring-2 ring-white ring-opacity-20 focus:outline-none focus:ring-opacity-100" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                <span class="sr-only">Open user menu</span>
                <img class="h-8 w-8 rounded-full" :src="$page.props.auth.photo" alt="">
            </MenuButton>
        </div>

        <transition
            enter-active-class="transition duration-100 ease-out"
            enter-from-class="transform scale-95 opacity-0"
            enter-to-class="transform scale-100 opacity-100"
            leave-active-class="transition duration-75 ease-in"
            leave-from-class="transform scale-100 opacity-100"
            leave-to-class="transform scale-95 opacity-0"
        >
            <MenuItems
                class="absolute right-0 w-56 mt-2 origin-top-right bg-white divide-y divide-gray-100 rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
            >
                <div class="px-1 py-1">
                    <MenuItem v-slot="{ active }">
                        <Link
                            method="post"
                            href="/logout"
                            :class="[
                                active ? 'bg-cyan-600 text-white' : 'text-gray-900','group flex rounded-md items-center w-full px-2 py-2 text-sm',
                            ]"
                        >
                            <LogoutIcon
                                :active="active"
                                class="w-5 h-5 mr-2 text-sky-800"
                                aria-hidden="true"
                            />
                            Sign out
                        </Link>
                    </MenuItem>
                </div>
            </MenuItems>
        </transition>
    </Menu>
</template>

<script setup>
import { LogoutIcon } from '@heroicons/vue/solid';
import { Menu, MenuButton, MenuItems, MenuItem } from "@headlessui/vue";
</script>

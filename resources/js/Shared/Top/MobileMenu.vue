<template>
  <Menu
    as="div"
    class="relative inline-block text-left"
  >
    <div>
      <MenuButton
        class="bg-transparent p-2 rounded-md inline-flex items-center justify-center text-cyan-200 hover:text-white hover:bg-white hover:bg-opacity-10 focus:outline-none focus:ring-2 focus:ring-white"
        aria-expanded="false"
      >
        <span class="sr-only">Open main menu</span>
        <MenuIcon class="block h-6 w-6" />
        <XIcon class="hidden h-6 w-6" />
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
              href="/profile/work"
              :class="[
                active ? 'bg-cyan-600 text-white' : 'text-gray-900','group flex rounded-md items-center w-full px-2 py-2 text-sm',
              ]"
            >
              <HomeIcon
                :active="active"
                class="w-5 h-5 mr-2 text-sky-800"
                aria-hidden="true"
              />
              Pradinis
            </Link>
          </MenuItem>
        </div>
        <div class="px-1 py-1">
          <MenuItem v-slot="{ active }">
            <Link
              href="/profile/notifications"
              :class="[
                active ? 'bg-cyan-600 text-white' : 'text-gray-900','group flex rounded-md items-center w-full px-2 py-2 text-sm',
              ]"
            >
              <BellIcon
                :active="active"
                class="w-5 h-5 mr-2 text-sky-800"
                aria-hidden="true"
              />
              Pranešimai
            </Link>
          </MenuItem>
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
              Atsijungti
            </Link>
          </MenuItem>
        </div>
      </MenuItems>
    </transition>
  </Menu>
</template>

<script setup>
import { Menu, MenuButton, MenuItems, MenuItem } from '@headlessui/vue';
import {XIcon, BellIcon, MenuIcon, LogoutIcon, HomeIcon} from '@heroicons/vue/solid';
</script>

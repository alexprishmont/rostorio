<template>
    <SettingsLayout>
        <template v-slot:navigation>
            <a href="#"
               class="bg-teal-50 border-teal-500 text-teal-700 hover:bg-teal-50 hover:text-teal-700 group border-l-4 px-3 py-2 flex items-center text-sm font-medium"
               aria-current="page"
            >
                <UserCircleIcon class="text-teal-500 group-hover:text-teal-500 flex-shrink-0 -ml-1 mr-3 h-6 w-6"/>
                <span class="truncate">Profile</span>
            </a>

            <a href="#"
               class="border-transparent text-gray-900 hover:bg-gray-50 hover:text-gray-900 group border-l-4 px-3 py-2 flex items-center text-sm font-medium"
               aria-current="page"
            >
                <OfficeBuildingIcon class="text-teal-500 group-hover:text-teal-500 flex-shrink-0 -ml-1 mr-3 h-6 w-6"/>
                <span class="truncate">Company</span>
            </a>
        </template>

        <template v-slot:content>
            <div>
                <h2 class="text-lg leading-6 font-medium text-gray-900">
                    Profile
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    This information will be displayed publicly so be careful what you share.
                </p>
            </div>
        </template>
    </SettingsLayout>
</template>

<script setup>
import SettingsLayout from '@/Layouts/SettingsLayout';
import {UserCircleIcon, OfficeBuildingIcon} from '@heroicons/vue/solid';
</script>

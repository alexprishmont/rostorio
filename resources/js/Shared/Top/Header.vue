<template>
  <header class="pb-24 bg-gradient-to-r from-sky-800 to-cyan-600">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:max-w-7xl lg:px-8">
      <div class="relative flex flex-wrap items-center justify-center lg:justify-between">
        <div class="absolute left-0 py-5 flex-shrink-0 lg:static">
          <Link href="/">
            <span class="text-cyan-100">
              Rostor.io
            </span>
          </Link>
        </div>

        <div class="hidden lg:ml-4 lg:flex lg:items-center lg:py-5 lg:pr-0.5">
          <Notifications />

          <div class="ml-4 relative flex-shrink-0">
            <ProfileDropdown />
          </div>
        </div>

        <div class="w-full py-5 lg:border-t lg:border-white lg:border-opacity-20">
          <div class="lg:grid lg:grid-cols-3 lg:gap-8 lg:items-center">
            <div class="hidden lg:block lg:col-span-2">
              <Navigation class="flex space-x-4" />
            </div>
            <div class="px-12 lg:px-0">
              <div class="max-w-xs mx-auto w-full lg:max-w-md">
                <SearchBar />
              </div>
            </div>
          </div>
        </div>

        <div class="absolute right-0 flex-shrink-0 lg:hidden">
          <MobileMenu />
        </div>
      </div>
    </div>
  </header>
</template>

<script setup>
import ProfileDropdown from '@/Shared/Profile/ProfileDropdown';
import Notifications from '@/Shared/Profile/Notifications';
import MobileMenu from '@/Shared/Top/MobileMenu';
import SearchBar from '@/Components/SearchBar';
import Navigation from '@/Shared/Top/Navigation';
</script>

<template>
  <section aria-labelledby="profile-overview-title">
    <div class="rounded-lg bg-white overflow-hidden shadow">
      <h2
        id="profile-overview-title"
        class="sr-only"
      >
        Paskyros apžvalga
      </h2>
      <div class="bg-white p-6">
        <div class="sm:flex sm:items-center sm:justify-between">
          <div class="sm:flex sm:space-x-5">
            <div class="flex-shrink-0">
              <img
                class="mx-auto h-20 w-20 rounded-full"
                :src="$page.props.auth.photo"
                alt=""
              >
            </div>
            <div class="mt-4 text-center sm:mt-0 sm:pt-1 sm:text-left">
              <p class="text-sm font-medium text-gray-600">
                Sveiki sugrįžus,
              </p>
              <p class="text-xl font-bold text-gray-900 sm:text-2xl">
                {{ $page.props.auth.user.firstname }} {{ $page.props.auth.user.lastname }}
              </p>
              <p class="text-sm font-medium text-gray-600">
                {{ $page.props.auth.user.roles[0]?.name }}
              </p>
            </div>
          </div>
        </div>
      </div>
      <div class="border-t border-gray-200 bg-gray-50 grid grid-cols-1 divide-y divide-gray-200 sm:grid-cols-2 sm:divide-y-0 sm:divide-x">
        <div class="px-6 py-5 text-sm font-medium text-center">
          <span class="text-gray-900">{{ workHours }}</span>
          <span class="text-gray-600"> Darbo valandų ši mėnesį</span>
        </div>

        <div class="px-6 py-5 text-sm font-medium text-center">
          <span class="text-gray-900">{{ shiftsCount }}</span>
          <span class="text-gray-600"> Pamainos ši mėnesį</span>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
defineProps({
    workHours: Number,
    shiftsCount: Number,
});
</script>


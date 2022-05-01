<template>
  <Head title="Sign In" />
  <Authentication>
    <div>
      <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
        Prisijungti prie paskyros
      </h2>
      <p class="mt-2 text-center text-sm text-gray-600">
        Arba
        <Link
          href="/sign-up"
          class="font-medium text-cyan-600 hover:text-sky-500"
        >
          pradėti naudotis nemokamai
        </Link>
      </p>
    </div>

    <ErrorAlert />

    <form
      class="mt-8 space-y-6"
      @submit.prevent="submit"
    >
      <input
        type="hidden"
        name="remember"
        value="true"
      >
      <div class="rounded-md shadow-sm -space-y-px">
        <div>
          <label
            for="email-address"
            class="sr-only"
          >Elektroninis paštas</label>
          <input
            id="email-address"
            v-model="form.email"
            name="email"
            type="email"
            autocomplete="email"
            required
            class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-cyan-500 focus:border-cyan-500 focus:z-10 sm:text-sm"
            placeholder="El. paštas"
          >
        </div>
        <div>
          <label
            for="password"
            class="sr-only"
          >Slaptažodis</label>
          <input
            id="password"
            v-model="form.password"
            name="password"
            type="password"
            autocomplete="current-password"
            required
            class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-cyan-500 focus:border-cyan-500 focus:z-10 sm:text-sm"
            placeholder="Slaptažodis"
          >
        </div>
      </div>
      <div>
        <button
          type="submit"
          class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-cyan-600 hover:bg-cyan-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500"
        >
          <span class="absolute left-0 inset-y-0 flex items-center pl-3">
            <LockClosedIcon class="h-5 w-5 text-cyan-500 group-hover:text-cyan-400" />
          </span>
          Prisijungti
        </button>
      </div>
    </form>
  </Authentication>
</template>

<script setup>
import {LockClosedIcon} from '@heroicons/vue/solid';
import {useForm} from '@inertiajs/inertia-vue3';
import Authentication from '@/Layouts/Authentication.vue';
import ErrorAlert from '@/Components/ErrorAlert';

const form = useForm({
    email: '',
    password: '',
});

const submit = () => {
    form.post('/sign-in');
};
</script>

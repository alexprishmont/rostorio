<template>
  <Head title="Sign Up" />
  <Authentication>
    <div>
      <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
        Sukurti paskyrą
      </h2>
      <p class="mt-2 text-center text-sm text-gray-600">
        Jau turite paskyrą?
        <Link
          href="/sign-in"
          class="font-medium text-cyan-600 hover:text-sky-500"
        >
          Prisijungti
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
      <div class="mt-1">
        <Input
          v-model="form.firstname"
          id-name="firstname"
          type="text"
        >
          Vardas
        </Input>
      </div>

      <div class="mt-1">
        <Input
          v-model="form.lastname"
          id-name="lastname"
          type="text"
        >
          Pavardė
        </Input>
      </div>

      <div class="mt-1">
        <Input
          v-model="form.email"
          id-name="email"
          type="email"
        >
          Elektroninis paštas
        </Input>
      </div>

      <div class="mt-1">
        <Input
          v-model="form.password"
          id-name="password"
          type="password"
        >
          Slaptažodis
        </Input>
      </div>

      <div>
        <button
          type="submit"
          class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-cyan-600 hover:bg-cyan-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500"
        >
          Registruotis
        </button>
      </div>
    </form>
  </Authentication>
</template>



<script setup>
import {useForm} from '@inertiajs/inertia-vue3';
import Authentication from '@/Layouts/Authentication.vue';
import ErrorAlert from '@/Components/ErrorAlert';
import Input from '@/Components/Inputs/Input';

const form = useForm({
    email: '',
    password: '',
    firstname: '',
    lastname: '',
});

const submit = () => {
    form.post('/sign-up');
};
</script>

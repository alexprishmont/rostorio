<template>
  <Head title="Įmonė" />
  <TwoColumnsLayout>
    <template #left>
      <ErrorAlert />

      <section class="rounded-lg bg-white overflow-hidden shadow space-y-8 divide-y divide-gray-200">
        <div class="p-6 space-y sm:space-y-5">
          <div>
            <h3 class="text-lg leading-6 font-medium text-gray-900">
              {{ company.name }}
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
              Įmonės informacija
            </p>
          </div>
          <form @submit.prevent="saveCompany">
            <div>
              <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                <div
                  class="sm:col-span-6"
                >
                  <Input
                    v-model="companyForm.name"
                    id-name="company-name"
                    type="text"
                    :is-disabled="canAccess(['admin']) ? false : true"
                  >
                    Pavadinimas
                  </Input>
                </div>
              </div>

              <div
                v-show="canAccess(['admin'])"
                class="mt-3 grid grid-cols-2 gap-y-6 gap-x-4 sm:grid-cols-6"
              >
                <div class="sm:col-span-2">
                  <DefaultButton
                    type="submit"
                    :is-loading="companyForm.processing"
                  >
                    Išsaugoti
                  </DefaultButton>
                </div>
              </div>
            </div>
          </form>
        </div>
      </section>

      <section class="rounded-lg bg-white overflow-hidden shadow space-y-8 divide-y divide-gray-200">
        <div class="p-6 space-y sm:space-y-5">
          <div>
            <h3 class="text-lg leading-6 font-medium text-gray-900">
              Pamainos
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
              Nustatykite kada prasideda ir pasibaigia pamainos.
            </p>
          </div>
          <form @submit.prevent="saveShiftsTime">
            <div class="flex justify-between">
              <div>
                <span class="text-sm text-gray-500 font-semibold">
                  Pradžia
                </span>
                <Datepicker
                  v-model="shiftsTimeForm.shifts_begins_at"
                  time-picker
                  :disabled="canAccess(['admin']) ? false : true"
                />
              </div>
              <div>
                <span class="text-sm text-gray-500 font-semibold">
                  Pabaiga
                </span>
                <Datepicker
                  v-model="shiftsTimeForm.shifts_ends_at"
                  time-picker
                  :disabled="canAccess(['admin']) ? false : true"
                />
              </div>
            </div>
            <div
              v-show="canAccess(['admin'])"
              class="mt-3"
            >
              <DefaultButton
                type="submit"
                :is-loading="shiftsTimeForm.processing"
              >
                Išsaugoti
              </DefaultButton>
            </div>
          </form>
        </div>
      </section>
    </template>

    <template #right>
      <section
        class="rounded-lg bg-gray-200 overflow-hidden shadow divide-y divide-gray-200 sm:divide-y-0 sm:grid sm:grid-cols-1 sm:gap-px"
      >
        <h2 class="sr-only">
          Veiksmai
        </h2>
        <div
          class="rounded-tl-lg rounded-tr-lg sm:rounded-tr-none relative group bg-white p-6 focus-within:ring-2 focus-within:ring-inset focus-within:border-cyan-500"
        >
          <ActionLink
            v-for="action in availableActions"
            :key="action.title"
            :icon="action.icon"
            :link="action.url"
            :can-access="action.canAccess"
          >
            <template #title>
              {{ action.title }}
            </template>
            <template #description>
              {{ action.description }}
            </template>
          </ActionLink>
        </div>
      </section>
    </template>
  </TwoColumnsLayout>
</template>

<script setup>
import TwoColumnsLayout from '@/Layouts/Authenticated/TwoColumnsLayout';
import ActionLink from '@/Components/ActionLink';
import DefaultButton from '@/Components/Buttons/DefaultButton';
import ErrorAlert from '@/Components/ErrorAlert';
import Input from '@/Components/Inputs/Input';
import {UserGroupIcon, BadgeCheckIcon, CalendarIcon} from '@heroicons/vue/solid';
import {useForm} from '@inertiajs/inertia-vue3';
import Datepicker from '@vuepic/vue-datepicker';
import {ref} from 'vue';
import {useCanAccess} from '@/Composables/useCanAccess';

const props = defineProps({
    company: Object,
});

const { canAccess } = useCanAccess();

const companyForm = useForm({
    name: props.company.name,
});

const shiftsTimeForm = useForm({
    'shifts_begins_at': {
        hours: props.company.shifts.starts.hours,
        minutes: props.company.shifts.starts.minutes,
    },
    'shifts_ends_at': {
        hours: props.company.shifts.ends.hours,
        minutes: props.company.shifts.ends.minutes,
    }
});

const saveShiftsTime = () => {
    shiftsTimeForm.put(`/company/${props.company.id}`);
};

const saveCompany = () => {
    companyForm.put(`/company/${props.company.id}`);
};

const availableActions = ref([
    {
        title: 'Valdyti rolės',
        description: 'Vartotojų rolių nustatymai',
        url: '/company/roles',
        icon: BadgeCheckIcon,
        canAccess: 'admin,moderator',
    },
    {
        title: 'Valdyti darbuotojus',
        description: 'Valdyti įmonei priklausančias darbuotojų paskyras.',
        url: '/company/employees',
        icon: UserGroupIcon,
        canAccess: 'admin,moderator',
    },
    {
        title: 'Valdyti darbo grafiką',
        description: 'Darbo grafiko redagavimas, generavimas.',
        url: '/shifts/create',
        icon: CalendarIcon,
        canAccess: 'admin,moderator',
    },
]);
</script>

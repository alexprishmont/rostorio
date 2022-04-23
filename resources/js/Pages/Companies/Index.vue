<template>
  <Head title="Organization" />
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
              Organization information
            </p>
          </div>
          <form @submit.prevent="saveCompany">
            <div>
              <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                <div class="sm:col-span-6">
                  <Input
                    v-model="companyForm.name"
                    id-name="company-name"
                    type="text"
                  >
                    Organization name
                  </Input>
                </div>
              </div>

              <div class="mt-3 grid grid-cols-2 gap-y-6 gap-x-4 sm:grid-cols-6">
                <div class="sm:col-span-2">
                  <DefaultButton
                    type="submit"
                    :is-loading="companyForm.processing"
                  >
                    Save
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
              Work shifts
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
              Set when your shifts start and end
            </p>
          </div>
          <form @submit.prevent="saveShiftsTime">
            <div class="flex justify-between">
              <div>
                <span class="text-sm text-gray-500 font-semibold">
                  Shifts starts at
                </span>
                <Datepicker
                  v-model="shiftsTimeForm.shifts_begins_at"
                  time-picker
                />
              </div>
              <div>
                <span class="text-sm text-gray-500 font-semibold">
                  Shifts ends at
                </span>
                <Datepicker
                  v-model="shiftsTimeForm.shifts_ends_at"
                  time-picker
                />
              </div>
            </div>
            <div class="mt-3">
              <DefaultButton
                type="submit"
                :is-loading="shiftsTimeForm.processing"
              >
                Save
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
          Actions
        </h2>
        <div
          class="rounded-tl-lg rounded-tr-lg sm:rounded-tr-none relative group bg-white p-6 focus-within:ring-2 focus-within:ring-inset focus-within:border-cyan-500"
        >
          <ActionLink
            v-for="action in availableActions"
            :key="action.title"
            :icon="action.icon"
            :link="action.url"
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
import {onMounted, ref} from 'vue';

const props = defineProps({
    company: Object,
    can: Object,
});

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
        permission: 'manage_roles',
        title: 'Manage roles',
        description: 'You can manage your organization roles & permissions.',
        url: '/company/roles',
        icon: BadgeCheckIcon,
    },
    {
        permission: 'manage_employees',
        title: 'Manage employees',
        description: 'You can manage your company\'s employees.',
        url: '/company/employees',
        icon: UserGroupIcon,
    },
    {
        permission: 'manage_next_month_schedule',
        title: 'Manage schedule',
        description: 'You can manage scheduling for company\'s workers.',
        url: '/shifts/create',
        icon: CalendarIcon,
    },
]);

onMounted(() => {
    availableActions.value = availableActions.value.filter(action => props.can[action.permission]);
});
</script>

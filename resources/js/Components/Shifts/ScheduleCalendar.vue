<template>
  <Calendar
    id-name="next-month-calendar"
    editable
    :events="companyShifts"
    :custom-buttons="customButtons"
    :header-toolbar="headerToolbar"
    :initial-date="initialDate"
    :button-text="{month: 'Month view'}"
    :event-drop="eventDrop"
    :event-click="eventClick"
    :dates-set="datesSet"
  />

  <FillableModal
    :show="showShiftModal"
    @close="closeShiftModal"
  >
    <template #title>
      Edit shift at {{ selectedShift.shift.start }}
    </template>
    <template #body>
      <Select
        v-model="workerName"
        class-name="mb-4"
        id-name="worker"
        :list="workers"
      >
        Worker
      </Select>
    </template>
    <template #footer>
      <p
        v-show="workerRequests"
        class="text-sm text-gray-500 pb-2"
      >
        Request for the shift: <span class="lowercase font-semibold">{{ workerRequests?.title }}</span>
      </p>
      <DefaultButton
        type="button"
        @click="saveShift"
      >
        Save
      </DefaultButton>
    </template>
  </FillableModal>
</template>


<script setup>
import Calendar from '@/Components/Calendar';
import FillableModal from '@/Components/Modals/FillableModal';
import Select from '@/Components/Inputs/Select';
import DefaultButton from '@/Components/Buttons/DefaultButton';
import {useShifts} from '@/Composables/useShifts';
import {onMounted, ref, watch} from 'vue';
import {Inertia} from '@inertiajs/inertia';

const props = defineProps({
    accounts: Array,
    monthsFromCurrent: Number,
    canGenerateSchedule: Boolean
});

const workers = ref([]);

const companyShifts = ref([]);
const originalCompanyShift = ref([]);

const showShiftModal = ref(false);
const selectedShift = ref(null);
const workerName = ref(null);
const workerRequests = ref(null);
const currentDate = ref(null);

const dateOptions = {
    year: 'numeric',
    month: '2-digit',
    day: '2-digit',
    hour: '2-digit',
    minute: '2-digit',
    second: '2-digit',
};

const headerToolbar = ref({
    left: 'startAutomaticGeneration',
    center: 'title',
    right: 'prev next saveChanges',
});

const customButtons = ref({
    startAutomaticGeneration: {
        text: 'Start automatic generation',
        click: () => {
            const date = new Date(currentDate.value).toLocaleDateString('lt-LT', {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
            });
            Inertia.get(`/shifts/generate/${date}`, {}, {
                preserveState: true,
                onSuccess: () => {
                    initialDate.value = new Date(currentDate.value).getTime();
                },
                onError: () => {
                    initialDate.value = new Date(currentDate.value).getTime();
                }
            });
        },
    },
    saveChanges: {
        text: 'Save changes',
        click: () => {
            Inertia.post('/shifts/save', companyShifts.value, {
                onError: () => {
                    companyShifts.value = originalCompanyShift.value;
                },
            });
        },
    },
});

const initialDate = ref(new Date().setMonth(new Date().getMonth() + parseInt(props.monthsFromCurrent)));

const closeShiftModal = () => {
    showShiftModal.value = false;
};

const eventClick = info => {
    const { event } = info;

    showShiftModal.value = true;
    selectedShift.value = {
        shift: companyShifts.value[event.start.getDate() - 1],
        info: info,
    };
    workerName.value = selectedShift.value?.shift.worker.name;
};

const saveShift = () => {
    const index = props.accounts.map(account => account.name).indexOf(workerName.value);
    const email = props.accounts[index].email;
    const { info } = selectedShift.value;

    companyShifts.value[info.event.start.getDate() - 1] = {
        id: info.event.id,
        title: workerName.value,
        allDay: info.event.allDay,
        start: info.event.start.toLocaleDateString('lt-LT', dateOptions),
        end: info.event.end.toLocaleDateString('lt-LT', dateOptions),
        worker: {
            name: workerName.value,
            email: email,
        },
    };

    info.view.calendar.getEventById(info.event.id).remove();
    info.view.calendar.addEvent({
        id: info.event.id,
        title: workerName.value,
        allDay: info.event.allDay,
        start: info.event.start,
        end: info.event.end,
    });

    showShiftModal.value = false;
};

const eventDrop = info => {
    const {start, end} = info.event;

    const newDate = new Date(start);
    const oldDate = new Date(info.oldEvent.start);

    const event = companyShifts.value[oldDate.getDate() - 1];
    const eventOnNewDate = companyShifts.value[newDate.getDate() - 1];

    const newStart = new Date(start);
    const newEnd = new Date(end);

    const oldStart = new Date(info.oldEvent.start);
    const oldEnd = new Date(info.oldEvent.end);

    companyShifts.value[oldDate.getDate() - 1] = {
        id: eventOnNewDate.id,
        title: eventOnNewDate.title,
        allDay: eventOnNewDate.allDay,
        start: oldStart.toLocaleDateString('lt-LT', dateOptions),
        end: oldEnd.toLocaleDateString('lt-LT', dateOptions),
        worker: {
            name: eventOnNewDate.worker.name,
            email: eventOnNewDate.worker.email,
        },
    };

    companyShifts.value[newDate.getDate() - 1] = {
        id: event.id,
        title: event.title,
        allDay: event.allDay,
        start: newStart.toLocaleDateString('lt-LT', dateOptions),
        end: newEnd.toLocaleDateString('lt-LT', dateOptions),
        worker: {
            name: event.worker.name,
            email: event.worker.email,
        },
    };

    info.view.calendar.getEventById(eventOnNewDate.id).remove();
    info.view.calendar.addEvent({
        id: eventOnNewDate.id,
        title: eventOnNewDate.title,
        allDay: false,
        start: info.oldEvent.start,
        end: info.oldEvent.end,
    });
};

const getWorkerRequestsForShift = (name, shiftDate) => {
    const worker = props.accounts.filter(account => account.name === name)[0];
    if (! worker.requests) {
        return null;
    }

    return worker.requests.filter(request => request.start === shiftDate)[0] ?? null;
};

const datesSet = async info => {
    if (new Date(currentDate.value).getTime() === new Date(info.start).getTime()) {
        return;
    }

    currentDate.value = info.start;

    const { getShift } = useShifts();
    companyShifts.value = await getShift(new Date(info.start).toLocaleDateString('lt-LT', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
    }));

    originalCompanyShift.value = companyShifts.value;
};

watch(workerName, value => {
    workerRequests.value = getWorkerRequestsForShift(
        value,
        new Date(selectedShift.value.shift.start).toLocaleString('lt-LT', {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit',
        })
    );
});

onMounted(() => {
    props.accounts.forEach(account => {
        workers.value.push(account.name);
    });

    if (!props.canGenerateSchedule) {
        headerToolbar.value.left = '';
    }
});

</script>

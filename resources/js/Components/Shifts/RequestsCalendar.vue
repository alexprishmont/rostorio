<template>
  <FullCalendar :options="options" />
</template>

<script setup>

import {defineEmits, onMounted, reactive, ref} from 'vue';
import FullCalendar from '@fullcalendar/vue3';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';
import interactionPlugin from '@fullcalendar/interaction';

import {useShiftRequests} from '@/Composables/useShiftRequests';

const emits = defineEmits(['select', 'eventClick']);
const props = defineProps({
    editable: Boolean,
    selectable: Boolean,
    droppable: Boolean,
    userId: Number,
    headerToolbar: Object,
    initialDate: String,
});

const {getRequestsByUserId} = useShiftRequests();
const events = ref([]);

onMounted(async () => {
    await reloadEvents();
});

const options = reactive({
    plugins: [dayGridPlugin, timeGridPlugin, listPlugin, interactionPlugin],
    initialView: 'dayGridMonth',
    headerToolbar: props.headerToolbar,
    editable: false,
    selectable: true,
    droppable: false,
    select: data => {
        const date1 = new Date(data.startStr);
        const date2 = new Date(data.endStr);
        const diffTime = Math.abs(date2 - date1);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

        if (diffDays > 1) {
            return;
        }

        emits('select', data);
    },
    eventClick: (data) => emits('eventClick', data),
    initialDate: props.initialDate,
    firstDay: 1,
    showNonCurrentDates: false,
    events,
});

const reloadEvents = async () => {
    events.value = await getRequestsByUserId(props.userId);
};

defineExpose({ reloadEvents });

</script>

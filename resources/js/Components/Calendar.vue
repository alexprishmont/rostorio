<template>
  <section class="rounded-lg bg-white overflow-hidden shadow space-y-8 divide-y divide-gray-200">
    <div class="p-6 space-y divide-y divide-gray-200 sm:space-y-5">
      <FullCalendar
        :id="idName"
        :options="options"
      />
    </div>
  </section>
</template>

<script setup>
import {reactive, defineEmits, defineProps, onMounted, ref, watch} from 'vue';
import FullCalendar from '@fullcalendar/vue3';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';
import interactionPlugin from '@fullcalendar/interaction';
import ltLocale from '@fullcalendar/core/locales/lt';

const props = defineProps({
    idName: String,
    editable: Boolean,
    selectable: Boolean,
    maxSelect: String,
    events: Array,
    customButtons: Object,
    headerToolbar: Object,
    initialDate: Number,
    buttonText: Object,
    dateClick: Function,
    eventDragStop: Function,
    eventDrop: Function,
    eventClick: Function,
    datesSet: Function,
});

const emits = defineEmits(['select']);

const newEvents = ref([]);

onMounted(() => {
    if (props.events) {
        props.events.forEach(event => {
            const {id, title, allDay, start, end} = event;

            newEvents.value.push({
                id,
                title,
                allDay,
                start,
                end,
            });
        });
    }
});

watch(() => props.events, updatedEvents => {
    if (JSON.stringify(updatedEvents) !== JSON.stringify(newEvents.value)) {

        newEvents.value = [];

        updatedEvents.forEach(event => {
            const {id, title, allDay, start, end} = event;

            newEvents.value.push({
                id,
                title,
                allDay,
                start,
                end,
            });
        });
    }
});

const options = reactive({
    plugins: [dayGridPlugin, timeGridPlugin, listPlugin, interactionPlugin],
    initialView: 'dayGridMonth',
    customButtons: props.customButtons ?? {},
    headerToolbar: props.headerToolbar ?? {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth timeGridWeek',
    },
    editable: props.editable,
    selectable: props.selectable,
    select: data => {
        const date1 = new Date(data.startStr);
        const date2 = new Date(data.endStr);
        const diffTime = Math.abs(date2 - date1);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

        if (props.maxSelect && props.maxSelect < diffDays) {
            return;
        }

        emits('select', data);
    },
    dateClick: props.dateClick,
    events: newEvents,
    initialDate: props.initialDate,
    buttonText: props.buttonText,
    eventDragStop: props.eventDragStop,
    eventDrop: props.eventDrop,
    firstDay: 1,
    showNonCurrentDates: false,
    eventClick: props.eventClick,
    datesSet: props.datesSet,
    locales: [ ltLocale ],
    locale: 'lt',
});

</script>

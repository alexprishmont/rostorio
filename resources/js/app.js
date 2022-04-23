import {createApp, h} from 'vue';
import {createInertiaApp, Head, Link} from '@inertiajs/inertia-vue3';
import {InertiaProgress} from '@inertiajs/progress';

import '@vueform/multiselect/themes/default.css';
import '@vuepic/vue-datepicker/dist/main.css';

createInertiaApp({
    resolve: async name => (await import(`@/Pages/${name}`)).default,
    setup({el, App, props, plugin}) {
        createApp({render: () => h(App, props)})
            .use(plugin)
            .component('Head', Head)
            .component('Link', Link)
            .mount(el);
    },
    title: title => `Rostor.io - ${title}`,
});

InertiaProgress.init({
    showSpinner: true,
});

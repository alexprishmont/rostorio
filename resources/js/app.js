import {createApp, h} from 'vue'
import {createInertiaApp, Head, Link} from '@inertiajs/inertia-vue3'
import {InertiaProgress} from '@inertiajs/progress'
import Layout from './Layouts/Layout'
import {__, getLocale, locales, setLocale, trans, transChoice} from 'matice'

createInertiaApp({
    resolve: async name => {
        const page = (await import(`./Pages/${name}`)).default;

        if (page.layout === undefined) {
            page.layout = Layout;
        }

        if (page.props?.layout === null) {
            page.layout = undefined;
        }

        return page
    },
    setup({el, App, props, plugin}) {
        const app = createApp({render: () => h(App, props)})
            .use(plugin)
            .component('Head', Head)
            .component('Link', Link)
            .mixin({
                methods: {
                    $trans: trans,
                    $__: __,
                    $transChoice: transChoice,
                    $setLocale(locale) {
                        if (locale !== getLocale()) {
                            setLocale(locale);
                            app.$forceUpdate()
                        }
                    },
                    $locale() {
                        return getLocale()
                    },
                    $locales() {
                        return locales()
                    },
                },
            })
            .mount(el)
    },
    title: title => `Rostor.io - ${title}`,
})

InertiaProgress.init()

import './bootstrap';

import '../css/app.css';

import { createApp, h } from 'vue';

import Home from './components/pages/home/home.vue';
import Search from './components/pages/search/search.vue';
import Chat from './components/pages/chat/chat.vue';

import { createInertiaApp } from '@inertiajs/vue3'

import DefaultLayout from './components/layouts/default.vue';
import Toaster from 'vanilla-toaster';


createInertiaApp({
    resolve: name => {
        const pages = import.meta.glob('./components/pages/**/*.vue', { eager: true })
        let page = pages[`./components/pages/${name}.vue`];
        // If the page doesn't have a layout, set the default layout
        if (page.default) {
            page.default.layout = page.default.layout || DefaultLayout;
        }
        return page;
    },
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) });
        app.use(plugin);
        app.component('home', Home);
        app.component('search', Search);
        app.component('chat', Chat);

        app.mount(el)

        const toaster = document.createElement('div');
        document.body.appendChild(toaster);
        new Toaster(toaster);
    },
})


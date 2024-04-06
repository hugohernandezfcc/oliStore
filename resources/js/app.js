import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp, Link } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import DataTablesLib from 'datatables.net'; 
import DataTable from 'datatables.net-vue3';
import axios from 'axios';
import ElementPlus from 'element-plus';
import 'element-plus/dist/index.css';
import { Search, Edit } from '@element-plus/icons-vue';

DataTable.use(DataTablesLib);

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const app =  createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(ElementPlus)
            .component('DataTable', DataTable)
            .component('search', Search)
            .component('edit', Edit)
            .component('inertia-link', Link) 
            .mount(el);

        return app;
    },
    progress: {
        color: '#4B5563',
    },
});


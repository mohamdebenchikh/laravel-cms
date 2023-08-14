import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import Toast from "vue-toastification";
import { createI18n } from 'vue-i18n'

import '../css/app.css'
import 'bootstrap/dist/js/bootstrap.bundle'
import 'bootstrap-icons/font/bootstrap-icons.min.css'
import "vue-toastification/dist/index.css";
import messages from './lang'

const i18n = createI18n({
  locale: window.locale,
  fallbackLocale: 'en',
  messages,
});

createInertiaApp({
  resolve: name => {
    const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
    return pages[`./Pages/${name}.vue`]
  },
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(ZiggyVue, Ziggy)
      .use(Toast, {})
      .use(i18n)
      .mount(el)
  },
})
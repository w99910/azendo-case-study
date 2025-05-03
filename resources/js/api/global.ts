import { reactive } from "vue";

export default {
    data: reactive({
        isDarkMode: false,
        currentPage: 'home',
    })
}

import axios from 'axios';

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

export { axios };
import './bootstrap';

import '../css/app.css';

import { createApp, ref } from 'vue';

import App from './components/app.vue';
import Home from './components/pages/home/home.vue';
import Search from './components/pages/search/search.vue';
import Chat from './components/pages/chat/chat.vue';

const app = createApp(App);

app.component('home', Home);
app.component('search', Search);
app.component('chat', Chat);


app.mount('#app')
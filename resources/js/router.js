import { createRouter, createWebHistory } from 'vue-router';
import Dashboard from './components/Dashboard.vue';
import FormBuilder from './components/FormBuilder.vue';

const routes = [
  { path: '/', component: Dashboard },
  { path: '/create', component: FormBuilder }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router;
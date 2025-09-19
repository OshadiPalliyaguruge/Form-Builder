import { createRouter, createWebHistory } from 'vue-router';
import Dashboard from './components/Dashboard.vue';
import FormBuilder from './components/FormBuilder.vue';
import FormsList from './components/FormsList.vue';
import FormPreview from './components/FormPreview.vue';

const routes = [
  { path: '/', component: Dashboard },
  { path: '/create', component: FormBuilder },
  { path: '/forms', component: FormsList },
  { path: '/edit/:id', component: FormBuilder, props: true },
  { path: '/preview/:id', component: FormPreview, props: true }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router;
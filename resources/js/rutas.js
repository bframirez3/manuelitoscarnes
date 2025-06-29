import { createRouter, createWebHistory } from 'vue-router';

const routes = [
    {
        path: '/reportes',
        name: 'reportes',
        component: () => import('./comp/Reportes.vue'), // Asegúrate de usar la extensión .vue
    },
    {
        path: '/inventarios',
        name: 'inventarios',
        component: () => import('./comp/Inventarios.vue'),
    },
    {
        path: '/productos',
        name: 'productos',
        component: () => import('./comp/Productos.vue'),
    },
    {
        path: '/container',
        name: 'container',
        component: () => import('./comp/container.vue'),
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
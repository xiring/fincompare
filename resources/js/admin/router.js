import { createRouter, createWebHistory } from 'vue-router';

// Import layouts
import AdminLayout from './layouts/AdminLayout.vue';

// Import critical pages (used immediately on load)
import Login from './pages/auth/Login.vue';
import Dashboard from './pages/Dashboard.vue';

const routes = [
    {
        path: '/login',
        name: 'admin.login',
        component: Login,
        meta: {
            title: 'Login',
            requiresGuest: true
        }
    },
    {
        path: '/admin',
        component: AdminLayout,
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'admin.dashboard',
                component: Dashboard,
                meta: { title: 'Dashboard' }
            },
            {
                path: 'products',
                name: 'admin.products.index',
                component: () => import('./pages/products/Index.vue'),
                meta: { title: 'Products' }
            },
            {
                path: 'products/create',
                name: 'admin.products.create',
                component: () => import('./pages/products/Create.vue'),
                meta: { title: 'Create Product' }
            },
            {
                path: 'products/:id/edit',
                name: 'admin.products.edit',
                component: () => import('./pages/products/Edit.vue'),
                meta: { title: 'Edit Product' }
            },
            {
                path: 'products/import',
                name: 'admin.products.import',
                component: () => import('./pages/products/Import.vue'),
                meta: { title: 'Import Products' }
            },
            // Placeholder routes for other admin pages
            {
                path: 'partners',
                name: 'admin.partners.index',
                component: () => import('./pages/partners/Index.vue'),
                meta: { title: 'Partners' }
            },
            {
                path: 'partners/create',
                name: 'admin.partners.create',
                component: () => import('./pages/partners/Create.vue'),
                meta: { title: 'Create Partner' }
            },
            {
                path: 'partners/:id/edit',
                name: 'admin.partners.edit',
                component: () => import('./pages/partners/Edit.vue'),
                meta: { title: 'Edit Partner' }
            },
            {
                path: 'leads',
                name: 'admin.leads.index',
                component: () => import('./pages/leads/Index.vue'),
                meta: { title: 'Leads' }
            },
            {
                path: 'users',
                name: 'admin.users.index',
                component: () => import('./pages/users/Index.vue'),
                meta: { title: 'Users' }
            },
            {
                path: 'users/create',
                name: 'admin.users.create',
                component: () => import('./pages/users/Create.vue'),
                meta: { title: 'Create User' }
            },
            {
                path: 'users/:id/edit',
                name: 'admin.users.edit',
                component: () => import('./pages/users/Edit.vue'),
                meta: { title: 'Edit User' }
            },
            {
                path: 'product-categories',
                name: 'admin.product-categories.index',
                component: () => import('./pages/product-categories/Index.vue'),
                meta: { title: 'Product Categories' }
            },
            {
                path: 'product-categories/create',
                name: 'admin.product-categories.create',
                component: () => import('./pages/product-categories/Create.vue'),
                meta: { title: 'Create Product Category' }
            },
            {
                path: 'product-categories/:id/edit',
                name: 'admin.product-categories.edit',
                component: () => import('./pages/product-categories/Edit.vue'),
                meta: { title: 'Edit Product Category' }
            },
            {
                path: 'attributes',
                name: 'admin.attributes.index',
                component: () => import('./pages/attributes/Index.vue'),
                meta: { title: 'Attributes' }
            },
            {
                path: 'attributes/create',
                name: 'admin.attributes.create',
                component: () => import('./pages/attributes/Create.vue'),
                meta: { title: 'Create Attribute' }
            },
            {
                path: 'attributes/:id/edit',
                name: 'admin.attributes.edit',
                component: () => import('./pages/attributes/Edit.vue'),
                meta: { title: 'Edit Attribute' }
            },
            {
                path: 'roles',
                name: 'admin.roles.index',
                component: () => import('./pages/roles/Index.vue'),
                meta: { title: 'Roles' }
            },
            {
                path: 'roles/create',
                name: 'admin.roles.create',
                component: () => import('./pages/roles/Create.vue'),
                meta: { title: 'Create Role' }
            },
            {
                path: 'roles/:id/edit',
                name: 'admin.roles.edit',
                component: () => import('./pages/roles/Edit.vue'),
                meta: { title: 'Edit Role' }
            },
            {
                path: 'permissions',
                name: 'admin.permissions.index',
                component: () => import('./pages/permissions/Index.vue'),
                meta: { title: 'Permissions' }
            },
            {
                path: 'permissions/create',
                name: 'admin.permissions.create',
                component: () => import('./pages/permissions/Create.vue'),
                meta: { title: 'Create Permission' }
            },
            {
                path: 'permissions/:id/edit',
                name: 'admin.permissions.edit',
                component: () => import('./pages/permissions/Edit.vue'),
                meta: { title: 'Edit Permission' }
            },
            {
                path: 'leads',
                name: 'admin.leads.index',
                component: () => import('./pages/leads/Index.vue'),
                meta: { title: 'Leads' }
            },
            {
                path: 'leads/:id',
                name: 'admin.leads.show',
                component: () => import('./pages/leads/Show.vue'),
                meta: { title: 'View Lead' }
            },
            {
                path: 'forms',
                name: 'admin.forms.index',
                component: () => import('./pages/forms/Index.vue'),
                meta: { title: 'Forms' }
            },
            {
                path: 'forms/create',
                name: 'admin.forms.create',
                component: () => import('./pages/forms/Create.vue'),
                meta: { title: 'Create Form' }
            },
            {
                path: 'forms/:id/edit',
                name: 'admin.forms.edit',
                component: () => import('./pages/forms/Edit.vue'),
                meta: { title: 'Edit Form' }
            },
            {
                path: 'blogs',
                name: 'admin.blogs.index',
                component: () => import('./pages/blogs/Index.vue'),
                meta: { title: 'Blogs' }
            },
            {
                path: 'blogs/create',
                name: 'admin.blogs.create',
                component: () => import('./pages/blogs/Create.vue'),
                meta: { title: 'Create Blog' }
            },
            {
                path: 'blogs/:id/edit',
                name: 'admin.blogs.edit',
                component: () => import('./pages/blogs/Edit.vue'),
                meta: { title: 'Edit Blog' }
            },
            {
                path: 'cms-pages',
                name: 'admin.cms-pages.index',
                component: () => import('./pages/cms-pages/Index.vue'),
                meta: { title: 'CMS Pages' }
            },
            {
                path: 'cms-pages/create',
                name: 'admin.cms-pages.create',
                component: () => import('./pages/cms-pages/Create.vue'),
                meta: { title: 'Create CMS Page' }
            },
            {
                path: 'cms-pages/:id/edit',
                name: 'admin.cms-pages.edit',
                component: () => import('./pages/cms-pages/Edit.vue'),
                meta: { title: 'Edit CMS Page' }
            },
            {
                path: 'faqs',
                name: 'admin.faqs.index',
                component: () => import('./pages/faqs/Index.vue'),
                meta: { title: 'FAQs' }
            },
            {
                path: 'faqs/create',
                name: 'admin.faqs.create',
                component: () => import('./pages/faqs/Create.vue'),
                meta: { title: 'Create FAQ' }
            },
            {
                path: 'faqs/:id/edit',
                name: 'admin.faqs.edit',
                component: () => import('./pages/faqs/Edit.vue'),
                meta: { title: 'Edit FAQ' }
            },
            {
                path: 'activity',
                name: 'admin.activity.index',
                component: () => import('./pages/activity/Index.vue'),
                meta: { title: 'Activity' }
            },
            {
                path: 'settings',
                name: 'admin.settings.edit',
                component: () => import('./pages/settings/Edit.vue'),
                meta: { title: 'Settings' }
            },
            {
                path: 'profile',
                name: 'admin.profile.edit',
                component: () => import('./pages/profile/Edit.vue'),
                meta: { title: 'Profile' }
            },
        ]
    },
    {
        path: '/',
        redirect: '/admin'
    },
    {
        path: '/:pathMatch(.*)*',
        redirect: '/admin'
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
    scrollBehavior(to, from, savedPosition) {
        if (savedPosition) {
            return savedPosition;
        }
        if (to.hash) {
            return { el: to.hash, behavior: 'smooth' };
        }
        return { top: 0, left: 0, behavior: 'auto' };
    }
});

// Navigation guards
router.beforeEach((to, from, next) => {
    // Update document title
    const baseTitle = 'Admin - FinCompare';
    document.title = to.meta.title ? `${to.meta.title} - ${baseTitle}` : baseTitle;

    // For now, skip auth check - Laravel middleware handles it
    // You can add client-side auth check here if needed
    next();
});

export default router;


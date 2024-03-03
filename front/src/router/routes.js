const routes = {
    home: {
        path: '/',
        redirect: {
            name: 'addAnnouncement',
        },
    },
    addAnnouncement: {
        path: '/announcements/add',
        component: () => import('../views/AddAnnouncementView.vue'),
    },
    listAnnouncements: {
        path: '/announcements/list',
        component: () => import('../views/ListAnnouncementsView.vue')
    },
    showAnnouncement: {
        path: '/announcements/:id',
        component: () => import('../views/ShowAnnouncementView.vue')
    },
    login: {
        path: '/login',
        component: () => import('../views/LoginView.vue'),
        props: true,
    },
    logout: {
        path: '/logout',
        component: () => import('../views/LogoutView.vue')
    },
};

export default routes;
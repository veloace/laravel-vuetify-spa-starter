import VueRouter from 'vue-router';
import auth from './utils/auth';


let loginRequired  = (to, from, next) => {
    if(!auth.user.is_authenticated)
    {//must be logged in, but they are not. So take them to the home page.
        next({
            name: 'home'
        });
    }
    else
    {//let them proceeded.
        return next();
    }
};

let guestRequired  = (to, from, next) => {

       if(auth.user.is_authenticated)
       {//must be a guest, but they are not. So take them to the home page.
           next({
               name: 'home'
           });
       }
       else
       {//let them proceeded.
           return next();
       }
};

const routes =[
    {
        path: '/register',
        component: require('./views/Register.vue').default,
        name:'register',
        beforeEnter:guestRequired

    },
    {
        path: '/login',
        component: require('./views/Login.vue').default,
        name:'login',
        beforeEnter:guestRequired

    },
    {
        path: '/password/forgot',
        component: require('./views/ForgotPassword.vue').default,
        name:'forgotPassword',
        beforeEnter:guestRequired

    },
    {
        path: '/password/reset',
        component: require('./views/resetPassword.vue').default,
        name:'resetPassword',
        beforeEnter:guestRequired

    },

    {
        path: '/home',
        component: require('./views/Home.vue').default,
        name:'home'
    },
];


export default new VueRouter({
    mode: 'history',
    base: '/',
    routes,
    hashbang: false,
    scrollBehavior (to, from, savedPosition) {
        return { x: 0, y: 0 }//scrolls to top on route change
    }
});
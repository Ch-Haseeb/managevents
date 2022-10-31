import VueRouter from 'vue-router'

const Welcome = () => import('./components/LoginComponent.vue')
const Register = () => import('./components/RegistrationComponent.vue')
const Calandar = () => import('./components/FullCalandar.vue')
const Event = () => import('./components/Event.vue')
function guardMyroute (to, from, next) {
    let isAuthenticated = false
   // this is just an example. You will have to find a better or
   // centralised way to handle you localstorage data handling
   if (localStorage.getItem('usertoken')) { isAuthenticated = true } else { isAuthenticated = false }
    if (isAuthenticated) {
     next() // allow to enter route
    } else {
     next('/') // go to '/login';
     console.log('un-authenticated')
    }
   }
export const routes = [
    {
        name: 'home',
        path: '/',
        component: Welcome
    },
    {
        name: 'Calandar',
        path: '/calandar',
        component: Calandar,
        beforeEnter: guardMyroute,
    },
    {
        name: 'Registration',
        path: '/register',
        component: Register,
    },
    {
        name: 'Event',
        path: '/event',
        component: Event,
        beforeEnter: guardMyroute,
    },

   
]
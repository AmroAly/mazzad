import Vue from 'vue';
import VueRouter from 'vue-router';

import Home from '../components/Home.vue'
import Login from '../components/auth/Login.vue'
import Register from '../components/auth/Register.vue'
import Dashboard from '../components/admin/Dashboard.vue'
import AuctionsList from '../components/auctions/AuctionsList.vue'
import AddAuction from '../components/auctions/AddAuction.vue'
import SingleAuction from '../components/auctions/SingleAuction.vue'
import OpenAuctionsList from '../components/auctions/OpenAuctionsList.vue'
import ClosedAuctionsList from '../components/auctions/ClosedAuctionsList.vue'
import Auth from './../store/auth'

Vue.use(VueRouter)
Auth.check()


const routes = [
    { path: '/', component: Home },
    { path: '/login', component: Login, meta: {  requiredNotAuth: true} },
    { path: '/register', component: Register, meta: {  requiredNotAuth: true} },
    { path: '/dashboard', component: Dashboard, meta: { requiredAdminPermission: true, requiredAuth: true }, name:"dashboard", children: [
        { path: "auctions", component: AuctionsList, name:"auctions_list", meta: { requiredAdminPermission: true, requiredAuth: true } },
        { path: "auctions/open", component: OpenAuctionsList, name:"auctions_open", meta: { requiredAdminPermission: true, requiredAuth: true } },
        { path: "auctions/closed", component: ClosedAuctionsList, name:"auctions_closed", meta: { requiredAdminPermission: true, requiredAuth: true } },
        { path: "auctions/add", component: AddAuction, name:"auctions_add", meta: { requiredAdminPermission: true, requiredAuth: true } },
        { path: "auctions/:id", component: SingleAuction, name:"auctions_single", meta: { requiredAdminPermission: true, requiredAuth: true } }
    ] }
]

const router = new VueRouter({
    mode: 'history',
    routes
})

router.beforeEach((to, from, next) => {
    if(to.meta.requiredAuth && !Auth.state.authenticated) {
        next('/login')
    }
    if(to.meta.requiredAdminPermission && Auth.state.is_admin == "false") {
        next('/')
    }
    if(to.matched.some(record => record.meta.requiredNotAuth) && Auth.state.authenticated) {
        console.log('called')
        next('/')
    } 
    else {
        next()
    }
})

export default router;
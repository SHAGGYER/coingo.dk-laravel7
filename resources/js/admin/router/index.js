import Vue from 'vue';
import VueRouter from "vue-router";

Vue.use(VueRouter);

import Dashboard from "../pages/Dashboard";
import Feedback from "../pages/Feedback";
import ContactMessages from "../pages/ContactMessages";
import Users from "../pages/Users";
import Admins from "../pages/Admins";

const routes=  [

  {
    path: '/',
    component: Dashboard
  },
  {
    path: '/feedback',
    component: Feedback
  },
  {
    path: '/contact-messages',
    component: ContactMessages
  },
  {
    path: '/users',
    component: Users
  },
  {
    path: '/admins',
    component: Admins
  }

];

export default new VueRouter({
  routes
})

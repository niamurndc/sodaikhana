import Vue from 'vue'
import VueRouter from 'vue-router'
import Home from './components/Home.vue'
import Admin from './components/Admin.vue'
import Dashboard from './components/admins/Dashboard.vue'
import Product from './components/admins/Product.vue'
import Order from './components/admins/Order.vue'
import Customer from './components/admins/Customer.vue'
import Request from './components/admins/Request.vue'
import AddProduct from './components/admins/AddProduct.vue'
import EditProduct from './components/admins/EditProduct.vue'
import Front from './components/home/Front.vue'
import Category from './components/home/Category.vue'
import Search from './components/home/Search.vue'
import FrontAuth from './components/FrontAuth.vue'
import Login from './components/auth/Login.vue'
import Register from './components/auth/Register.vue'
import Varify from './components/auth/Varify.vue'
import Forgot from './components/auth/Forgot.vue'
import FontOrder from './components/fontauth/Order.vue'
import Profile from './components/fontauth/Profile.vue'
import List from './components/fontauth/List.vue'

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    component: Home,
    children: [
      {
        path: '/',
        component: Front
      },
      {
        path: '/category/:id',
        component: Category
      },
      {
        path: 'search/:search',
        component: Search
      }
    ]
  },
  {
    path: '/user',
    component: FrontAuth,
    children: [
      {
        path: 'checkout',
        component: FontOrder,
      },
      {
        path: 'profile',
        component: Profile,
      },
      {
        path: 'orders',
        component: List,
      }
    ]
  },
  {
    path: '/login',
    component: Login,
  },
  {
    path: '/register',
    component: Register,
  },
  {
    path: '/varify',
    component: Varify,
  },
  {
    path: '/forgot',
    component: Forgot,
  },
  {
    path: '/admin',
    component: Admin,
    children: [
      {
        path: '/',
        component: Dashboard
      },
      {
        path: 'product',
        component: Product
      },
      {
        path: 'order',
        component: Order
      },
      {
        path: 'customer',
        component: Customer
      },
      {
        path: 'request',
        component: Request
      },
      {
        path: 'addproduct',
        component: AddProduct
      },
      {
        path: 'editproduct/:id',
        component: EditProduct
      }
    ]
  }
]

const router = new VueRouter({
  mode: "history",
  routes,
})

export default router
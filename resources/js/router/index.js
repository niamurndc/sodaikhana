import { createRouter, createWebHashHistory } from "vue-router";
import Home from '@/Main/Page/Home.vue'
import Dashboard from '@/Main/Page/Admin/Dashboard.vue'
import Order from '@/Main/Page/Admin/Order.vue'
import OrderSend from '@/Main/Page/Admin/OrderSend.vue'
import Customer from '@/Main/Page/Admin/Customer.vue'
import Story from '@/Main/Page/Admin/Story.vue'
import ViewOrder from '@/Main/Page/Admin/ViewOrder.vue'
import PrintOrder from '@/Main/Page/Admin/PrintOrder.vue'
import AddOrder from '@/Main/Page/Admin/AddOrder.vue'
import AddProduct from '@/Main/Page/Admin/AddProduct.vue'
import POS from '@/Main/Page/Admin/POS.vue'
import DailySale from '@/Main/Page/Admin/DailySale.vue'
import ProductSale from '@/Main/Page/Admin/ProductSale.vue'
import Expance from '@/Main/Page/Admin/Expance.vue'
import Profit from '@/Main/Page/Admin/Profit.vue'
import EditProduct from '@/Main/Page/Admin/EditProduct.vue'
import Product from '@/Main/Page/Admin/Product.vue'
import ProductRequest from '@/Main/Page/Admin/ProductRequest.vue'
import Stock from '@/Main/Page/Admin/Stock.vue'
import Setting from '@/Main/Page/Admin/Setting.vue'
import Area from '@/Main/Page/Admin/Area.vue'
import SmsDash from '@/Main/Page/Admin/SmsDash.vue'
import ViewList from '@/Main/Page/Admin/ViewList.vue'
import SendSMS from '@/Main/Page/Admin/SendSMS.vue'
import Admin from '@/Main/Page/Admin.vue'


// auth route
import Login from '@/Main/Page/auth/Login.vue'
import Register from '@/Main/Page/auth/Register.vue'
import Varify from '@/Main/Page/auth/Varify.vue'
import Forgot from '@/Main/Page/auth/Forgot.vue'

import FrontPage from '@/Main/Page/Front/FrontPage.vue'
import Category from '@/Main/Page/Front/Category.vue'
import Search from '@/Main/Page/Front/Search.vue'

// user auth
import User from '@/Main/Page/User.vue'
import FontOrder from '@/Main/Page/User/Order.vue'
import Success from '@/Main/Page/User/Success.vue'
import Profile from '@/Main/Page/User/Profile.vue'
import List from '@/Main/Page/User/List.vue'

const routes = [
    {
        path: '/',
        name: 'Home',
        component: Home,
        children: [
            {
                path: '',
                name: 'FrontPage',
                component: FrontPage
            },
            {
                path: 'category/:id',
                name: 'Category',
                component: Category
            },
            {
                path: 'search/:search',
                name: 'Search',
                component: Search
            }
        ]
    },
    {
        path: '/user',
        component: User,
        children: [
          {
            path: 'checkout',
            name: 'FrontOrder',
            component: FontOrder,
          },
          {
            path: 'profile',
            name: 'Profile',
            component: Profile,
          },
          {
            path: 'orders',
            name: 'List',
            component: List,
          },
          {
            path: 'success',
            name: 'Success',
            component: Success,
          }
        ]
    },
    {
        path: '/login',
        name: 'Login',
        component: Login
    },
    {
        path: '/register',
        name: 'Register',
        component: Register,
    },
    {
        path: '/varify',
        name: 'Varify',
        component: Varify,
    },
    {
        path: '/forgot',
        name: 'Forgot',
        component: Forgot,
    },
    {
        path: '/admin',
        component: Admin,
        children: [
            {
                path: '',
                name: 'Dashboard',
                component: Dashboard, 
            },
            {
                path: 'orders',
                name: 'Order',
                component: Order, 
            },
            {
                path: 'view-order/:id',
                name: 'ViewOrder',
                component: ViewOrder, 
            },
            {
                path: 'print-order/:id',
                name: 'PrintOrder',
                component: PrintOrder, 
            },
            {
                path: 'order/send-sms/:id',
                name: 'OrderSend',
                component: OrderSend, 
            },
            {
                path: 'add-order',
                name: 'AddOrder',
                component: AddOrder, 
            },
            {
                path: 'pos',
                name: 'POS',
                component: POS, 
            },
            {
                path: 'daily-sale',
                name: 'DailySale',
                component: DailySale, 
            },
            {
                path: 'product-sale',
                name: 'ProductSale',
                component: ProductSale, 
            },
            {
                path: 'expance',
                name: 'Expance',
                component: Expance, 
            },
            {
                path: 'profit-loss',
                name: 'Profit',
                component: Profit, 
            },
            {
                path: 'customers',
                name: 'Customer',
                component: Customer, 
            },
            {
                path: 'story',
                name: 'Story',
                component: Story, 
            },
            {
                path: 'pro-request',
                name: 'ProductRequest',
                component: ProductRequest, 
            },
            {
                path: 'products',
                name: 'Product',
                component: Product, 
            },
            {
                path: 'add-product',
                name: 'AddProduct',
                component: AddProduct, 
            },
            {
                path: 'edit-product/:id',
                name: 'EditProduct',
                component: EditProduct, 
            },
            {
                path: 'stocks',
                name: 'Stock',
                component: Stock, 
            },
            {
                path: 'areas',
                name: 'Area',
                component: Area, 
            },
            {
                path: 'sms-marketing',
                name: 'SmsDash',
                component: SmsDash, 
            },
            {
                path: 'sms-list/:id',
                name: 'ViewList',
                component: ViewList, 
            },
            {
                path: 'send-sms/:id',
                name: 'SendSMS',
                component: SendSMS, 
            },
            {
                path: 'setting',
                name: 'Setting',
                component: Setting, 
            },
        ]
    }
]

const router = createRouter({
    history: createWebHashHistory(),
    routes,
})

export default router

<template>
    <main>
        <nav class="bg-success text-light navbar-dark navbar-success navbar-sticky sticky-top shadow">
            <div class="px-md-4 py-2">
                <div class="row mx-0">
                    <div class="col-md-3 col-6 order-1 order-md-1">
                        <i @click="showside" class="fa fa-bars fs-5 me-2"></i>
                        <a href="/" class="text-decoration-none fs-5 text-white">Sodaikhana</a>
                    </div>
                    <div class="col-md-6 order-3 order-md-2 mt-2 mt-md-0">
                        <div class="w-100 bg-white">
                            <div class="input-group">
                                <input type="text" v-model="search" @keyup="searchProduct" class="form-control border-0 rounded-0" placeholder="Type product name">
                                <div class="input-group-append">
                                    <button class="btn btn-light border-0 rounded-0"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 order-2 order-md-3">
                        <div class="w-100 d-flex align-items-center justify-content-end">
                            <div @click="cartshow = true">
                                <i class="fa fa-shopping-cart fs-5"></i>
                                <span class="ms-1 me-2 fs-6">Cart({{cart_no}})</span>
                            </div>
                            <router-link v-if="!auth" to="/login" class="btn btn-light btn-sm text-success">Login</router-link>
                            <router-link v-else to="/user/profile" class="btn btn-light btn-sm text-success">Profile</router-link>
                        </div>
                    </div>
                </div>
            </div>
        </nav>


            <div class="wrapper-body">
                <div v-bind:class="{'sidebar': sidemenu, 'side-phone': !sidemenu}">
                    <div class="sidebar-content pt-1">
                        <Sidebar />
                    </div>
                </div>
                <div v-bind:class="{'content': sidemenu, 'content-phone': !sidemenu}">
                    <MainContent v-if="searchProducts.length === 0" v-on:updateCart="updateCart" />
                    <div v-else>
                        <div class="popular-section text-center py-5">
                            <h2 class="text-success fw-bold fs-4">Search Result</h2>
                            <div class="container pt-5">
                                <div class="row">
                                <div v-for="popular in searchProducts" :key="popular.id" class="col-md-3 col-sm-4 col-6 p-3 text-center border">
                                    <SingleProduct :product="popular" v-on:updateCart="updateCart" />
                                </div>    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div class="row position-relative">
                <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light" v-bind:class="{'side-show-mobile': sidemenu, 'sidebar collapse': !sidemenu}">
                    <div class="fixed-sidebar-left pt-3">
                        <Sidebar />
                    </div>
                </nav>

                

                <main role="main" class="col-md-9 ml-sm-auto col-lg-10 bg-white main-box">
                    
                    
                </main>
            </div> -->
    </main>

    <!-- modal start -->
    <div v-if="cartshow" class="modal shadow" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title text-light fw-bold">Shopping Cart</h5>
                    <button type="button" class="close close-btn btn-light">
                    <span v-on:click="destroyModal" aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <ul class="list-group">
                        <li v-for="cart in carts" :key="cart.id" class="list-group-item">
                            <div class="row">
                                <div class="col-7">
                                    <img :src="cart.image" alt="cart image" class="cart-image">
                                    <p class="m-0">{{cart.title}}-{{cart.atrri}}</p>
                                </div>
                                <div class="col-3 text-center">
                                    
                                    <b class="text-dark font-weight-bold">{{cart.price}}</b>
                                    
                                    <div class="d-flex jsutify-content-between mt-3">
                                        <div class="border px-1" @click="decCart(cart.id)">
                                            <i class="fa fa-minus"></i>
                                        </div>
                                        <div class="border px-2">
                                            <h6 class="m-0">{{cart.qty}}</h6>
                                        </div>
                                        <div class="border px-1" @click="incCart(cart.id)">
                                            <i class="fa fa-plus"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-2 cart-control">
                                    <b class="font-weight-bold">{{cart.price * cart.qty}}</b> 
                                    
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button @click="$router.push({path: '/user/checkout'})" type="button" v-on:click="getCartItem" class="btn btn-success">Checkout</button>
                </div>
            </div>
        </div>
    </div>

    <div v-if="cartshow" class="modal-backdrop fade show"></div>

    <!-- modal end -->
</template>

<script>
import Sidebar from '@/Main/Page/Front/Sidebar.vue'
import MainContent from '@/Main/Page/Front/MainContent.vue'
  import SingleProduct from '@/Main/Page/Front/SingleProduct.vue'

export default {
    components: {
        Sidebar,
        MainContent,
        SingleProduct
    },
    data(){
        return{
            search: '',
            auth: false,
            sidemenu: true,
            searchProducts: [],
            carts: [],
            cart_no: 0,
            cartshow: false,
            config: {},
            name: localStorage.getItem('name'),
        }
    },

    beforeCreate() {
        const token = localStorage.getItem('info')

        if(!token){
            return this.auth = false
        }else{
            this.config = {
                headers: {
                    'Content-Type' : 'application/json',
                    'Accept' : 'application/json',
                    'Authorization' : 'Bearer '+token
                }
            }

            axios.get('/api/profile', this.config)
                .then(res => {
                    const role = localStorage.getItem('phone')
                    if(res.data.phone != role){
                        this.auth = false
                    }else{
                        this.auth = true
                    }
                })
                .catch(err => {
                    if(err.response.status == 401){
                        this.auth = false
                    }
                })
        }
    },

    mounted() {
        this.updateCart()
        
    },

    methods: {
        showside(){
            this.sidemenu = !this.sidemenu
            // console.log(this.sidemenu)
        },

        searchProduct(){
            if(this.search.length > 2){
                axios.get(`/api/product/search/${this.search}`)
                .then(res => {
                    
                    this.searchProducts = res.data.data
                })
                .catch(err => console.log(err))
            }
        },

        updateCart(){
            this.carts = localStorage.getItem('carts')
            this.carts = JSON.parse(this.carts)
            
            this.cart_no = 0
            if(this.carts != null){
                this.carts.forEach(c => {
                    this.cart_no += c.qty
                })
            }
            
        },

        getCartItem(){
            this.carts = localStorage.getItem('carts')
            this.carts = JSON.parse(this.carts)
            this.cartshow = true
        },

        incCart(id){
            this.carts = localStorage.getItem('carts')
            this.carts = JSON.parse(this.carts)

            let activecart = this.carts.find(cart => cart.id == id)
            activecart.qty += 1

            let withoutactivecart = this.carts.filter(cart => cart.id != id)
            withoutactivecart.push(activecart)
            localStorage.setItem('carts', JSON.stringify(withoutactivecart))
            this.updateCart()
        },

        decCart(id){
            this.carts = localStorage.getItem('carts')
            this.carts = JSON.parse(this.carts)

            let activecart = this.carts.find(cart => cart.id == id)
            activecart.qty -= 1
            
            let withoutactivecart = this.carts.filter(cart => cart.id != id)

            if(activecart.qty > 0){
                withoutactivecart.push(activecart)
            }

            localStorage.setItem('carts', JSON.stringify(withoutactivecart))

            this.updateCart()
        },

        destroyModal(){
            this.cartshow = false
        },

        logout(){
            const token = localStorage.getItem('info')
            this.config = {
                headers: {
                    'Content-Type' : 'application/json',
                    'Accept' : 'application/json',
                    'Authorization' : 'Bearer '+token
                }
            }
            axios.post('http://localhost:8000/api/logout', {}, this.config)
            .then(res => {
                if(res.data.message){
                    this.$toasted.success("You are logged out", {
                        duration: 3000,
                    },)
                }
                this.$router.push({path: '/'})
                this.auth = false
            })
            .catch(err => console.log(err))
        },
    }
}
</script>

<style>
    .product-image{
        height: 150px;
        width: 150px;
    }

    .wrapper-body{
        height: calc(100vh - 52px);
        background-color: #eee;
        width: 100w;
        display: flex;
        flex-wrap: nowrap;
    }

    .sidebar{
        width: 225px;
        background-color: #ddd;
        height: 100%;
        overflow-y: scroll;
        border-right: 1px solid #888;
        padding-right: 0px;
    }

    .sidebar::-webkit-scrollbar {
        width: 3px;
    }

    /* Track */
    .sidebar::-webkit-scrollbar-track {
    background: #f1f1f1;
    }

    /* Handle */
    .sidebar::-webkit-scrollbar-thumb {
    background: #888;
    }

    /* Handle on hover */
    .sidebar::-webkit-scrollbar-thumb:hover {
    background: #555;
    }

    .content{
        width: calc(100vw - 225px);
        height: 100%;
        overflow: auto;
    }

    .side-phone{
        width: 225px;
        height: 100%;
        overflow: auto;
    }

    .content-phone{
        width: calc(100vw - 225px);
        height: 100%;
        overflow: auto;
    }

    .cart-image{
        width: 40px;
        height: 40px;
    }

    @media (max-width: 690px){
        .product-image{
        height: 120px;
        width: 120px;
        } 
        
        .wrapper-body{
            height: calc(100vh - 90px);
            background-color: #eee;
            width: 100w;
            display: flex;
            flex-wrap: nowrap;
        }

        .sidebar{
            width: 0px;
        }

        .content{
            width: 100vw;
        }

        .side-phone{
            width: 225px;
            height: 100%;
            overflow: auto;
        }

        .content-phone{
            width: calc(100vw - 225px);
            height: 100%;
            overflow: auto;
        }

        .cart-image{
            width: 25px;
            height: 25px;
        }
    }
</style>


        
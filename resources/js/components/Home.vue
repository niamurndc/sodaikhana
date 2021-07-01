<template>
    <div class="page">
        <nav class="menu text-light custome-nav px-2 px-md-4 py-2 py-md-3 sticky-top shadow">
            <div class="row">
                <div class="col-md-3 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <i @click="showside" class="fa fa-bars mr-3 mb-2"></i>
                        <a href="/" class="brand-title">Sodaikhana</a>
                    </div>
                    <div class="d-flex align-items-center d-md-none">
                        <p v-on:click="getCartItem" class="mb-0 position-relative"><span class="badge badge-pill badge-custom position-absolute ml-2">{{cart_no}}</span><i class="fa fa-shopping-cart mr-3 mb-2"></i></p>
                        <i v-if="auth" @click="$router.push({path: '/user/profile'})" class="fa fa-user-circle-o mb-2 d-md-none"></i>
                        <i v-else @click="$router.push({path: '/login'})" class="fa fa-user-circle-o mb-2 d-md-none ml-4"></i>
                        <i v-if="auth" @click="logout" class="fa fa-sign-out ml-4 mb-2 d-md-none"></i>
                    </div>
                </div>

                <div class="col-md-6">
                    <form @submit.prevent="searchProduct" class="mt-2 mt-md-0">
                        <div class="input-group">
                            <input type="text" v-model="search" class="form-control" required>
                            <div class="input-group-append">
                                <button class="btn btn-custom" type="submit"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="col-md-3 d-none d-md-flex align-items-center justify-content-end">
                    <p v-on:click="getCartItem" class="mb-0 position-relative"><span class="badge badge-pill badge-custom position-absolute ml-2">{{cart_no}}</span><i class="fa fa-shopping-cart mr-3 mb-2"></i></p>
                    <!-- pc -->
                    <router-link v-if="auth" to="/user/profile" class="text-white d-none d-md-inline">{{name}}</router-link>
                    <a v-if="auth" @click="logout" href="#" class="btn btn-custom ml-3 d-none d-md-block">Logout</a>
                    <a v-else @click="$router.push({path: '/login'})" href="#" class="btn btn-custom ml-3 d-none d-md-block">Login</a>
                    
                </div>
            </div>        
        </nav>

        <!-- modal start -->
        <div v-if="cartshow" class="modal" aria-hidden="true" tabindex="-1">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header bg-green">
                        <h5 class="modal-title">Shopping Cart</h5>
                        <button type="button" class="close">
                        <span v-on:click="destroyModal" aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <ul class="list-group">
                            <li v-for="cart in carts" :key="cart.id" class="list-group-item">
                                <div class="row">
                                    <div class="col-md-2 col-0">
                                        <img :src="cart.image" alt="cart image" height="40px" width="40px" class="d-none d-md-inline">
                                    </div>
                                    <div class="col-md-8 col-10">
                                        <p class="m-0">{{cart.title}}-{{cart.atrri}}</p>
                                        <span class="text-green bold">{{cart.price}} X {{cart.qty}} = {{cart.price * cart.qty}} </span>
                                    </div>
                                    <div class="col-md-2 col-2 cart-control">
                                        <i @click="incCart(cart.id)" class="fa fa-chevron-up"></i>
                                        <h6 class="m-0">{{cart.qty}}</h6>
                                        <i @click="decCart(cart.id)" class="fa fa-chevron-down"></i>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="modal-footer">
                        <button @click="$router.push({path: '/user/checkout'})" type="button" v-on:click="getCartItem" class="btn btn-add-cart">Checkout</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- modal end -->

        <div class="container-fluid">
            <div class="row position-relative">
                <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light" v-bind:class="{'side-show-mobile': sidemenu, 'sidebar collapse': !sidemenu}">
                    <div class="sidebar-sticky pt-3">
                        <Sidebar />
                    </div>
                </nav>

                <main role="main" class="col-md-9 ml-sm-auto col-lg-10 bg-white">
                    <MainContent v-on:updateCart="updateCart"/>
                </main>
            </div>
        </div>
    </div>
</template>

<script>
    import MainContent from './home/MainContent.vue'
    import Sidebar from './home/Sidebar.vue'
    export default {
        components: {
            MainContent,
            Sidebar
        },
        data(){
            return{
                search: '',
                carts: [],
                cart_no: 0,
                cartshow: false,
                auth: false,
                config: {},
                name: localStorage.getItem('name'),
                sidemenu: false,
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
            updateCart(){
                this.carts = localStorage.getItem('carts')
                this.carts = JSON.parse(this.carts)
                
                this.cart_no = 0
                this.carts.forEach(c => {
                    this.cart_no += c.qty
                })
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
                axios.post('/api/logout', {}, this.config)
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

            searchProduct(){
                this.$router.push({path: `/search/${this.search}`})
            },

            showside(){
                this.sidemenu = !this.sidemenu
                console.log(this.sidemenu)
            }
        }
    }
</script>

<style>
    .page{
        font-family: 'Poppins', sans-serif;
    }

    .text-green{
        color: #1E6F5C;
    }

    .bg-green{
        background-color: #1E6F5C;
        color: #FFF;
    }

    .custome-nav{
        background: #1E6F5C;
        color: #fff;
    }

    .custome-nav i{
        color: #fff;
        font-size: 24px;
        cursor: pointer;
    }

    .brand-title{
        color: #fff;
        text-decoration: none;
        font-size: 20px;
    }

    .brand-title:hover{
        color: #E6DD3B;
        text-decoration: none;
        font-size: 20px;
    }

    .btn-custom{
        background-color: #fff;
        color: #1E6F5C;
        border: 1px solid #1E6F5C;
    }

    .btn-custom i{
        color: #1E6F5C;
        margin-bottom: 7px;
    }

    .btn-add-cart{
        border: 1px solid #1E6F5C;
        color: #FFFFFF;
        background-color: #1E6F5C;
    }

    .btn-add-cart:hover{
        border: 1px solid #1E6F5C;
        color: #1E6F5C;
        background-color: #FFFFFF;
    }

    .bg-white{
        background-color: #efefef;
    }

    .product-image{
        height: 150px;
        width: 150px;
    }

    .badge-custom{
        background-color: #E6DD3B;
        color: #1E6F5C;
    }

    .cart-control i{
        cursor: pointer;
    }



    @media (max-width: 690px){
        .product-image{
        height: 120px;
        width: 120px;
        } 
        
        .side-show-mobile{
            position: absolute !important;
            left: 0;
            right: 0;
            max-width: 250px;
            z-index: 5000;
        }

    }
</style>

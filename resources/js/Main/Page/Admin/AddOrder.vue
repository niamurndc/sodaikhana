<template>
  <main>
    <div class="bg-primary text-white position-sticky sticky-top">
        <div class="container py-3 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <router-link to="/admin" class="text-white me-2"><i class="fa fa-arrow-left"></i></router-link>
                <h4 class="mb-0 pb-0">Create Order</h4>
            </div>
            <button class="btn btn-warning text-dark" @click="createOrder">Save</button>
        </div>
    </div>

    <div class="container">
        <div class="py-2">

          <div class="form-group">
            <label for="name">Customer Name</label>
            <input type="text" v-model="name" id="name" class="form-control">
          </div>

          <div class="form-group">
            <label for="phone">Customer Phone</label>
            <input type="text" v-model="phone" id="phone" class="form-control">
          </div>

          <div class="form-group">
            <label for="area">Customer Area</label>
            <input type="text" v-model="area" id="area" class="form-control">
          </div>

          <div class="form-group">
            <label for="address">Customer Address</label>
            <input type="text" v-model="address" id="address" class="form-control">
          </div>

          <h6 class="text-primary mt-4 fw-bold">Products</h6>
          <div class="position-relative">
            <input type="text" v-model="search" placeholder="Type product name" class="form-control" @keyup="searchProduct">

            <div v-if="products != []" class="position-relative bottom-0 left-0 w-100">
                <div v-for="product in products" :key="product.id" class="border-bottom p-1"><div @click="addProduct(product)">{{product.title}} -- {{product.price}}</div></div>
            </div>
          </div>

          <div class="table-responsiv mt-4">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Attribute</th>
                        <th>Price</th>
                        <th>QTY</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="cart in carts" :key="cart.id">
                        <td>{{cart.title}}</td>
                        <td>{{cart.attri}}</td>
                        <td>{{cart.price}} </td>
                        <td><div @click="showEdit(cart.id, cart.quantity)">{{cart.quantity}}</div> <input type="number" style="width: 50px" v-model="qty" v-if="cart.id == editId"></td>
                    </tr>

                    <tr>
                        <td colspan="2">Shipping Cost</td>
                        <td>{{ ship }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="2">Total</td>
                        <td>{{total + ship}}</td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
          </div>

          



          

        </div>
    </div>
  </main>
</template>

<script>
import { v4 as uuidv4 } from 'uuid'
  export default {
    data(){
      return{
        carts: [],
        products: [],
        name: '',
        phone: '',
        area: '',
        address: '',
        search: '',
        total: 0,
        editqty: false,
        editId: '',
        qty: 0,
        ship: 30,
      }
    },

    mounted() {
      this.getSetting()
    },


    methods: {
        searchProduct(){
            if(this.search.length > 2){
                axios.get(`/api/product/search/${this.search}`)
                    .then(res => {
                        this.products = res.data.data
                    })
                    .catch(err => console.log(err))
            }
        },

        getSetting(){
          axios.get(`/api/settings`)
                .then(data => {
                  this.ship = data.data.data.ship
                })
                .catch(err => console.log(err))
        },

        addProduct(product){
            let cart = {
                id: uuidv4(),
                title: product.title,
                price: product.price,
                product_id: product.id,
                attri: product.attri,
                quantity: 1
            }

            this.total += cart.quantity * cart.price;

            this.carts.push(cart);
            this.search = ''
            this.products = []
            console.log(this.carts, this.total)
        },

        showEdit(id, qty){
          if(this.editqty){
            this.editqty = false
            this.total = 0;

            this.carts.forEach((cart) => {
              if(cart.id == id){
                cart.quantity = this.qty
              }

              this.total += cart.quantity * cart.price
            })
            this.editId = ''
            this.qty = 0
          }else{
            this.editqty = true
            this.editId = id
            this.qty = qty
          }
          
        },

        createOrder(){
          const token = localStorage.getItem('info')

          this.config = {
            headers: {
              'Content-Type' : 'application/json',
              'Accept' : 'application/json',
              'Authorization' : 'Bearer '+token
            }
          }

          axios.post('/api/order/add', {name: this.name, phone: this.phone, address: this.address, area: this.area, subtotal: this.total, carts: this.carts}, this.config)
            .then(res => {
              this.$router.push({path: '/admin'})
            })
            .catch(err => console.log(err))
        }
    }
  }
</script>

<style>
  .modal{
    display: block;
  }
</style>

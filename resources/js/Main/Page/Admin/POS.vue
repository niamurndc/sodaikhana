<template>
    <main>
      <div class="bg-primary text-white position-sticky sticky-top">
          <div class="px-4 py-3 d-flex justify-content-between align-items-center">
              <div class="d-flex align-items-center">
                  
                  <h4 class="mb-0 pb-0">Sodaikhana POS</h4>
              </div>
              <div>
                <router-link to="/admin/daily-sale" class="btn btn-light btn-sm text-dark me-2"><i class="fa fa-list"></i> Daily Sale</router-link>
                <router-link to="/admin" class="btn btn-warning btn-sm text-dark"><i class="fa fa-arrow-left"></i> Back</router-link>
              </div>
              
          </div>
      </div>
  
      <div class="px-4">
          <div class="py-2">
            <div class="row">
                <div class="form-group col-md-2">
                    <label for="phone">Customer Phone</label>
                    <input type="text" v-model="phone" @keyup.enter="searchCustomer" id="phone" class="form-control">
                </div>

                <div class="form-group col-md-2">
                    <label for="name">Customer Name</label>
                    <input type="text" v-model="name" id="name" class="form-control">
                </div>

                <div class="form-group col-md-2">
                    <label for="name">Area</label>
                    <select v-model="area" id="name" class="form-control">
                        <option v-for="area in allareas" :value="area.title">{{ area.title }}</option>
                    </select>
                </div>

                <div class="form-group col-md-1">
                    <label for="balance">Balance</label>
                    <input type="text" v-model="balance" id="balance" class="form-control">
                </div>


                <div class="form-group col-md-3">
                    <label for="name">Address</label>
                    <input type="text" v-model="address" id="name" class="form-control">
                </div>

                <div class="col-md-2">
                    <button v-if="new_cus" class="btn btn-dark mt-4 w-100" @click="addCustomer">Save Customer</button>
                    <button v-else-if="upd_cus" class="btn btn-dark mt-4 w-100" @click="updateCustomer">Update Customer</button>
                    <button v-else class="btn btn-dark mt-4 w-100" disabled>Save Customer</button>
                </div>


                <div class="col-md-3">
                    <b v-if="error != ''" class="text-danger">{{ error }}</b>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <h4 class="text-primary mt-4 fw-bold">Products</h4>
                    <div class="position-relative">
                        <input type="text" v-model="search" placeholder="Type product name" class="form-control" @keyup="searchProduct" ref="barcode">
            
                        <div v-if="products != [] || product_variation" class="position-relative bottom-0 left-0 w-100">
                            <div v-for="product in products" :key="product.id" class="border-bottom p-1"><div @click="addProduct(product)">{{product.title}} -- {{product.price}}</div></div>
                        </div>
                        <div v-if="product_variation" class="position-relative bottom-0 left-0 w-100">
                            <h6 class="text-primary">Variation: {{ product.title }}</h6>
                            <div v-for="vari in variation_list" class="border-bottom p-1"><div @click="addCart(product, vari.attri, vari.value)">{{vari.attri}} -- {{parseInt(product.price) * parseInt(vari.value) / 100}}</div></div>
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
                                    <th>Del.</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="cart in carts" :key="cart.id">
                                    <td>{{cart.title}}</td>
                                    <td>{{cart.attri}}</td>
                                    <td>{{cart.price}} </td>
                                    <td><div @click="showEdit(cart.id, cart.quantity)">{{cart.quantity}}</div> <input type="number" step=".01" style="width: 50px" v-model="qty" v-if="cart.id == editId" v-on:keyup.enter="showEdit(cart.id, cart.quantity)"></td>
                                    <td><span class="btn btn-danger" @click="deleteCart(cart.id)"><i class="fa fa-times"></i></span></td>
                                </tr>
                                <tr>
                                    <td colspan="2">Total</td>
                                    <td>{{total}}</td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-4">
                    <h4 class="text-primary mt-4 fw-bold">Summary</h4>
                    <div class="bg-dark p-4">
                        <div class="d-flex justify-content-between align-items-center text-light">
                            <h6>Total</h6>
                            <h6>{{ total }} Tk</h6>
                        </div>
                        <div class="d-flex justify-content-between align-items-center text-light">
                            <h6><input type="checkbox" v-model="shipping_cost" @change="shipChange"> Shipping cost</h6>
                            <h6>{{ ship }} Tk</h6>
                        </div>
                        <div class="d-flex justify-content-between align-items-center w-100">
                            <h4 class="text-center text-light">Subtotal</h4>
                            <h1 class="text-light">{{ total + ship }} Tk</h1>
                        </div>

                        <div class="form-group mt-4">
                            <label for="discount" class="text-white">Discount</label>
                            <input type="text" value="0" id="discount" class="form-control">
                        </div>

                        <div class="form-group mt-4">
                            <label for="pay" class="text-white">Pay</label>
                            <input type="text" v-model="pay" @keyup.enter="payChange" id="pay" class="form-control">
                        </div>

                        <div class="form-group mt-4">
                            <label for="change" class="text-white">Due</label>
                            <input type="text" v-model="due" id="change" class="form-control">
                        </div>

                        <div class="form-group mt-4">
                            <label for="method" class="text-white">Payment Method</label>
                            <select v-model="method" id="method" class="form-control">
                                <option value="cash">Cash</option>
                                <option value="bkash">Bkash</option>
                                <option value="nagad">Nagad</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <div class="row mx-0 mt-4">
                            <button class="btn btn-success btn-lg col-md-12" @click="createOrder">Save & Print Order</button>
                        </div>
                    </div>

                </div>
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
          new_cus: false,
          upd_cus: false,
          name: '',
          phone: '',
          area: '',
          address: '',
          search: '',
          total: 0,
          pay: 0,
          due: 0,
          method: 'cash',
          editqty: false,
          editId: '',
          qty: 0,
          ship: 30,
          shipping_cost: true,
          product_variation: false,
          product: {},
          variation_list: [],
          allareas: [],
          customer_id: 0,
          balance: 0,

          error: '',
          config: {}
        }
      },
  
      mounted() {
        this.getSetting()
        this.getAreas()
      },
  
  
      methods: {
          payChange(){
            this.due = this.total + this.ship - this.pay;

            if(this.balance - this.pay > 0){
                this.balance = this.balance - this.pay
                this.updateCustomer()
            }else{
                this.balance = 0
                this.updateCustomer()
            }
          },

          shipChange(){
            if(this.shipping_cost == false){
                this.ship = 0;
            }else{
                this.getSetting()
            }
            
          },

          searchCustomer(){
            if(this.phone.length < 11){
                this.error = 'Phone must be 11 digit'
            }else{
                const token = localStorage.getItem('info')

                this.config = {
                    headers: {
                        'Content-Type' : 'application/json',
                        'Accept' : 'application/json',
                        'Authorization' : 'Bearer '+token
                    }
                }

                axios.get(`/api/customer/search/${this.phone}`, this.config)
                    .then(data => {
                        if(data.data.data.length == 0){
                            this.new_cus = true
                            this.error = 'No customer found please add'
                            this.name = ''
                        }else{
                            this.name = data.data.data[0].name
                            this.balance = data.data.data[0].balance == null ? 0 : data.data.data[0].balance
                            this.area = data.data.data[0].area
                            this.address = data.data.data[0].address
                            this.customer_id = data.data.data[0].id
                            this.upd_cus = true
                        }
                        
                    })
                    .catch(err => console.log(err))
            }
          },

          addCustomer(){
            if(this.name == ''){
                this.error = 'Please enter the name'
            }else{

                const token = localStorage.getItem('info')

                this.config = {
                    headers: {
                        'Content-Type' : 'application/json',
                        'Accept' : 'application/json',
                        'Authorization' : 'Bearer '+token
                    }
                }

                axios.post('/api/customer/add', {
                    phone: this.phone, password: '12345678', name: this.name, area: this.area, address: this.address
                },  this.config)
                    .then(data => {
                        this.error = 'Customer saved'
                    })
                    .catch(err => {
                        this.error = err.message
                    })

                
            }
          },

          updateCustomer(){
            if(this.name == '' || this.area == '' || this.address == ''){
                this.error = 'Please fill all field'
            }else{

                const token = localStorage.getItem('info')

                this.config = {
                    headers: {
                        'Content-Type' : 'application/json',
                        'Accept' : 'application/json',
                        'Authorization' : 'Bearer '+token
                    }
                }

                axios.post(`/api/customer/edit/${this.customer_id}`, {
                    phone: this.phone, name: this.name, area: this.area, balance: this.balance, address: this.address, status: 1
                },  this.config)
                    .then(data => {
                        this.error = 'Customer update'
                    })
                    .catch(err => {
                        this.error = err.message
                    })
            }
          },

          getAreas(){
                const token = localStorage.getItem('info')

                this.config = {
                headers: {
                    'Content-Type' : 'application/json',
                    'Accept' : 'application/json',
                    'Authorization' : 'Bearer '+token
                }
                }

                axios.get('/api/areas', this.config)
                    .then(data => {
                        this.allareas = data.data.data
                    })
                    .catch(err => console.log(err))
          },

          searchProduct(){
              if(this.search.length > 2){
                  this.product_variation = false;
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
            if(product.attri1 != null){
                
                this.product = product
                this.variation_list[0] = {
                    attri: product.attri1,
                    value: product.val1,
                }
                this.variation_list[1] = {
                    attri: product.attri2,
                    value: product.val2,
                }
                this.variation_list[2] = {
                    attri: product.attri3,
                    value: product.val3,
                }

                this.search = ''
                this.products = []

                this.product_variation = true

                //this.addCart(product, product.attri)
            }else{
                this.addCart(product, product.attri, 100)
            }
          },

          addCart(product, attri, attri_val){
            let cart = {
                id: uuidv4(),
                title: product.title,
                price: parseInt(product.price) * parseInt(attri_val) / 100,
                product_id: product.id,
                attri: attri,
                quantity: 1
            }

            this.total += cart.quantity * cart.price;

            this.carts.push(cart);
            this.search = ''
            this.products = []
            this.product_variation = false
            this.variation_list = []
            this.$refs.barcode.focus()
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

          deleteCart(id){
            this.carts = this.carts.filter(cart => cart.id != id)
            this.carts.forEach((cart) => {
                this.total = cart.quantity * cart.price
            })
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
  
            axios.post('/api/order/add', {name: this.name, phone: this.phone, address: this.address, area: this.area, subtotal: this.total + this.ship, paid: this.pay, due: this.due, carts: this.carts, terminal: 1}, this.config)
              .then(res => {
                const oid = res.data.data.id
                this.$router.push({path: `/admin/print-order/${oid}`})
              })
              .catch(err => console.log(err))
          }
      }
    }
  </script>
  
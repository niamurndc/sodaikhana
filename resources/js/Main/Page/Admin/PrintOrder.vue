<template>
    <main>
      <div class="bg-primary text-white position-sticky sticky-top">
          <div class="container py-3 d-flex justify-content-between align-items-center">
              <div class="d-flex align-items-center">
                  <router-link to="/admin/orders" class="text-white me-2"><i class="fa fa-arrow-left"></i></router-link>
                  <h4 class="mb-0 pb-0">Order Invoice</h4>
              </div>
              <button class="btn btn-warning text-dark" @click="printme"><i class="fa fa-print"></i> Print</button>
          </div>
      </div>
  
      <div class="container">
          <div class="py-2">
  
            <div class="printable">
                <div class="text-center">
                  <h2 class="text-success">সদাইখানা</h2>
                  <h6 class="mb-0 pb-0">www.sodaikhana.com</h6>
                  <h6 class="mb-0 pb-0">Contacts:01795-351753, 01778-974164 Faceook Page: সদাইখানা</h6>
                  <h6 class="mb-0 pb-0">Bkash,Nagad,Rocket-(Personal Number):01772-404554-6</h6>
                
                </div>
                <table class="table table-sm">
                    <tr>
                        <td>
                            <h4 class="mb-0 pb-0">Invoice</h4>
                            <h6 class="mb-0 pb-0">Order Number: #{{order.id}}</h6>
                            <h6 class="mb-0 pb-0">Date: {{order.created}}</h6>
                            <h6 class="mb-0 pb-0 fw-bold">Customer details:</h6>
                        </td>
                        <td class="text-end">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h6 class="mb-0 pb-0">Name: {{ order.name }}</h6>
                        </td>
                        <td>
                          <h6 class="mb-0 pb-0">Phone: {{ order.phone }}</h6>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <h6 class="mb-0 pb-0">Address: {{ order.address }} ({{ order.area }})</h6>
                        </td>
                    </tr>
                </table>

                <h6 class="mb-0 pb-0 fw-bold">Product details:</h6>
                <table class="table table-bordered table-sm">
                    <tr>
                      <th>Product Name</th>
                      <th>Attribute</th>
                      <th>Rate</th>
                      <th>QTY</th>
                      <th>Price</th>
                    </tr>

                    <tr v-for="cart in order.carts">
                      <td>{{ cart.product.title }}</td>
                      <td>{{ cart.attri }}</td>
                      <td>{{ cart.price }}</td>
                      <td>{{ cart.quantity }}</td>
                      <td>{{ parseFloat(cart.quantity) * parseFloat(cart.price) }}</td>
                    </tr>

                    <tr>
                      <td colspan="4">Total</td>
                      <td>{{ total }}</td>
                    </tr>
                </table>

                <table class="table table-sm">
                    <tr>
                      <td>
                        <h6 class="mb-0 pb-0 fw-bold">Billing details:</h6>
                        <h6 class="mb-0 pb-0">Total: {{ total }} BDT</h6>
                        <h6 class="mb-0 pb-0">Shipping cost: {{ ship }} BDT</h6>
                        <h6 class="mb-0 pb-0">Subtotal: {{ order.subtotal }} BDT</h6>
                        <h6 class="mb-0 pb-0">Advance: {{ order.paid }} BDT</h6>
                        <h6 class="mb-0 pb-0">Due: {{ order.due }} BDT</h6>
                      </td>
                      <td>
                        <h6 class="text-success">Check the product before receiving</h6>
                      </td>
                    </tr>
                    <tr>
                      <td class="pt-4">
                        <h6 class="mb-1 pb-0 fw-bold">Rider Token:</h6>
                        <h6 class="mb-0 pb-0">Order Number: #{{ order.id }}</h6>
                        <h6 class="mb-0 pb-0">Pay to Rider: {{ order.due }} BDT</h6>
                        <h6 class="mb-0 pb-0">Payment Method: Cash/Bkash/Nagad</h6>
                      </td>
                      <td class="text-center pt-4">
                        <h6>Customer Signature</h6>
                      </td>
                    </tr>
                </table>
            </div>
          </div>
      </div>
    </main>
  </template>
  
  <script>
    export default {
      data(){
        return{
          order: {},
          ship: 30,
          total: 0,
          config: {},
          order_id: this.$route.params.id,
        }
      },
      mounted() {
        this.getOrder()
        this.getSetting()
      },
  
      methods: {
        getOrder(){
          const token = localStorage.getItem('info')
  
          this.config = {
            headers: {
              'Content-Type' : 'application/json',
              'Accept' : 'application/json',
              'Authorization' : 'Bearer '+token
            }
          }
  
          axios.get(`/api/order/${this.order_id}`, this.config)
                .then(data => {
                  this.order = data.data.data
                  
                  this.order.carts.forEach(cart => {
                    this.total += parseFloat(cart.quantity) * parseFloat(cart.price)
                  })
                })
                .catch(err => console.log(err))
        },
  
        getSetting(){
          axios.get(`/api/settings`)
                .then(data => {
                  this.ship = data.data.data.ship
                })
                .catch(err => console.log(err))
        },
  
        
        
  
        printme(){
          window.print();
        }
      }
    }
  </script>
  
  <style>
    @media print {
      body * {
        visibility: hidden;
      }
      .printable, .printable * {
        visibility: visible;
      }
      .printable {
        position: absolute;
        left: 0;
        top: 0;
        width: 93%;
        margin: 0 auto;
      }
    }
  </style>
  
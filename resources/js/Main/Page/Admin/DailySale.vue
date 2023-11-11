<template>
    <main>
      <div class="bg-primary text-white position-sticky sticky-top">
          <div class="px-4 py-3 d-flex justify-content-between align-items-center">
              <div class="d-flex align-items-center">
                  
                  <h4 class="mb-0 pb-0">Sodaikhana POS</h4>
              </div>
              <div>
                <router-link to="/admin/pos" class="btn btn-light btn-sm text-dark me-3"><i class="fa fa-shopping-cart"></i> POS</router-link>
                <router-link to="/admin" class="btn btn-warning btn-sm text-dark"><i class="fa fa-arrow-left"></i> Back</router-link>
              </div>
              
          </div>
      </div>
  
      <div class="px-4">
          <div class="py-2">
            <div class="row">
                <div class="form-group col-md-2">
                    <label for="from">From Date</label>
                    <input type="date" v-model="from" @keyup.enter="searchCustomer" id="from" class="form-control">
                </div>

                <div class="form-group col-md-2">
                    <label for="to">To Date</label>
                    <input type="date" v-model="to" id="to" class="form-control">
                </div>

                <div class="form-group col-md-2">
                    <button class="btn btn-success mt-4" @click="getOrders">Search</button>
                </div>


                <div class="col-md-6 d-flex align-items-center">
                    <router-link to="/admin/product-sale" class="btn btn-dark text-light me-3"><i class="fa fa-list"></i> Product Wise Sale</router-link>
                    <router-link to="/admin/profit-loss" class="btn btn-dark text-light me-3"><i class="fa fa-sliders"></i> Profit Loss</router-link>
                    <router-link to="/admin/expance" class="btn btn-dark text-light me-3"><i class="fa fa-money"></i> Expance</router-link>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <h4 class="text-primary mt-4 fw-bold">Daily Sales</h4>


                    <div class="table-responsiv mt-4">
                        <table class="table table-bordered table-sm">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Date</th>
                                    <th>Csutomer</th>
                                    <th>Phone</th>
                                    <th>Area</th>
                                    <th>Total</th>
                                    <th>Paid</th>
                                    <th>Due</th>
                                    <th>P/M</th>
                                    <th>Pay</th>
                                    <th>Print</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="order in orders" :key="order.id">
                                    <td>{{order.id}}</td>
                                    <td>{{order.created}}</td>
                                    <td>{{order.name}}</td>
                                    <td>{{order.phone}}</td>
                                    <td>{{order.area}}</td>
                                    <td>{{order.subtotal}}</td>
                                    <td>{{order.paid}}</td>
                                    <td>{{order.due}}</td>
                                    <td>{{order.method}}</td>
                                    <td><span class="btn btn-info btn-sm" @click="payOrder(order)"><i class="fa fa-money"></i></span></td>
                                    <td><router-link :to="`/admin/print-order/${order.id}`" class="btn btn-success btn-sm"><i class="fa fa-print"></i></router-link></td>
                                </tr>
                                <tr>
                                    <td colspan="5">Total</td>
                                    <td>{{total}}</td>
                                    <td>{{advance}}</td>
                                    <td>{{totaldue}}</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                    
                            </tbody>
                        </table>
                        </div>
                </div>
            </div>
  
          </div>
      </div>


      <div v-if="modal" class="modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Order Edit</h5>
                <button type="button" class="btn-close" @click="modalClose" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <tr>
                        <td>Order Total</td>
                        <td><b>{{ total }}</b></td>
                    </tr>
                    <tr>
                        <td>Paid amount</td>
                        <td><b>{{ paid }}</b></td>
                    </tr>
                    <tr>
                        <td>Order Due</td>
                        <td><b>{{ due }}</b></td>
                    </tr>
                </table>
                <div v-if="msg != ''" class="alert alert-danger">
                    <b>{{ msg }}</b>
                </div>

                <div class="form-group mt-4">
                    <label for="status" class="text-white">Order Status</label>
                    <select v-model="status" class="form-select">
                        <option value="0">Processing</option>
                        <option value="1">Collecting Product</option>
                        <option value="2">Out for delivery</option>
                        <option value="3">Delivered</option>
                        <option value="4">Canceled</option>
                    </select>
                </div>

                

                <div class="form-group mt-4">
                    <label for="change" class="text-dark">Due</label>
                    <input type="text" v-model="due" id="change" class="form-control">
                </div>

                <div class="form-group mt-4">
                    <label for="pay" class="text-dark">Due Recived Amount</label>
                    <input type="text" v-model="duepaid" @keyup.enter="payChange" id="pay" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" @click="modalClose" >Close</button>
                <button type="button" class="btn btn-primary" @click="orderUpdate">Save changes</button>
            </div>
            </div>
        </div>
      </div>
    </main>
  </template>
  
  <script>
    export default {
      data(){
        return{
          orders: [],
          
          from: '',
          to: '',
          config: {},
          total: 0,
          advance: 0,
          totaldue: 0,


          modal: false,
          order_id: 0,
          status: 0,
          due: 0,
          paid: 0,
          total: 0,
          duepaid: 0,
          msg: '',
        }
      },
  
      mounted() {
        this.getOrders()
      },
  
  
      methods: {
          payChange(){
            if(this.duepaid > this.due){
                this.msg = 'Amount is large'
            }else{
                this.due = this.due - this.duepaid;
            }
            
          },

          getOrders(){
                const token = localStorage.getItem('info')

                this.config = {
                headers: {
                    'Content-Type' : 'application/json',
                    'Accept' : 'application/json',
                    'Authorization' : 'Bearer '+token
                }
                }
                let order_link = ''
                if(this.from == '' || this.to == ''){
                    order_link = `/api/daily-sale`
                }else{
                    order_link = `/api/daily-sale?from=${this.from}&to=${this.to}`
                }

                axios.get(order_link, this.config)
                    .then(data => {
                        this.orders = data.data.data

                        this.orders.forEach(order => {
                            this.total += order.subtotal
                            this.advance += parseInt(order.paid)
                            this.totaldue += parseInt(order.due)
                        })
                    })
                    .catch(err => console.log(err))
          },

          payOrder(order){
            this.modal = true
            this.order_id = order.id
            this.total = order.subtotal
            this.due = order.due
            this.paid = order.paid
            this.status = order.status
          },

          modalClose(){
            this.modal = false
          },

          orderUpdate(){
                const token = localStorage.getItem('info')

                this.config = {
                headers: {
                    'Content-Type' : 'application/json',
                    'Accept' : 'application/json',
                    'Authorization' : 'Bearer '+token
                }
                }

                axios.post(`/api/order/pay/${this.order_id}`, {status: this.status, paid: this.duepaid}, this.config)
                    .then(data => {
                        this.getOrders()
                    })
                    .catch(err => console.log(err))
            },
          
      }
    }
  </script>
  
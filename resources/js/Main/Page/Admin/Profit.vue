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

                <div class="col-md-6 d-flex align-items-center">
                    <router-link to="/admin/daily-sale" class="btn btn-dark text-light me-3"><i class="fa fa-list"></i> Daily Sale</router-link>
                    <router-link to="/admin/product-sale" class="btn btn-dark text-light me-3"><i class="fa fa-sliders"></i> Product Wise Sale</router-link>
                    <router-link to="/admin/expance" class="btn btn-dark text-light me-3"><i class="fa fa-money"></i> Expance</router-link>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <h4 class="text-primary mt-4 fw-bold">Profit Loss</h4>


                    <div class="table-responsiv mt-4">
                        <table class="table table-bordered">
                            <tr>
                                <td><h5>Total Order</h5></td>
                                <td><h5>{{ order_count }}</h5></td>
                            </tr>
                            <tr>
                                <td><h5>Total Order Amount</h5></td>
                                <td><h5>{{ order_total }}</h5></td>
                            </tr>
                            <tr>
                                <td><h5>Total Order Paid</h5></td>
                                <td><h5>{{ order_paid}}</h5></td>
                            </tr>
                            <tr>
                                <td><h5>Total Order Due</h5></td>
                                <td><h5>{{ order_due}}</h5></td>
                            </tr>
                            <tr>
                                <td><h5>Total Expance</h5></td>
                                <td><h5>{{ expance }}</h5></td>
                            </tr>
                            <tr>
                                <td><h5><b>Cash in hand</b></h5></td>
                                <td><h5>{{ order_paid - expance }}</h5></td>
                            </tr>
                        </table>
                    </div>


                    <div v-if="msg != ''" class="alert alert-danger">
                        <b>{{ msg }}</b>
                    </div>
                    <button type="button" @click="closeCash" class="btn btn-success">Close Cash</button>
                </div>

                <div class="col-md-6">
                    <h4 class="text-primary mt-4 mb-4 fw-bold">Cash Report</h4>
                    <table class="table table-bordered">
                        <tr>
                            <td>Date</td>
                            <td>Orders</td>
                            <td>Total Recived</td>
                            <td>Total Expance</td>
                            <td>Total Cash</td>
                        </tr>
                        <tr v-for="report in reports">
                            <td>{{ report.created_at }}</td>
                            <td>{{report.orders}}</td>
                            <td>{{report.paid}}</td>
                            <td>{{report.expance}}</td>
                            <td>{{ report.cash }}</td>
                        </tr>
                    </table>
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
          reports: [],
          config: {},
          order_count: 0,
          order_total: 0,
          order_paid: 0,
          order_due: 0,
          expance: 0,
          msg: '',
        }
      },
  
      mounted() {
        this.getOrders()
        this.getReports()
      },
  
  
      methods: {
          getOrders(){
                const token = localStorage.getItem('info')

                this.config = {
                headers: {
                    'Content-Type' : 'application/json',
                    'Accept' : 'application/json',
                    'Authorization' : 'Bearer '+token
                }
                }

                axios.get(`/api/profit-loss`, this.config)
                    .then(data => {
                        this.order_count = data.data.order_count
                        this.order_total = data.data.order_total
                        this.order_paid = data.data.order_paid
                        this.order_due= data.data.order_due
                        this.expance = data.data.expance
                    })
                    .catch(err => console.log(err))
          },

          getReports(){
                const token = localStorage.getItem('info')

                this.config = {
                headers: {
                    'Content-Type' : 'application/json',
                    'Accept' : 'application/json',
                    'Authorization' : 'Bearer '+token
                }
                }

                axios.get(`/api/daily-report`, this.config)
                    .then(data => {
                        console.log(data);
                        this.reports = data.data
                    })
                    .catch(err => console.log(err))
          },

          closeCash(){
                const token = localStorage.getItem('info')

                this.config = {
                headers: {
                    'Content-Type' : 'application/json',
                    'Accept' : 'application/json',
                    'Authorization' : 'Bearer '+token
                }
                }

                axios.post(`/api/save-report`, {orders: this.order_count, total: this.order_total, paid: this.order_paid, due: this.order_due, expance: this.expance, cash: this.order_paid - this.expance}, this.config)
                    .then(data => {
                        this.msg = 'Cash updated';
                    })
                    .catch(err => console.log(err))
          },


          
      }
    }
  </script>
  
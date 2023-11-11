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
                    <router-link to="/admin/daily-sale" class="btn btn-dark text-light me-3"><i class="fa fa-list"></i> Daily Sale</router-link>
                    <router-link to="/admin/profit-loss" class="btn btn-dark text-light me-3"><i class="fa fa-sliders"></i> Profit Loss</router-link>
                    <router-link to="/admin/expance" class="btn btn-dark text-light me-3"><i class="fa fa-money"></i> Expance</router-link>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <h4 class="text-primary mt-4 fw-bold">Daily Sales</h4>


                    <div class="table-responsiv mt-4">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Product Name</th>
                                    <th>QTY</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="cart in carts">
                                    <td>{{cart.name}}</td>
                                    <td>{{cart.qty}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
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
          orderitems: [],
          carts: [],
          id_list: [],
          
          from: '',
          to: '',
          config: {},
          total: 0,
        }
      },
  
      mounted() {
        this.getOrders()
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

                axios.get(`/api/product-sale?from=${this.from}&to=${this.to}`, this.config)
                    .then(data => {
                        this.orderitems = data.data
                        this.carts = this.orderitems

                        this.carts.sort((a,b) => b.qty - a.qty)
                    })
                    .catch(err => console.log(err))
          },
          
      }
    }
  </script>
  
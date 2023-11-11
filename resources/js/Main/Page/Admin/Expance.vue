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
                    <router-link to="/admin/product-sale" class="btn btn-dark text-light me-3"><i class="fa fa-money"></i> Product Sale</router-link>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <h4 class="text-primary mt-4 fw-bold">Daily Expances</h4>

                    
                    <div class="table-responsiv mt-4">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Details</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="cart in carts">
                                    <td>{{cart.name}}</td>
                                    <td>{{cart.qty}}</td>
                                </tr>
                                <tr>
                                    <td>Total</td>
                                    <td>{{ total }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col-md-6">
                    <h5 class="text-primary">Add Expance</h5>
                    <div class="alert alert-success" v-if="msg != ''">
                        {{ msg }}
                    </div>
                    <div class="form-group">
                        <label for="detail">Details</label>
                        <input type="text" v-model="detail" id="detail" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="amount">Amount</label>
                        <input type="text" v-model="amount" id="amount" class="form-control">
                    </div>

                    <button type="submit" class="btn btn-success mt-4" @click="addExpance">Add Expance</button>
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
          expances: [],
          carts: [],
          id_list: [],
          
          from: '',
          to: '',
          config: {},
          total: 0,
          detail: '',
          amount: 0,
          msg: ''
        }
      },
  
      mounted() {
        this.getExpances()
      },
  
  
      methods: {
          getExpances(){
                const token = localStorage.getItem('info')

                this.config = {
                headers: {
                    'Content-Type' : 'application/json',
                    'Accept' : 'application/json',
                    'Authorization' : 'Bearer '+token
                }
                }

                axios.get(`/api/expances?from=${this.from}&to=${this.to}`, this.config)
                    .then(data => {
                        this.expances = data.data
                        this.expances.forEach(exp => this.total += exp.amount);
                    })
                    .catch(err => console.log(err))
          },

          addExpance(){
            const token = localStorage.getItem('info')

            this.config = {
                headers: {
                    'Content-Type' : 'application/json',
                    'Accept' : 'application/json',
                    'Authorization' : 'Bearer '+token
                }
            }

            axios.post('/api/expance/add', {detail: this.detail, amount: this.amount}, this.config)
                .then(data => {
                    this.msg = 'Expance added'
                })
                .catch(err => console.log(err))
          }
          
      }
    }
  </script>
  
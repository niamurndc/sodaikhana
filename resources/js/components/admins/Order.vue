<template>
  <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">

    <!-- modal -->
    <div v-if="getModal" class="modal" aria-hidden="true" tabindex="-1">
      <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Order Details #{{order_id}}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span v-on:click="destroyModal" aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <h5 class="text-primary">Customer Details</h5>
            <h6>Name: {{active.name}}</h6>
            <h6>Phone: {{active.phone}}</h6>
            <h6>Area: {{active.area}}</h6>
            <h6>Address: {{active.address}}</h6>
            <h6>Status: </h6>
            <select v-model="active.status" class="form-control">
              <option value="0">Processing</option>
              <option value="1">Collecting Products</option>
              <option value="2">Out for Delivery</option>
              <option value="3">Trying but Failed</option>
              <option value="4">Complete</option>
            </select>

            <h5 class="mt-3 text-primary">Billing Details</h5>
            <h6>Total: {{active.subtotal}}</h6>

            <h5 class="mt-3 text-primary">Products Details</h5>
            <h6 class="text-success">Note: {{active.note}}</h6>
            <div class="table-responsive">
              <table class="table table-striped table-sm">
                <thead>
                  <tr>
                    <th>Title</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Attribute</th>
                    <th>Total</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="cart in active.carts" :key="cart.id">
                    <td>{{cart.product.title}}</td>
                    <td>{{cart.product.price}}</td>
                    <td>{{cart.quantity}}</td>
                    <td>{{cart.attri}}</td>
                    <td>{{cart.quantity * cart.product.price}}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="modal-footer">
            <button v-on:click="destroyModal" type="button" class="btn btn-secondary">Close</button>
            <button type="button" v-on:click="updateOrder" class="btn btn-primary">Save changes</button>
          </div>
        </div>
      </div>
    </div>
    <!-- modal end -->

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
      <h1 class="h2">Orders</h1>
      <div class="btn-toolbar mb-2 mb-md-0">

      </div>
    </div>

    <h3>All Orders</h3>
    <div class="table-responsive">
      <table class="table table-striped table-sm">
        <thead>
          <tr>
            <th>#</th>
            <th>Name</th>
            <th>Phone</th>
            <th>Area</th>
            <th>Total</th>
            <th>Time</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="order in allorders" :key="order.id">
            <td>{{order.id}}</td>
            <td>{{order.name}}</td>
            <td>{{order.phone}}</td>
            <td>{{order.area}}</td>
            <td>{{order.subtotal}}</td>
            <td>{{order.created}}</td>
            <td>
              <p v-if="order.status == 0" class="m-0 text-secondary">Processing</p>
              <p v-else-if="order.status == 1" class="m-0 text-primary">Collecting</p>
              <p v-else-if="order.status == 2" class="m-0 text-warning">Outing</p>
              <p v-else-if="order.status == 3" class="m-0 text-danger">Failed</p>
              <p v-else class="m-0 text-success">Complete</p>
            </td>
            <td><i v-on:click="viewOrder(order.id)" class="fa fa-pencil text-primary mr-1 pinter"></i><i v-on:click="deleteOrder(order.id)" class="fa fa-trash text-danger"></i></td>
          </tr>
        </tbody>
      </table>
    </div>
  </main>
</template>

<script>
  export default {
    data(){
      return{
        allorders: [],
        order_id: 0,
        active: {},
        getModal: false,
        config: {},
      }
    },
    mounted() {
      this.getOrder()
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

        axios.get('/api/orders', this.config)
              .then(data => {
                this.allorders = data.data.data
              })
              .catch(err => console.log(err))
      },

      viewOrder(id){
        this.order_id = id

        const token = localStorage.getItem('info')

        this.config = {
          headers: {
            'Content-Type' : 'application/json',
            'Accept' : 'application/json',
            'Authorization' : 'Bearer '+token
          }
        }
        
        axios.get(`/api/order/${id}`, this.config)
              .then(data => {
                
                const order = data.data.data
                this.active.name = order.name
                this.active.phone = order.phone
                this.active.area = order.area
                this.active.address = order.address
                this.active.subtotal = order.subtotal
                this.active.status = order.status
                this.active.note = order.note
                this.active.created = order.created
                this.active.carts = order.carts
                this.getModal = true
              })
              .catch(err => console.log(err))
      },

      updateOrder(){
        const token = localStorage.getItem('info')

        this.config = {
          headers: {
            'Content-Type' : 'application/json',
            'Accept' : 'application/json',
            'Authorization' : 'Bearer '+token
          }
        }

        axios.post(`/api/order/edit/${this.order_id}`, {status: this.active.status}, this.config)
              .then(data => {
                
                const order = data.data.data
                this.$toasted.success("Order Updated Successful", {
                  duration: 3000,
                });
                this.destroyModal()
                this.getOrder()
              })
              .catch(err => console.log(err))
      },

      destroyModal(){
        this.getModal = false
        this.order_id = 0
        this.active = {}
      },

      deleteOrder(id){
        const token = localStorage.getItem('info')

        this.config = {
          headers: {
            'Content-Type' : 'application/json',
            'Accept' : 'application/json',
            'Authorization' : 'Bearer '+token
          }
        }

        axios.delete(`/api/order/delete/${id}`, this.config)
          .then(data => {
            this.$toasted.success(data.data.message, {
                duration: 3000,
              });
            this.getOrder()
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

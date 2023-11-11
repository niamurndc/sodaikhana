<template>
  <div class="mb-5">

    <!-- modal -->
    <div v-if="getModal" class="modal" aria-hidden="true" tabindex="-1">
      <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Order Details</h5>
          </div>
          <div class="modal-body">
            <h5 class="text-primary">Customer Details</h5>
            <h6>Name: {{active.name}}</h6>
            <h6>Phone: {{active.phone}}</h6>
            <h6>Area: {{active.area}}</h6>
            <h6>Address: {{active.address}}</h6>

            <h5 class="mt-3 text-primary">Billing Details</h5>
            <h6>Total: {{active.subtotal}} BDT</h6>

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
            <button v-on:click="destroyModal" type="button" class="btn btn-success">Close</button>
          </div>
        </div>
      </div>
    </div>
    <!-- modal end -->
      
      <div class="d-flex justify-content-between mb-5">
        <h2>Your Order List</h2>
        <button @click="$router.push({path: '/user/profile'})" class="btn btn-success">Profile</button>
      </div>

      <div v-for="order in allorders" :key="order.id" class="card p-3">
        <div class="row">
          <div class="col-md-4">
            <p class="m-0">Time: {{order.created}}</p>
            <p class="m-0">Phone: {{order.phone}}</p>
          </div>
          <div class="col-md-4">
            <p class="m-0">Area: {{order.area}}</p>
            <p class="m-0">Address: {{order.address}}</p>
          </div>
          <div class="col-md-4 d-flex align-items-center justify-content-between">
            <div>
              <p v-if="order.status == 0" class="m-0 text-secondary">Status: Processing</p>
              <p v-else-if="order.status == 1" class="m-0 text-primary">Status: Collecting Product</p>
              <p v-else-if="order.status == 2" class="m-0 text-warning">Status: Out for Delivery</p>
              <p v-else-if="order.status == 3" class="m-0 text-danger">Status: Delivered</p>
              <p v-else-if="order.status == 4" class="m-0 text-danger">Status: Canceled</p>
              <p v-else class="m-0 text-success">Status: Complete</p>
              <h6>{{order.subtotal}}</h6>
            </div>
            <button @click="viewOrder(order.id)" class="btn btn-success">View</button>
          </div>
        </div>
      </div>

  </div>
</template>

<script>
export default {
  data(){
    return{
      allorders: [],
      config: {},
      getModal: false,
      active: {}
    }
  },
  
  mounted() {
    this.getAllOrder()
  },

  methods: {

    getAllOrder(){
      const token = localStorage.getItem('info')
      const phone = localStorage.getItem('phone')
      this.config = {
          headers: {
              'Content-Type' : 'application/json',
              'Accept' : 'application/json',
              'Authorization' : 'Bearer '+token
          }
      }
      axios.get(`/api/orders/${phone}`, this.config)
        .then(res => {
          this.allorders = res.data.data
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

    destroyModal(){
      this.getModal = false
      this.active = {}
    },
  }
}
</script>
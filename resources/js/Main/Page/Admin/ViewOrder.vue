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
          <h5 class="mb-0 text-primary mb-4">ID: #{{order.id}}</h5>

          <h6 class="text-primary mb-0">Customer Details</h6>
          <small class="d-block text-dark">Name: {{order.name}}</small>
          <small class="d-block text-dark">Phone: {{order.phone}}</small>
          <small class="d-block">Area: {{order.area}}</small>
          <small class="d-block">Address: {{order.address}}</small>
          <small class="d-block">{{order.created}}</small>

          <small class="d-block text-danger">Note: {{order.note}}</small>

          <h6 class="text-primary mb-0 mt-4">Billing Details</h6>
          <h6 class="mt-3">Total: {{order.subtotal - ship}} BDT</h6>
          <h6 class="mt-3">Shipping Cost: {{ship}} BDT</h6>
          <h6><b>Order Subtotal: {{order.subtotal}} BDT</b></h6>

          <h6 class="text-primary mb-0 mt-4">Product Details</h6>
          <div class="d-flex border-bottom justify-content-between align-items-center py-2" v-for="cart in order.carts" :key="cart.id">
            <div class="d-flex align-items-center text-decoration-none text-secondary">
                <div>
                    <h6 class="mb-0">{{cart.product.title}}</h6>
                    <small>Attribute: {{cart.attri}}</small>
                </div>
            </div>
            <div>
                <h6><b>Qty: {{cart.quantity}}</b></h6>
                <small class="d-block">Price: {{cart.product.price}}</small>
            </div>
          </div>

          </div>

          <div class="d-flex justify-content-between aligh-items-center mt-5">
            <button class="btn btn-outline-danger" @click="deleteOrder(order.id)"><i class="fa fa-trash"></i> Delete</button>
            <div class="col-md-6">
              <label class="text-success" v-if="statusMsg">Order Status Changed</label>
              <select v-model="status" class="form-select" @change="changeStatus">
                <option value="0">Processing</option>
                <option value="1">Collecting Product</option>
                <option value="2">Out for delivery</option>
                <option value="3">Delivered</option>
                <option value="4">Canceled</option>
              </select>
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
        allorders: [],
        order_id: this.$route.params.id,
        order: {},
        getModal: false,
        config: {},
        status: 0,
        statusMsg: false,
        ship: 30,
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
                this.status = data.data.data.status
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

      
      changeStatus(){
        const token = localStorage.getItem('info')

        this.config = {
          headers: {
            'Content-Type' : 'application/json',
            'Accept' : 'application/json',
            'Authorization' : 'Bearer '+token
          }
        }

        axios.post(`/api/order/edit/${this.order_id}`, {status: this.status}, this.config)
              .then(data => {
                this.statusMsg = true
              })
              .catch(err => console.log(err))
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
            this.$router.push({ path: '/admin'})
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
      width: 50%;
      margin: 0 auto;
    }
  }
</style>

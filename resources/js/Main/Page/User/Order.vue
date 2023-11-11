<template>
  <div class="row my-5">
    <div class="col-md-8">
      <h2>Checkout</h2>

      <div class="alert alert-info" v-if="error != ''">
        <small>{{error}}</small>
      </div>

      <div class="row p-0">
        <div class="form-group col-md-6">
          <label for="name">Name</label>
          <input type="text" v-model="order.name" class="form-control">
        </div>
        <div class="form-group col-md-6">
          <label for="phone">Phone</label>
          <input type="text" v-model="order.phone" class="form-control">
        </div>
      </div>

      <div class="row p-0">
        <div class="form-group col-md-6">
          <label for="area">Area</label>
          <select v-model="order.area" class="form-control">
            <option value="">Select Area</option>
            <option v-for="area in allareas" :key="area.id" :value="area.title">{{area.title}}</option>
          </select>
        </div>
        <div class="form-group col-md-6">
          <label for="address">Address</label>
          <input type="text" v-model="order.address" class="form-control">
        </div>
      </div>

      <div class="row">
        <div class="form-group col-md-12">
          <label for="note">Note (optional)</label>
          <textarea rows="3" class="form-control" v-model="order.note"></textarea>
        </div>
      </div>

      <div class="d-flex flex-column align-items-center justify-content-center">
        <button @click="submitOrder" class="btn btn-success mt-4 btn-lg">Place Order</button>
        <span>You accept the terms and conditions with Place Order</span>
      </div>

    </div>

    <!-- checkout end -->


    <div class="col-md-4">
      <h4>Cart Items</h4>
      <ul class="list-group">
        <li v-for="cart in carts" :key="cart.id" class="list-group-item">
          <p class="m-0">{{cart.title}}-{{cart.atrri}}</p>
          <span class="text-success bold">{{cart.price}} X {{cart.qty}} = {{cart.price * cart.qty}} </span>
        </li>
      </ul>
      <div class="mt-4 text-end bg-white p-3 border">
        <h5>Total: {{total}} BDT</h5>
        <h6>shipping Cost: {{ ship }} BDT</h6>
        <h4>Subtotal: {{total + ship}} BDT</h4>
      </div>
    </div>

  </div>
</template>

<script>
export default {
  data(){
    return{
      carts: [],
      allareas: [],
      order: {
        name: localStorage.getItem('name'),
        phone: localStorage.getItem('phone'),
        area: '',
        address: '',
        note: ''
      },
      total: 0,
      newcarts: [],
      config: {},
      error: '',
      ship: 30,
    }
  },

  mounted() {
    this.updateCart()
    this.getAreas()
    this.getSetting()
  },

  methods: {
    updateCart(){
      this.carts = localStorage.getItem('carts')
      this.carts = JSON.parse(this.carts)

      this.carts.forEach(cart => {
        this.total += cart.price * cart.qty

        const newcart = {
          product_id: cart.product_id,
          attri: cart.atrri,
          quantity: cart.qty,
          price: cart.price
        }

        this.newcarts.push(newcart)
      })
    },

    getAreas(){
      axios.get('/api/areas')
            .then(data => {
              this.allareas = data.data.data
            })
            .catch(err => console.log(err))
    },

    getSetting(){
      axios.get('/api/settings')
            .then(data => {
              this.ship = data.data.data.ship
            })
            .catch(err => console.log(err))
    },

    submitOrder(){
      if(this.newcarts.length == 0){
        // cart validate failed
          this.error = 'Your cart is empty'
      }else{
        // cart validate pass
        if(this.order.name == '' || this.order.phone == '' || this.order.area == '' || this.order.address == ''){

          // field vlaidate failed
          this.error = "Fields are required"
 
        }else{

          // field validate passed
          const token = localStorage.getItem('info')
          this.config = {
              headers: {
                  'Content-Type' : 'application/json',
                  'Accept' : 'application/json',
                  'Authorization' : 'Bearer '+token
              }
          }

          this.total = this.total + this.ship

          axios.post('/api/order/add', {name: this.order.name, phone: this.order.phone, address: this.order.address, area: this.order.area, subtotal: this.total, due: this.total, paid: 0, carts: this.newcarts, note: this.order.note}, this.config)
            .then(res => {
              localStorage.removeItem('carts')
              this.$router.push({path: '/user/success'})
            })
            .catch(err => console.log(err))
        }
      }
    },
  }
}
</script>
<template>
  <div class="row my-5">
    <div class="col-md-8">
      <h2>Checkout</h2>

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
            <option v-for="area in areas">{{area}}</option>
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
        <button @click="submitOrder" class="btn btn-add-cart btn-lg">Place Order</button>
        <span>You accept the terms and conditions with Place Order</span>
      </div>

    </div>

    <!-- checkout end -->


    <div class="col-md-4">
      <h4>Cart Items</h4>
      <ul class="list-group">
        <li v-for="cart in carts" :key="cart.id" class="list-group-item">
          <p class="m-0">{{cart.title}}-{{cart.atrri}}</p>
          <span class="text-green bold">{{cart.price}} X {{cart.qty}} = {{cart.price * cart.qty}} </span>
        </li>
      </ul>
      <div class="mt-4 text-right">
        <h5>Total: {{total}} BDT</h5>
        <h6>shipping Cost: 25 BDT</h6>
        <h4>Subtotal: {{total + 25}} BDT</h4>
      </div>
    </div>

  </div>
</template>

<script>
export default {
  data(){
    return{
      carts: [],
      order: {
        name: localStorage.getItem('name'),
        phone: localStorage.getItem('phone'),
        area: '',
        address: '',
        note: '',
      },
      total: 0,
      newcarts: [],
      config: {},
      areas: [
            'Akur Takur Para - North',
            'Jela Sadar',
            'Poshchim Akur Takur Para',
            'Kagmara (North)',
            'Kagmara (South)',
            'Bepari Para',
            'Beradoma',
            'Dighulia',
            'Char Dighulia',
            'Bot tola (Bazar)',
            'Kalipur',
            'College Para',
            'Peradise Para',
            'Par Dighulia',
            'Baluk Kandi',
            'Bagan Bari',
            'Patuli Para',
            'Pal Para',
            'Aloa Bhobani',
            'Char Patuli',
            'Aloa (East)',
            'Bajitpur',
            'Berabusina',
            'Kachuadanga',
            'Kanda Para',
            'Adi Tangail',
            'Bil Boali',
            'Tangail - mul moholla',
            'Soi-Ani Bazar',
            'Pach-Ani Bazar',
            'Thana Para',
            'Purba Adalat Para',
            'Adalat Para',
            'Bishwas Betka',
            'Shahapara',
            'Ashekpur',
            'Par Dighulia',
            'Kumudini College Para',
            'Munsi Para',
            'Registry Para',
            'Kodalia',
            'Sabalia',
            'Shantikunja',
            'Airport',
            'Polashtoli',
            'Bottola',
            'Taltola',
            'Bokultola',
            'Boilla',
            'Dhulerchar Madrasha',
            'Boali',
            'Godown Bazar',
            'Atpukur',
            'Shupari Bagan',
            'Shabalia (tintala)',
            'Tangail Medical',
            'Deola',
            'LGED',
            'Sabalia (Bottola)',
            'Kagmara (meser market)',
            'Sapra Mosjid',
            'Nagar Jalfe',
            'Shimultali',
            'Housing Math',
            'Amghat Road',
            'Tuhin Road',
            'Dhopa Para',
            'Jhilpar',
            'Soto Kalibari',
            'Boro Kalibari',
            'Hazra Ghat',
            'Shantosh (Bazar)',
            'MBSTU',
            'Stadium'
            ]
    }
  },

  mounted() {
    this.updateCart()
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
          quantity: cart.qty
        }

        this.newcarts.push(newcart)
      })
    },

    submitOrder(){
      if(this.newcarts.length == 0){
        // cart validate failed
          this.$toasted.error("Your Cart is empty", {
            duration: 3000,
          })
      }else{
        // cart validate pass
        if(this.order.name == '' || this.order.phone == '' || this.order.area == '' || this.order.address == ''){

          // field vlaidate failed
          this.$toasted.error("Fields are required", {
            duration: 3000,
          })
 
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

          axios.post('/api/order/add', {name: this.order.name, phone: this.order.phone, address: this.order.address, area: this.order.area, subtotal: this.total, carts: this.newcarts}, this.config)
            .then(res => {
              this.$toasted.success("Order placed successfully", {
                duration: 3000,
              })

              localStorage.removeItem('carts')
              this.$router.push({path: '/'})
            })
            .catch(err => console.log(err))
        }
      }
    },
  }
}
</script>
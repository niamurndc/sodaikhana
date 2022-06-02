<template>
  <div>
    <div v-if="pid == product.id" class="modal " tabindex="-1" role="dialog">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content p-2">
          <div class="d-flex justify-content-between align-items-center">
            <h5 class="modal-title"></h5>
            <button @click="destroyModal" type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">
            <div class="row">
              <div class="col-md-6">
                <img :src="product.image" class="" width="100%" height="350px" alt="">
              </div>
              <div class="col-md-6 text-left">
                <h3 class="product-title">{{product.title}}</h3>
                <h6 class="mt-3">{{product.brand.title}}</h6>
                <p class="mt-3"><span v-for="cat in product.category" :key="cat.id">{{cat.title}}, </span></p>
                <p v-if="product.attri1 == null" class="m-0">{{product.attri}}</p>
                <select v-else v-model="attribute" v-on:change="changePrice" class="form-control">
                  <option :value="product.val1">{{product.attri1}}</option>
                  <option v-if="product.attri2 != null" :value="product.val2">{{product.attri2}}</option>
                  <option v-if="product.attri3 != null" :value="product.val3">{{product.attri3}}</option>
                </select>
                <h2 class="text-green mt-4">{{price}} BDT</h2>

                <button v-if="product.stock !== 'on'" @click="addtoCart(product.id)" class="btn btn-add-cart btn-lg">Add to cart</button>
                <p v-else class="text-danger">Stock Out</p>
                <button v-if="product.stock === 'on'" @click="sendRequest(product.id)" class="btn btn-add-cart btn-lg">Send Product Request</button>
              </div>

              <div class="col-md-12 text-left mt-5">
                <h5>Description</h5>
                {{product.desc}}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- modal end -->

    <div class="row">
      <div class="col-6">
        <img @click="showModal(product.id)" :src="product.image"  alt="offer image" class="card-image" height="150px" width="100%">
      </div>
      <div class="col-6 p-2">
        <h5 @click="showModal(product.id)" class="">{{product.title}}</h5>
        
        <h6 class="m-0"><strike class="text-danger">{{product.offprice}} BDT</strike></h6>
        <span class="mt-2">Offer price</span>
        <h3 class="m-0">{{product.price}} BDT</h3>
        <div v-if="product.stock !== 'on'">
          <button v-if="showbtn" @click="decCart(product.id)" class="btn btn-sm btn-outline-secondary">-1</button>
          <button  @click="addtoCart(product.id)" class="btn btn-add-cart btn-sm">Add to Cart</button> <button v-if="showbtn" @click="incCart(product.id)" class="btn btn-sm btn-outline-secondary">+1</button>
        </div>
        
        <div v-else>
          <p class="text-danger">Stock Out</p>
        </div>
      </div>
    </div> 
  </div>
</template>

<script>
import { v4 as uuidv4 } from 'uuid'

export default {

  props: ['product'],
  data(){
    return{
      attribute: this.product.val1,
      price: this.product.price,
      cart: [],
      attri: this.product.attri,
      pid: 0,
      config: {},
      carts: [],
      cartchecks: [],
      showbtn: false
    }
  },

  created() {
    this.showExtraBtn(this.product.attri)
  },

  methods: {
    changePrice(){
      this.price = this.attribute / 100 * this.product.price

      if(this.attribute != null){
        if(this.attribute == this.product.val1){
          this.attri = this.product.attri1
        }else if(this.attribute == this.product.val2){
          this.attri = this.product.attri2
        }else if(this.attribute == this.product.val3){
          this.attri = this.product.attri3
        }
      }

      this.showExtraBtn(this.attri)
    },

    addtoCart(id){
      if(this.attribute != null){
        if(this.attribute == this.product.val1){
          this.attri = this.product.attri1
        }else if(this.attribute == this.product.val2){
          this.attri = this.product.attri2
        }else if(this.attribute == this.product.val3){
          this.attri = this.product.attri3
        }
      }
      
      const newcart = {
        id: uuidv4(),
        title: this.product.title,
        image: this.product.image,
        price: this.price,
        product_id: id,
        atrri: this.attri,
        qty: 1
      }

      this.cart = JSON.parse(localStorage.getItem('carts'))

      if(this.cart == null){
        this.cart = []
        this.cart.push(newcart)
        localStorage.setItem('carts', JSON.stringify(this.cart))

        this.$emit('updateCart')

      }else{
        const getcart = this.cart.find(c => c.product_id == id && c.atrri == this.attri)

        if(getcart != null){
          this.$toasted.error("Already added into cart", {
            duration: 3000,
          });
        }else{
          this.cart.push(newcart)
          localStorage.setItem('carts', JSON.stringify(this.cart))

          this.$emit('updateCart')
        }
      }

      this.attribute = this.product.val1
      this.price = this.product.price

      this.showExtraBtn(this.product.attri)
    },

    showModal(id){
      this.pid = id
    },

    destroyModal(){
      this.pid = 0
    },

    sendRequest(id){
      const token = localStorage.getItem('info')

      if(!token){
        this.$toasted.error("Please login for request", {
          duration: 3000,
        });
        this.$router.push({path: '/login'})
      }else{
        this.config = {
            headers: {
                'Content-Type' : 'application/json',
                'Accept' : 'application/json',
                'Authorization' : 'Bearer '+token
            }
        }

        axios.get(`/api/request/add/${this.pid}`, this.config)
          .then(res => {
            this.$toasted.success("Request send successfuly", {
              duration: 3000,
            });
          })
          .catch(err => console.log(err))
      }  
    },


    // cart function
    incCart(id){
      this.carts = localStorage.getItem('carts')
      this.carts = JSON.parse(this.carts)
    
      let activecart = this.carts.find(cart => cart.product_id == id && cart.atrri == this.attri)
      activecart.qty += 1

      let withoutactivecart = this.carts.filter(cart => cart.id != activecart.id)
      withoutactivecart.push(activecart)
      localStorage.setItem('carts', JSON.stringify(withoutactivecart))
      this.$emit('updateCart')
    },

    decCart(id){
        this.carts = localStorage.getItem('carts')
        this.carts = JSON.parse(this.carts)

        let activecart = this.carts.find(cart => cart.product_id == id && cart.atrri == this.attri)
        activecart.qty -= 1
        
        let withoutactivecart = this.carts.filter(cart => cart.id != activecart.id)

        if(activecart.qty > 0){
            withoutactivecart.push(activecart)
        }else{
          this.showbtn = false
        }

        localStorage.setItem('carts', JSON.stringify(withoutactivecart))

        this.$emit('updateCart')
    },

    showExtraBtn(attri){
      console.log(this.product.id)
      this.cartchecks = localStorage.getItem('carts')
      this.cartchecks = JSON.parse(this.cartchecks)

      this.cartchecks = this.cartchecks.filter(cart => cart.product_id == this.product.id && cart.atrri == attri)

      console.log(this.cartchecks)
      if(this.cartchecks.length != 0){
        this.showbtn = true
      }else{
        this.showbtn = false
      }
    }
  }
}
</script>

<style scoped>
  .product-title:hover{
    color: #289672;
    transform: scale(1.1);
    cursor: pointer;
  }

  .product-modal{
    width: 900px;
  }
</style>
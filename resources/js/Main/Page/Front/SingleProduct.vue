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
                <img :src="product.image" height="200" alt="">
              </div>
              <div class="col-md-6 text-left">
                <h3 class="product-title">{{product.title}}</h3>
                <h6 class="mt-3 text-success">{{product.brand}}</h6>

                <p v-if="product.attri1 == null" class="m-0">{{product.attri}}</p>
                <select v-else v-model="attribute" v-on:change="changePrice" class="form-control">
                  <option :value="product.val1">{{product.attri1}}</option>
                  <option v-if="product.attri2 != null" :value="product.val2">{{product.attri2}}</option>
                  <option v-if="product.attri3 != null" :value="product.val3">{{product.attri3}}</option>
                </select>
                <h2 class="text-green mt-4">{{price}} BDT</h2>

                <button v-if="product.stock == 'on' || product.qty != 0" @click="addtoCart(product.id)" class="btn btn-success btn-lg">Add to cart</button>
                <button v-else @click="sendRequest(product.id)" class="btn btn-outline-success btn-lg">Send Product Request</button>
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
    <img @click="showModal(product.id)" :src="product.image" class="product-image mb-2" alt="">
    <h5 @click="showModal(product.id)" class="text-success fs-6">{{product.title}}</h5>
    <p v-if="product.attri1 == null" class="m-0 text-secondary">{{product.attri}}</p>
    <select v-else v-model="attribute" v-on:change="changePrice" class="form-control-sm">
      <option :value="product.val1">{{product.attri1}}</option>
      <option v-if="product.attri2 != null" :value="product.val2">{{product.attri2}}</option>
      <option v-if="product.attri3 != null" :value="product.val3">{{product.attri3}}</option>
    </select>
    <p class="m-0" v-if="product.offer">

        <strike> {{product.price}}BDT </strike>
        <h4 class="text-red"> {{price}}BDT </h4>
    </p>

    <p class="m-0" v-else>
        <b> {{price}}BDT </b>
    </p>

    <p v-if="product.stock == 'on' || product.qty == 0" class="text-danger">Stock Out</p>
    <button v-else  @click="addtoCart(product.id)" class="btn btn-success mt-3 btn-sm">Add to cart</button>
    
  </div>
</template>

<script>
import { v4 as uuidv4 } from 'uuid'

export default {

  props: ['product'],
  data(){
    return{
      attribute: this.product.val1,
      price: this.product.offer ?  this.product.offprice : this.product.price,
      cart: [],
      attri: this.product.attri,
      pid: 0,
      config: {}
    }
  },

  created() {
    
  },

  methods: {
    changePrice(){
      if(this.product.offer){
        this.price = this.attribute / 100 * this.product.offprice
      }else{
        this.price = this.attribute / 100 * this.product.price
      }
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
      if(this.product.offer){
        this.price = this.product.offprice
      }else{
        this.price = this.product.price
      }
      
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
    }
  }
}
</script>

<style scoped>
  .product-modal{
    width: 900px;
  }

  .text-red{
    color: red;
  }
</style>
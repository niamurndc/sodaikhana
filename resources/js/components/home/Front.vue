<template>
  <div class="front">
    <div class="hero">
      <img src="/image/cover2.jpg" alt="cover image" width="100%">
    </div>
    <div class="hero-2 mt-4">
      <div class="row p-0">
        <div class="col-md-6">
          <img src="/image/cover1.jpg" alt="cover image" width="100%">
        </div>
        <div class="col-md-6">
          <img src="/image/cover3.jpg" alt="cover image" width="100%">
        </div>
      </div>
    </div>

    <div class="offer text-center my-5">
      <h2>Special Offer</h2>
      <div class="container mt-5">
        <div class="row">
          <div v-for="offer in offerproducts" :key="offer.id" class="col-md-4">
            <div class="card p-3 mb-4">
              <div class="row">
                <div class="col-6">
                  <img :src="offer.image" alt="offer image" class="card-image" height="150px" width="100%">
                </div>
                <div class="col-6 p-2">
                  <h5 class="">{{offer.title}}</h5>
                  
                  <h6 class="m-0">Actual price: <strike class="text-danger">{{offer.price}} BDT</strike></h6>
                  <span class="mt-2">Offer price</span>
                  <h3 class="m-0">{{offer.offprice}} BDT</h3>
                  <button class="btn btn-add-cart btn-sm mt-2">Add to Cart</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="show-section py-5">
      <div class="container">
        <div class="row">
          <div class="col-md-6">
            <img src="/image/image1.jpg" alt="delivery box" width="100%">
          </div>
          <div class="col-md-6 d-flex align-items-center">
            <h1>We Are Always Ready To Deliver Your <span class="text-pink"> All Needs Safely</span></h1>
          </div>
        </div>
      </div>
    </div>

    <div class="popular-section text-center py-5">
      <h2>Popular Products</h2>
      <div class="container pt-5">
        <div class="row">
          <div v-for="popular in popularproducts" :key="popular.id" class="col-md-3 col-sm-4 col-6 p-3 text-center border">
            <SingleProduct :product="popular" v-on:updateCart="updateCart" />
          </div>    
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import SingleProduct from './SingleProduct.vue'
  export default {
    components: {
      SingleProduct
    },

    data(){
      return{
        offerproducts: [],
        popularproducts: []
      }
    },
    mounted() {
        console.log('Component mounted.')
        this.getOffer()
        this.getPopular()
    },

    methods: {
      getOffer(){
        axios.get('/api/productoffer')
          .then(res => {
            this.offerproducts = res.data.data
          })
          .catch(err => console.log(err))
      },

      getPopular(){
        axios.get('/api/productpopular')
          .then(res => {
            this.popularproducts = res.data.data
          })
          .catch(err => console.log(err))
      },

      updateCart(){
        this.$emit('updateCart')
      }
    }
  }
</script>

<style scoped>
  .show-section{
    background-color: #289672;
    color: #fff;
  }

  .text-pink{
    color: #E6DD3B;
  }

  .social-link i{
    font-size: 30px;
    padding-right: 40px;
  }
</style>
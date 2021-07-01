<template>
  <div class="main-content pt-3">
    
    
    <router-view v-on:updateCart="updateCart" />

    <div class="footer bg-dark text-white px-5 pt-5">
      <div class="row">
        <div class="col-md-3">
          <h4>Sodaikhana</h4>
          <p><a href="#" class="text-white">Home</a></p>
          <p><a href="#" class="text-white">Shop</a></p>
          <p><a href="#" class="text-white">About</a></p>
          <p><a href="#" class="text-white">Contact</a></p>
        </div>
        <div class="col-md-3">
          <h4></h4>
          <p><a href="#" class="text-white">Login</a></p>
          <p><a href="#" class="text-white">Register</a></p>
          <p><a href="#" class="text-white">Account</a></p>
          <p><a href="#" class="text-white">Checkout</a></p>
        </div>
        <div class="col-md-3">
          <h4>About</h4>
          <p><a href="#" class="text-white">About Us</a></p>
          <p><a href="#" class="text-white">Payment</a></p>
          <p><a href="#" class="text-white">Privacy Policy</a></p>
          <p><a href="#" class="text-white">Terms & Conditions</a></p>
        </div>
        <div class="col-md-3">
          <h4>Social Links</h4>
          <div class="social-link d-flex justify-content-evenly">
            <a href="#" class="text-white"><i class="fa fa-facebook"></i></a>
            <a href="#" class="text-white"><i class="fa fa-twitter"></i></a>
            <a href="#" class="text-white"><i class="fa fa-instagram"></i></a>
          </div>
        </div>
      </div>

      <hr>
      <div class="d-flex justify-content-between pt-3">
        <p> Sodaikhana &copy; 2021 </p>
        <a href="https://www.facebook.com/bloopwebsolution" class="text-green">Developed by Bloop Web Solution</a>
      </div>
    </div>
  </div>
</template>

<script>
    export default {
        components: {
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
<template>
  <div v-if="auth" class="page">
    <nav class="menu text-light custome-nav px-2 px-md-4 py-2 py-md-3 sticky-top shadow">
      <div class="row">
        <div class="col-6 d-flex align-items-center">
          <i class="fa fa-bars mr-3 mb-2"></i>
          <router-link to="/" class="brand-title">Sodaikhana</router-link>
        </div>
        <div class="col-6 d-flex align-items-center justify-content-end">
          <i @click="$router.push({path: '/user/profile'})" class="fa fa-user-circle-o d-md-none mb-2"></i>
          <i @click="logout" class="fa fa-sign-out d-md-none mb-2 ml-4 mr-2"></i>
          <router-link v-if="auth" to="/user/profile" class="text-white d-none d-md-inline">{{name}}</router-link>
          <a @click="logout" href="#" class="btn btn-custom ml-3 d-none d-md-block">Logout</a>
        </div>
      </div>        
    </nav>

    <!-- chekout start -->

    <div class="container pt-5">
      <router-view />
    </div>

    <div class="footer bg-dark text-white px-5 pt-5">
      <div class="row">
        <div class="col-md-3">
          <h4>Sodaikhana</h4>
          <p><a href="/" class="text-white">Home</a></p>
          <p><a href="/" class="text-white">Cart</a></p>
          <p><router-link to="/user/checkout" class="text-white">Checkout</router-link></p>
        </div>
        <div class="col-md-3">
          <h4></h4>
          <p><router-link to="/login" class="text-white">Login</router-link></p>
          <p><router-link to="/register" class="text-white">Register</router-link></p>
          <p><router-link to="/user/profile" class="text-white">Acount</router-link></p>
          
        </div>
        <div class="col-md-3">
          <h4>About</h4>
          <p><a href="#" class="text-white">About Us</a></p>
          <p><a href="#" class="text-white">Privacy Policy</a></p>
          <p><p><router-link to="/terms-conditions" class="text-white">Terms & Conditions</router-link></p>
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
    data(){
      return{
        auth: false,
        config: {},
        name: localStorage.getItem('name')
      }
    },

    beforeCreate() {
      const token = localStorage.getItem('info')

      if(!token){
        this.$router.push({path: '/login'})
      }else{
        this.config = {
            headers: {
                'Content-Type' : 'application/json',
                'Accept' : 'application/json',
                'Authorization' : 'Bearer '+token
            }
        }

        axios.get('/api/profile', this.config)
            .then(res => {
                const role = localStorage.getItem('phone')
                if(res.data.phone != role){
                    this.$router.push({path: '/login'})
                }else{
                    if(res.data.status == 0){
                      this.$router.push({path: '/varify'})
                    }else{
                      this.auth = true
                    }
                    
                }
            })
            .catch(err => {
              if(err.response.status == 401){
                this.$router.push({path: '/'})
              }
            })
      }
    },

    mounted() {
    },

    methods: {

      logout(){
        const token = localStorage.getItem('info')
        this.config = {
            headers: {
                'Content-Type' : 'application/json',
                'Accept' : 'application/json',
                'Authorization' : 'Bearer '+token
            }
        }

        axios.post('/api/logout', {}, this.config)
        .then(res => {
            if(res.data.message){
                this.$toasted.success("You are logged out", {
                    duration: 3000,
                },)
            }
            this.$router.push({path: '/'})
            this.auth = false
            localStorage.removeItem('info')
            localStorage.removeItem('phone')
            localStorage.removeItem('key')
            localStorage.removeItem('name')
        })
        .catch(err => console.log(err))
      }
    }
  }
</script>
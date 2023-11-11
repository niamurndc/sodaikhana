<template>
  <div v-if="auth" class="page">
    <nav class="menu text-light navbar-dark bg-success px-2 px-md-4 py-2 py-md-3 sticky-top shadow">
      <div class="row">
        <div class="col-6 d-flex align-items-center">
          <router-link to="/" class="text-decoration-none fs-5 text-light">Sodaikhana</router-link>
        </div>
        <div class="col-6 d-flex align-items-center justify-content-end">
          <a @click="logout" href="#" class="btn btn-light text-success ml-3">Logout</a>
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
        </div>
        <div class="col-md-3">
        </div>
        <div class="col-md-3">
        </div>
        <div class="col-md-3">
          <div class="social-link d-flex justify-content-end">
            <a href="#" class="text-white fs-4 ms-4"><i class="fa fa-facebook"></i></a>
            <a href="#" class="text-white fs-4 ms-4"><i class="fa fa-twitter"></i></a>
            <a href="#" class="text-white fs-4 ms-4"><i class="fa fa-instagram"></i></a>
          </div>
        </div>
      </div>

      <hr>
      <div class="d-flex justify-content-between pt-3">
        <p> Sodaikhana &copy; 2021 </p>
        <a href="https://www.facebook.com/bloopwebsolution" class="text-light text-decoration-none">Developed by Bloop Web Solution</a>
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
        this.$router.push({path: '/'})
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
                    this.$router.push({path: '/'})
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
<template>
  <div class="login position-relative">
    <i @click="$router.push({path: '/'})" class="fa fa-times"></i>
    <div class="container row d-flex align-items-center justify-content-center py-5 px-0 mx-0">
      <div class="col-md-4">
        <h2 class="text-center text-green" @click="$router.push({path: '/'})">Sodaikhana</h2>
        <h3 class="text-center">Login</h3>

        <div class="alert alert-info" v-if="error != ''">
          <small>{{error}}</small>
        </div>
        <div class="form-group mt-5">
          <label for="phone">Phone</label>
          <input type="number" v-model="phone" class="form-control">
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" v-model="password" class="form-control">
        </div>
        <div class="d-flex justify-content-between align-items-center mt-4">
          <button @click="login" class="btn btn-success">Login</button>
          <router-link to="/forgot" class="text-success">Forgot Password</router-link>
        </div>
        <p class="text-center mt-4">
          Create New Account <router-link class="text-success" to="/register">Register</router-link>
        </p>
      </div>
    </div>
  </div>
</template>

<script>
export default {

  data(){
    return{
      phone: '',
      password: '',
      error: ''
    }
  },

  methods: {
    login(){
      // validation
      if(this.phone == '' || this.password == ''){

        // failed validation
        this.error = "Phone & Password is required"
      }else{

        // pass validation

        axios.post('/api/login', {
          phone: this.phone, password: this.password
        })
          .then(res => {
            console.log(res)

            // wrong password alert
            if(res.data.message){
              this.error = "Incorrect information"
            }

            if(res.data.token){
              localStorage.setItem('info', res.data.token)
              localStorage.setItem('name', res.data.user.name)
              localStorage.setItem('phone', res.data.user.phone)
              localStorage.setItem('key', res.data.user.role)
              if(res.data.user.role == 23314){
                this.$router.push({path: '/admin'})
              }else{
                this.$router.push({path: '/'})
              }
            }


          })
          .catch(err => {
            if(err.response.status == 422){
              this.error = "Incorrect information"
            }
          })
      }
      
      
    }
  }
}
</script>

<style scoped>
.fa{
  position: absolute;
  top: 10px;
  right: 20px;
  font-size: 30px;
  cursor: pointer;
}
</style>

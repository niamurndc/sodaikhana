<template>
  <div class="login position-relative h-100 mt-0">
    <i @click="$router.push({path: '/'})" class="fa fa-times"></i>
    <div class="container d-flex align-items-center justify-content-center py-5">
      <div class="col-md-6">
        <h2 class="text-center text-green" @click="$router.push({path: '/'})">Sodaikhana</h2>
        <h3 class="text-center">Register</h3>
        <div class="form-group mt-5">
          <label for="name">Name</label>
          <input type="text" v-model="name" class="form-control">
        </div>
        <div class="form-group">
          <label for="phone">Phone</label>
          <input type="text" v-model="phone" class="form-control">
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" v-model="password" class="form-control">
        </div>
        <div class="d-flex justify-content-between align-items-center mt-4">
          <button @click="register" class="btn btn-add-cart">Create Account</button>
        </div>
        <p class="text-center mt-4">
          I have an account <router-link class="text-green" to="/login">Login</router-link>
        </p>
        
      </div>
    </div>
  </div>
</template>

<script>
export default {

  data(){
    return{
      name: '',
      phone: '',
      password: ''
    }
  },

  methods: {
    register(){
      // validation
      if(this.phone < 11 || this.password.length < 6 || this.name == ''){

        // failed validation
        this.$toasted.error("All are required & Password should be 6 ", {
          duration: 3000,
        });
      }else{

        // pass validation

        axios.post('/api/register', {
          phone: this.phone, password: this.password, name: this.name
        })
          .then(res => {
            console.log(res)

            // wrong password alert
            if(res.data.message){
              this.$toasted.error("Information is not correct", {
                duration: 3000,
              },)
            }

            if(res.data.token){
              localStorage.setItem('info', res.data.token)
              localStorage.setItem('name', res.data.user.name)
              localStorage.setItem('phone', res.data.user.phone)
              localStorage.setItem('key', res.data.user.role)

              this.$router.push({path: '/varify'})
            }


          })
          .catch(err => {
            console.log(err.response)
            if(err.response.status == 422){
              if(err.response.data.errors.phone){
                this.$toasted.error(err.response.data.errors.phone[0], {
                  duration: 3000,
                },)
              }

              if(err.response.data.errors.name){
                this.$toasted.error(err.response.data.errors.name[0], {
                  duration: 3000,
                },)
              }

              if(err.response.data.errors.password){
                this.$toasted.error(err.response.data.errors.password[0], {
                  duration: 3000,
                },)
              }
              
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

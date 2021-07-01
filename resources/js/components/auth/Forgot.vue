<template>
  <div class="login position-relative h-100 mt-0">
    <i @click="$router.push({path: '/'})" class="fa fa-times"></i>
    <div class="container d-flex align-items-center justify-content-center py-5">
      <div class="col-md-6">
        <h2 class="text-center text-green" @click="$router.push({path: '/'})">Sodaikhana</h2>
        <h3 class="text-center">Forgot Password</h3>

        <div class="form-group mt-5">
          <label>Phone Number</label>
          <div class="input-group">
            <input type="text" v-model="phone" class="form-control">
            <div class="input-group-append">
                <button @click="getCode" class="btn btn-custom" type="submit">Send</button>
            </div>
          </div>
        </div>
        

        <div class="form-group">
          <label for="name">Code</label>
          <input type="text" v-model="code" class="form-control">
        </div>

        <div class="form-group">
          <label for="pass">Password</label>
          <input type="password" v-model="password" class="form-control">
        </div>

        <div class="d-flex justify-content-between align-items-center mt-4">
          <button @click="varify" class="btn btn-add-cart">Submit</button>
        </div>
        
      </div>
    </div>
  </div>
</template>

<script>
export default {

  data(){
    return{
      code: '',
      phone: '',
      password: '',
      config: {}
    }
  },

  methods: {
    getCode(){
      if(this.phone == ''){
        this.$toasted.error("Insert your phone", {
          duration: 3000,
        });
      }else{

        axios.post('/api/forgot', {
          phone: this.phone})
          .then(res => {
            console.log(res)

            // wrong password alert
            if(res.data.error){
              this.$toasted.error("Phone number is not correct", {
                duration: 3000,
              },)
            }

            if(res.data.message){
              this.$toasted.success("Code is send", {
                duration: 3000,
              },)
            }
          })
          .catch(err => console.log(err))
      }
    },

    varify(){
      // validation
      if(this.code == '' || this.password < 6){

        // failed validation
        this.$toasted.error("Code is required and password must be 6", {
          duration: 3000,
        });
      }else{

        axios.post('/api/getpassword', {
          code: this.code, password: this.password})
          .then(res => {
            console.log(res)

            // wrong password alert
            if(res.data.error){
              this.$toasted.error("Code is not correct", {
                duration: 3000,
              },)
            }

            if(res.data.message){
              this.$toasted.success("Pasword reset Please Login", {
                duration: 3000,
              },)

              this.$router.push({path: '/login'})
            }


          })
          .catch(err => console.log(err))
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


<template>
  <div class="login position-relative h-100 mt-0">
    <i @click="$router.push({path: '/'})" class="fa fa-times"></i>
    <div class="container d-flex align-items-center justify-content-center py-5">
      <div class="col-md-6">
        <h2 class="text-center text-green" @click="$router.push({path: '/'})">Sodaikhana</h2>
        <h3 class="text-center">Varify Phone</h3>
        <p class="text-center">You recive a code. Don't recive <b @click="sendCodeAgain" class="text-green">send again</b></p>
        <div class="form-group mt-5">
          <label for="name">Code</label>
          <input type="text" v-model="code" class="form-control">
        </div>

        <div class="d-flex justify-content-between align-items-center mt-4">
          <button @click="varify" class="btn btn-add-cart">Varify</button>
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
      config: {}
    }
  },

  methods: {
    varify(){
      // validation
      if(this.code == ''){

        // failed validation
        this.$toasted.error("Code is required", {
          duration: 3000,
        });
      }else{

        // pass validation
        const token = localStorage.getItem('info')


        this.config = {
          headers: {
              'Content-Type' : 'application/json',
              'Accept' : 'application/json',
              'Authorization' : 'Bearer '+token
          }
        }

        axios.post('/api/varify', {
          code: this.code
        }, this.config)
          .then(res => {

            // wrong code alert
            if(res.data.error){
              this.$toasted.error("Code is not correct", {
                duration: 3000,
              },)
            }

            if(res.data.message){
              this.$toasted.success("Your profile is varified", {
                duration: 3000,
              },)

              this.$router.push({path: '/'})
            }

          })
          .catch(err => console.log(err))
      }
    },

    sendCodeAgain(){
      // pass validation
      const token = localStorage.getItem('info')

      this.config = {
        headers: {
            'Content-Type' : 'application/json',
            'Accept' : 'application/json',
            'Authorization' : 'Bearer '+token
        }
      }

      axios.get('/api/sendcode', this.config)
        .then(res => {

          if(res.data.message){
            this.$toasted.success("Send code", {
              duration: 3000,
            },)
          }

        })
        .catch(err => {
          if(err.response.status == 401){
            this.$toasted.error("You are not logged in", {
              duration: 3000,
            },)
          }
        })
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

b{
  cursor: pointer;
}
</style>


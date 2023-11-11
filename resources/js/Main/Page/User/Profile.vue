<template>
  <div class="row my-5">
    <div class="col-md-12">
      
      <div class="d-flex justify-content-between">
        <h2>Profile</h2>
        <button @click="$router.push({path: '/user/orders'})" class="btn btn-success">Your Order</button>
      </div>

      <div class="bg-white border rounded-0 p-2 mt-4">
        <p class="mb-1 fw-bold text-success fs-5">Name: {{user.name}}</p>
        <p class="mb-1 fw-bold text-success fs-5">Phone: {{user.phone}}</p>
        <p class="mb-1 fw-bold text-success fs-6">Referral Code: {{user.code}}</p>
        <p class="mb-1 fw-bold text-danger fs-5">Reward Point: {{user.point}}</p>
      </div>
      

      <div class="row p-0 mt-5">
        <div class="form-group col-md-6">
          <label for="name">Name</label>
          <input type="text" v-model="user.name" class="form-control">
        </div>
        <div class="form-group col-md-6">
          <label for="password">Password</label>
          <input type="password" v-model="user.password" class="form-control">
        </div>
      </div>

        <button @click="updateProfile" class="btn btn-success mt-4">Update Profile</button>

    </div>

    <!-- checkout end -->

  </div>
</template>

<script>
export default {
  data(){
    return{
      user: {
        name: '',
        phone: '',
        password: '',
        code: '',
        point: '',
      },
      config: {}
    }
  },
  
  mounted() {
    this.getProfile()
  },

  methods: {

    getProfile(){
      const token = localStorage.getItem('info')
      this.config = {
          headers: {
              'Content-Type' : 'application/json',
              'Accept' : 'application/json',
              'Authorization' : 'Bearer '+token
          }
      }
      axios.get('/api/profile', this.config)
        .then(res => {
          this.user.name = res.data.name
          this.user.phone = res.data.phone
          this.user.code = res.data.ref_code
          this.user.point = res.data.point
        })
        .catch(err => console.log(err))
    },

    updateProfile(){
      const token = localStorage.getItem('info')
      this.config = {
          headers: {
              'Content-Type' : 'application/json',
              'Accept' : 'application/json',
              'Authorization' : 'Bearer '+token
          }
      }
      axios.post('/api/profile/update', {name: this.user.name, password: this.user.password}, this.config)
        .then(res => {
          this.$toasted.success("Profile updated", {
            duration: 3000,
          })

          this.user.name = res.data.name
          this.user.phone = res.data.phone

          localStorage.setItem('name', res.data.name)

        })
        .catch(err => console.log(err))
    },
  }
}
</script>
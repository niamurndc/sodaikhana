<template>
    <main>
      <div class="bg-primary text-white position-sticky sticky-top">
          <div class="container py-3 d-flex justify-content-between align-items-center">
              <div class="d-flex align-items-center">
                  <router-link to="/admin" class="text-white me-2"><i class="fa fa-arrow-left"></i></router-link>
                  <h4 class="mb-0 pb-0">Setting</h4>
              </div>
          </div>
      </div>
  
      <div class="container">
        <div class="form-group mt-2">
            <label for="ship">Delivery Charge</label>
            <input type="text" v-model="ship" id="ship" class="form-control">
        </div>
        <div class="form-group mt-2">
            <label for="reg_point">Affiliate Registration Point</label>
            <input type="text" v-model="reg_point" id="reg_point" class="form-control">
        </div>
        <div class="form-group mt-2">
            <label for="sms_numbers">Order SMS Numbers</label>
            <input type="text" v-model="sms_numbers" id="sms_numbers" class="form-control">
        </div>
        <button class="btn btn-primary mt-4" @click="addSetting">Update Setting</button>
      </div>
    </main>
  </template>
  
  <script>
    export default {
      data(){
        return{
          config: {},
          ship: '',
          reg_point: '',
          sms_numbers: '',
        }
      },
      mounted() {
        this.getSetting()
      },
  
      methods: {
        getSetting(){
          const token = localStorage.getItem('info')
  
          this.config = {
            headers: {
              'Content-Type' : 'application/json',
              'Accept' : 'application/json',
              'Authorization' : 'Bearer '+token
            }
          }
  
          axios.get('/api/settings', this.config)
                .then(data => {
                    const setting = data.data.data
                    this.ship = setting.ship
                    this.reg_point = setting.reg_point
                    this.sms_numbers = setting.sms_numbers

                })
                .catch(err => console.log(err))
        },
  
        addSetting(){
          const token = localStorage.getItem('info')
  
          this.config = {
            headers: {
              'Content-Type' : 'application/json',
              'Accept' : 'application/json',
              'Authorization' : 'Bearer '+token
            }
          }
  
          axios.post('/api/settings', {ship: this.ship, reg_point: this.reg_point, sms_numbers: this.sms_numbers}, this.config)
                .then(data => {
                  this.getSetting()
                  console.log(data)
                })
                .catch(err => console.log(err))
        },
      }
  }
  </script>
  
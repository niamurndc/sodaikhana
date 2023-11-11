<template>
  <main>
    <div class="bg-primary text-white position-sticky sticky-top">
        <div class="container py-3 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <router-link to="/admin" class="text-white me-2"><i class="fa fa-arrow-left"></i></router-link>
                <h4 class="mb-0 pb-0">All Orders</h4>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="d-flex border-bottom justify-content-between align-items-center py-2" v-for="order in allorders" :key="order.id">
            <router-link class="text-decoration-none text-secondary" :to="`/admin/view-order/${order.id}`">
                <div>
                    <h6 class="mb-0 text-primary">ID: #{{order.id}}</h6>
                    <small class="d-block text-dark">Name: {{order.name}}</small>
                    <small class="d-block text-dark">Phone: {{order.phone}}</small>
                    <small class="d-block">Area: {{order.area}}</small>
                </div>
            </router-link>
            <div class="text-end">
                <h6><b>{{order.subtotal}}</b></h6>
                <small class="d-block">{{order.created}} BDT</small>
                <router-link class="text-primary fw-bold" :to="`/admin/order/send-sms/${order.id}`">Send SMS</router-link>
            </div>
        </div>
    </div>
  </main>
</template>

<script>
  export default {
    data(){
      return{
        allorders: [],
        config: {},
      }
    },
    mounted() {
      this.getOrder()
    },

    methods: {
      getOrder(){
        const token = localStorage.getItem('info')

        this.config = {
          headers: {
            'Content-Type' : 'application/json',
            'Accept' : 'application/json',
            'Authorization' : 'Bearer '+token
          }
        }

        axios.get('/api/orders', this.config)
              .then(data => {
                this.allorders = data.data.data
              })
              .catch(err => console.log(err))
      },

    }
  }
</script>

<template>
  <main>
    <div class="bg-primary text-white position-sticky sticky-top">
        <div class="container py-3 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <router-link to="/admin" class="text-white me-2"><i class="fa fa-arrow-left"></i></router-link>
                <h4 class="mb-0 pb-0">All Customers</h4>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="d-flex border-bottom justify-content-between align-items-center py-2" v-for="customer in allcustomers" :key="customer.id">

            <div>
                <h6 class="mb-0 text-primary">ID: #{{customer.id}}</h6>
                <small class="d-block text-dark">Name: {{customer.name}}</small>
                <small class="d-block text-dark">Phone: {{customer.phone}}</small>
            </div>
            <div class="text-end">
            </div>
        </div>
    </div>
  </main>
</template>

<script>
  export default {
    data(){
      return{
        allcustomers: [],
        config: {},
      }
    },
    mounted() {
      this.getCustomer()
    },

    methods: {
      getCustomer(){
        const token = localStorage.getItem('info')

        this.config = {
          headers: {
            'Content-Type' : 'application/json',
            'Accept' : 'application/json',
            'Authorization' : 'Bearer '+token
          }
        }

        axios.get('/api/customers', this.config)
              .then(data => {
                this.allcustomers = data.data.data
              })
              .catch(err => console.log(err))
      },
    }
}
</script>

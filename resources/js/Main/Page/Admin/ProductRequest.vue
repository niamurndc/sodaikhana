<template>
    <main>
      <div class="bg-primary text-white position-sticky sticky-top">
          <div class="container py-3 d-flex justify-content-between align-items-center">
              <div class="d-flex align-items-center">
                  <router-link to="/admin" class="text-white me-2"><i class="fa fa-arrow-left"></i></router-link>
                  <h4 class="mb-0 pb-0">Product Request</h4>
              </div>
          </div>
      </div>
  
      <div class="container">
  
          <div class="border-bottom py-2" v-for="product in allproducts.data" :key="product.id">
            <h6 class="text-primary">{{ product.name }}</h6>
            <h6 class="text-danger">{{ product.phone }}</h6>
            <p class="mb-0 pb-0">Product: {{ product.product.title }} - Price: {{ product.product.price }}</p>
            <small class="mb-0 pb-0">Time: {{ product.created }}</small>
          </div>
  

          
      </div>
    </main>
  </template>
  
  <script>
  
    export default {
      components: {
      },
      data(){
        return{
          allproducts: [],
          config: {},
        }
      },
  
      mounted() {
        this.getProducts()
      },
  
      methods: {
        getProducts(){
            const token = localStorage.getItem('info')

            this.config = {
                headers: {
                    'Content-Type' : 'application/json',
                    'Accept' : 'application/json',
                    'Authorization' : 'Bearer '+token
                }
            }
            axios.get('/api/requests', this.config)
                .then(data => {
                    console.log(data)
                  this.allproducts = data.data
                })
                .catch(err => console.log(err))
        },
  

  


      }
    }
  </script>
  
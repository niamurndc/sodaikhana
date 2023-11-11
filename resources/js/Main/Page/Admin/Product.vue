<template>
  <main>
    <div class="bg-primary text-white position-sticky sticky-top">
        <div class="container py-3 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <router-link to="/admin" class="text-white me-2"><i class="fa fa-arrow-left"></i></router-link>
                <h4 class="mb-0 pb-0">All Products</h4>
            </div>
        </div>
    </div>

    <div class="container">
        <input type="text" v-model="search" placeholder="Type product name" class="form-control mt-3 mb-2" @keyup="searchProduct">

        <div class="d-flex border-bottom justify-content-between align-items-center py-2" v-for="product in allproducts.data" :key="product.id">
            <router-link class="d-flex align-items-center text-decoration-none text-secondary" :to="`/admin/edit-product/${product.id}`">
                <div style="width: 30px; height: 30px;">
                    <img :src="product.image" alt="pro" class="admin-pro-image">
                </div>
                <div>
                    <h6 class="mb-0">{{product.title}}</h6>
                    <small>Stock: {{product.qty}}</small>
                </div>
            </router-link>
            <div>
                <h6 class="fw-bold" @click="showPriceEdit(product.id, product.price)">{{product.price}}</h6>
                <input v-if="showPrice == true && editId == product.id" type="number" v-model="price" style="width: 50px;">
            </div>
        </div>

        <div class="overflow-auto mt-4">
          <Bootstrap5Pagination
              :data="allproducts"
              @pagination-change-page="getProducts"
          />
        </div>
        
    </div>
  </main>
</template>

<script>
import { Bootstrap5Pagination } from 'laravel-vue-pagination';

  export default {
    components: {
      Bootstrap5Pagination
    },
    data(){
      return{
        allproducts: [],
        config: {},
        price: 0,
        editId: 0,
        showPrice: false
      }
    },

    mounted() {
      this.getProducts()
    },

    methods: {
      getProducts(page = 1){
        axios.get('/api/products?page='+page)
              .then(data => {
                this.allproducts = data.data
              })
              .catch(err => console.log(err))
      },

      searchProduct(){
            if(this.search.length > 2){
                axios.get(`/api/product/search/${this.search}`)
                    .then(res => {
                        this.allproducts = res.data
                    })
                    .catch(err => console.log(err))
            }
        },

      editproduct(id){
        this.$router.push({ path: `/admin/editproduct/${id}` })
      },

      showPriceEdit(id, price){
        if(this.showPrice){
          const token = localStorage.getItem('info')

          this.config = {
            headers: {
              'Content-Type' : 'application/json',
              'Accept' : 'application/json',
              'Authorization' : 'Bearer '+token
            }
          }
          
          axios.post(`/api/product/edit-price/${id}`, {price: this.price}, this.config)
            .then(data => {
              this.allproducts.data.forEach((product) => {
                if(product.id == id){
                  product.price = this.price
                }
              })

              this.editId = 0
              this.price = this.price
              this.showPrice = false
            })
            .catch(err => console.log(err))
          }else{
            this.editId = id
            this.price = 0
            this.showPrice = true
          }
        
      },
    }
  }
</script>

<style>
.admin-pro-image{
  height: 30px;
  width: 30px;
}
</style>

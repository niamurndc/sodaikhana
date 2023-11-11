<template>
  <main>
    <div class="bg-primary text-white position-sticky sticky-top">
        <div class="container py-3 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <router-link to="/admin" class="text-white me-2"><i class="fa fa-arrow-left"></i></router-link>
                <h4 class="mb-0 pb-0">Stock</h4>
            </div>
        </div>
    </div>

    <div class="container">
        <input type="text" v-model="search" placeholder="Type product name" class="form-control mt-3 mb-2" @keyup="searchProduct">

        <div class="d-flex border-bottom justify-content-between align-items-center py-2" v-for="product in allproducts.data" :key="product.id">
            <div class="d-flex align-items-center">
                <div style="width: 30px">
                    <img :src="product.image" alt="pro" class="admin-pro-image">
                </div>
                <div>
                    <h6 class="mb-0">{{product.title}}</h6>
                    <small>Price: {{product.price}} BDT</small>
                </div>
            </div>
            <div>
                <h6 class="fw-bold" @click="showQtyEdit(product.id, product.qty)">{{product.qty}}</h6>
                <input v-if="showqty == true && editid == product.id" type="number" v-model="qty" style="width: 50px;">
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
        qty: 0,
        editid: 0,
        showqty: false
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

      editproduct(id){
        this.$router.push({ path: `/admin/editproduct/${id}` })
      },

      showQtyEdit(id, qty){
        if(this.showqty){
          const token = localStorage.getItem('info')

          this.config = {
            headers: {
              'Content-Type' : 'application/json',
              'Accept' : 'application/json',
              'Authorization' : 'Bearer '+token
            }
          }
          
          axios.post(`/api/product/edit-qty/${id}`, {qty: this.qty}, this.config)
            .then(data => {
              console.log(data)
              this.allproducts.data.forEach((product) => {
                if(product.id == id){
                  product.qty = this.qty
                }
              })

              this.editid = 0
              this.qty = this.qty
              this.showqty = false
            })
            .catch(err => console.log(err))
          }else{
            this.editid = id
            this.qty = 0
            this.showqty = true
          }
        
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

      deleteProduct(id){
        const token = localStorage.getItem('info')

        this.config = {
          headers: {
            'Content-Type' : 'application/json',
            'Accept' : 'application/json',
            'Authorization' : 'Bearer '+token
          }
        }
        
        axios.delete(`/api/product/delete/${id}`, this.config)
          .then(data => {
            this.$toasted.success(data.data.message, {
                duration: 3000,
              });
            this.getProducts()
          })
          .catch(err => console.log(err))
      }
    }
  }
</script>


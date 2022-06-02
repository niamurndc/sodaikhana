<template>
  <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
      <h1 class="h2">Products</h1>
      <div class="btn-toolbar mb-2 mb-md-0">
        <router-link to="/admin/addproduct" class="btn btn-sm btn-success">
          <i class="fa fa-plus"></i>Add New
        </router-link>
      </div>
    </div>

    
    <div class="row mb-4">
      <div class="col-md-6"><h3>All Products</h3></div>
      <div class="col-md-6 text-right">
        <div class="row">
          <input @keyup="searchProductsId" type="number" v-model="pid" class="form-control col-md-6" placeholder="Search ID..">
          <input @keyup="searchProducts" type="text" v-model="search" class="form-control col-md-6" placeholder="Search product....">
        </div>
      </div>
    </div>
    <div class="table-responsive">
      <table class="table table-striped table-sm">
        <thead>
          <tr>
            <th>#</th>
            <th>Image</th>
            <th>Title</th>
            <th>Price</th>
            <th>Attribute</th>
            <th>Brand</th>
            <th>Categories</th>
            <th>Option</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="product in allproducts" :key="product.id">
            <td>{{product.id}}</td>
            <td class="text-center"><img :src="product.image" alt="product image" class="rounded" height="30px" width="30px"></td>
            <td><router-link class="text-dark" :to="{ path: `/admin/editproduct/${product.id}` }">{{product.title}}</router-link></td>
            <td><strike>{{product.offprice}}</strike> {{product.price}}</td>
            <td>{{product.attri}}</td>
            <td>{{product.brand.title}}</td>
            <td>
              <p v-for="cat in product.category" :key="cat.id" class="m-0">{{cat.title}}</p>
            </td>
            <td>
              <p v-if="product.offer == 'on'" class="m-0 text-primary">Offer</p>
              <p v-if="product.popular == 'on'" class="m-0 text-success">Popular</p>
              <p v-if="product.stock == 'on'" class="m-0 text-danger">Stock Out</p>
            </td>
            <td><i v-on:click="deleteProduct(product.id)" class="fa fa-trash text-danger"></i></td>
          </tr>
        </tbody>
      </table>
    </div>
    <nav aria-label="Page navigation example">
      <ul class="pagination">
        <li class="page-item"><span class="page-link" @click="paginate(1)">1</span></li>

        <!-- page 2 -->

        <li class="page-item"><span class="page-link" @click="paginate(links.current_page - 1)">{{links.current_page - 1}}</span></li>

        <!-- link 3 -->

        <li class="page-item active"><span class="page-link" @click="paginate(links.current_page)">{{links.current_page}}</span></li>

        <!-- link 4 -->

        <li class="page-item"><span class="page-link" @click="paginate(links.current_page + 1)">{{links.current_page + 1}}</span></li>


        <li class="page-item"><span class="page-link" v-on:click="paginate(links.last_page)">{{links.last_page}}</span></li>
      </ul>
    </nav>
  </main>
</template>

<script>
  export default {
    data(){
      return{
        allproducts: [],
        config: {},
        page: 1,
        links: [],
        search: '',
        pid: 0
      }
    },

    mounted() {
      
      this.getProducts(this.page)
    },

    methods: {
      paginate(page){
        console.log(page)
        this.page = page
        this.getProducts(this.page)
      },

      getProducts(page){
        axios.get(`/api/products?page=${page}`)
              .then(data => {
                this.allproducts = data.data.data
                this.links = data.data.meta
              })
              .catch(err => console.log(err))
      },

      searchProducts(){
        if(this.search.length > 3){
          axios.get(`/api/product/find/${this.search}`)
                .then(data => {
                  this.allproducts = data.data.data
                })
                .catch(err => console.log(err))
        }
      },

      searchProductsId(){
        axios.get(`/api/product/findid/${this.pid}`)
              .then(data => {
                this.allproducts = data.data.data
              })
              .catch(err => console.log(err))
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

<style scoped>
.page-link{
  cursor: pointer;
}
</style>


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

    <h3>All Products</h3>
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
            <td>{{product.title}}</td>
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
            <td><i v-on:click="editproduct(product.id)" class="fa fa-pencil text-primary mr-2"></i><i v-on:click="deleteProduct(product.id)" class="fa fa-trash text-danger"></i></td>
          </tr>
        </tbody>
      </table>
    </div>
  </main>
</template>

<script>
  export default {
    data(){
      return{
        allproducts: [],
        config: {}
      }
    },

    mounted() {
      this.getProducts()
    },

    methods: {
      getProducts(){
        axios.get('/api/products')
              .then(data => {
                this.allproducts = data.data.data
              })
              .catch(err => console.log(err))
      },

      editproduct(id){
        this.$router.push({ path: `/admin/editproduct/${id}` })
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


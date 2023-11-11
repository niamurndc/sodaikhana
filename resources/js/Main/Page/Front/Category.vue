<template>
  <div class="category">
    <div class="popular-section text-center py-5">
      <h2 class="text-success fw-bold fs-4">Category: {{cat_title}}</h2>
      <div class="container pt-5">
        <div class="row">
          <div v-for="popular in popularproducts" :key="popular.id" class="col-md-3 col-sm-4 col-6 p-3 text-center border">
            <SingleProduct :product="popular" v-on:updateCart="updateCart" />
          </div>    
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import SingleProduct from '@/Main/Page/Front/SingleProduct.vue'
  export default {
    components: {
      SingleProduct
    },

    data(){
      return{
        popularproducts: [],
        catid: this.$route.params.id,
        cat_title: ''
      }
    },

    created(){
      this.getPopular(this.catid)
    },

    beforeRouteUpdate (to, from, next) {
      this.getPopular(to.params.id);
      next();
    },

    methods: {

      getPopular(id){
        axios.get(`/api/showcategory/${id}`)
          .then(res => {
            console.log(res.data.data)
            this.popularproducts = res.data.data.products
            this.cat_title = res.data.data.title
          })
          .catch(err => console.log(err))
      },

      updateCart(){
        this.$emit('updateCart')
      }
    }
  }
</script>

<style scoped>
  .show-section{
    background-color: #289672;
    color: #fff;
  }

  .text-pink{
    color: #E6DD3B;
  }


</style>
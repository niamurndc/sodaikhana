<template>
  <div class="category">
    <div class="popular-section text-center py-5">
      <h2>Search Result</h2>
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
  import SingleProduct from './SingleProduct.vue'
  export default {
    components: {
      SingleProduct
    },



    data(){
      return{
        popularproducts: [],
        search: this.$route.params.search
      }
    },

    mounted(){
      this.getPopular(this.search)
    },

    beforeRouteUpdate (to, from, next) {
      this.getPopular(to.params.search);
      next();
    },

    methods: {

      getPopular(search){
        console.log(search)
        axios.get(`/api/product/search/${search}`)
          .then(res => {
            
            this.popularproducts = res.data.data
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
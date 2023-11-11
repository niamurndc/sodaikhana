<template>
  <div>
    <div class="story-container text-left">
    <div class="container my-4 align-items-center flex-no-wrap story-content px-1" :style="{width: 114 * allstorys.length}">
      <div class="story" v-for="story in allstorys" :key="story.id">
        <router-link :to="story.link">
        <img :src="story.image" class="story-image">
        </router-link>
      </div>
    </div>
    </div>

    <div class="popular-section text-center py-5">
      <h2 class="text-success fw-bold fs-4">Offer Products</h2>
      <div class="container pt-5">
        <div class="row">
          <div v-for="popular in offerproducts" :key="popular.id" class="col-md-3 col-sm-4 col-6 p-3 text-center border">
            <SingleProduct :product="popular" v-on:updateCart="updateCart" />
          </div>    
        </div>
      </div>
    </div>


    <div class="popular-section text-center py-5">
      <h2 class="text-success fw-bold fs-4">Popular Products</h2>
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
        allstorys: [],
        popularproducts: [],
        offerproducts: []
      }
    },
    mounted() {
        console.log('Component mounted.')
        this.getstorys()
        this.getPopular()
        this.getOffer()
    },

    methods: {
      getstorys(){
        axios.get('/api/storys')
          .then(res => {
            this.allstorys = res.data.data
            console.log(this.allstorys)
          })
          .catch(err => console.log(err))
      },

      getPopular(){
        axios.get('/api/productpopular')
          .then(res => {
            this.popularproducts = res.data.data
          })
          .catch(err => console.log(err))
      },

      getOffer(){
        axios.get('/api/productoffer')
          .then(res => {
            this.offerproducts = res.data.data
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

  .story-container {
    width: 100%;
    overflow-x: scroll;
  }

  .story-content{
    white-space: nowrap;
    min-width: 100vw;
    display: flex;
    justify-content: start;
  }

  /* width */
  ::-webkit-scrollbar {
    height: 5px;
  }

  /* Track */
  ::-webkit-scrollbar-track {
    background: #f1f1f1;
  }

  /* Handle */
  ::-webkit-scrollbar-thumb {
    background: #888;
  }

  /* Handle on hover */
  ::-webkit-scrollbar-thumb:hover {
    background: #555;
  }

  .story{
    display: inline-block;
    width: 110px;
    height: 180px;
    background-color: #8a9a95;
    border-radius: 5px;
    margin-right: 4px;
  }

  .story-image{
    width: 110px;
    height: 180px;
    border-radius: 5px;
    object-fit: cover;
  }


</style>
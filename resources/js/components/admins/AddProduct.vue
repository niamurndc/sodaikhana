<template>
  <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
      <h1 class="h2">Product</h1>
      <div class="btn-toolbar mb-2 mb-md-0">
        <router-link to="/admin/product" class="btn btn-secondary">
          Discard
        </router-link>
        <button class="btn btn-success ml-2" v-on:click="createProduct"><i class="fa fa-arrow-up"></i> Publish</button>
      </div>
    </div>

    <h2>Add new product</h2>
    <div class="row">
      <div class="col-md-8">
        <div class="card p-3">
          <h4 class="text-primary">Product Details</h4>
          <div class="form-group">
            <label for="title">Title *</label>
            <input type="text" v-model="product.title" class="form-control">
          </div>
          <div class="form-group">
            <label for="attri">Attribute</label>
            <input type="text" v-model="product.attri" class="form-control">
          </div>
          <div class="form-group">
            <label for="price">Price *</label>
            <input type="number" v-model="product.price" class="form-control">
          </div>
          <div class="form-group">
            <label for="offprice">Offer Price</label>
            <input type="number" v-model="product.offprice" class="form-control">
          </div>
          <div class="form-group">
            <label for="image">Picture *</label>
            <input type="file" @change="fileUpload" class="form-control">
          </div>
        </div>

        <div class="card p-3 mt-4">
          <h4 class="text-primary">Product Variation</h4>
          <div class="row">
            <div class="form-group col-md-6">
              <label for="attri1">Attribute 1</label>
              <input type="text" v-model="product.attri1" class="form-control">
            </div>
            <div class="form-group col-md-6">
              <label for="val1">Value 1</label>
              <input type="text" v-model="product.val1" class="form-control">
            </div>
          </div>
          <div class="row">
            <div class="form-group col-md-6">
              <label for="attri2">Attribute 2</label>
              <input type="text" v-model="product.attri2" class="form-control">
            </div>
            <div class="form-group col-md-6">
              <label for="val2">Value 2</label>
              <input type="text" v-model="product.val2" class="form-control">
            </div>
          </div>
          <div class="row">
            <div class="form-group col-md-6">
              <label for="attri3">Attribute 3</label>
              <input type="text" v-model="product.attri3" class="form-control">
            </div>
            <div class="form-group col-md-6">
              <label for="val3">Value 3</label>
              <input type="text" v-model="product.val3" class="form-control">
            </div>
          </div>
        </div>

        <div class="card p-3 my-4">
          <h4 class="text-primary">Product Details</h4>
          <div class="form-group">
            <label for="desc">Description</label>
            <textarea v-model="product.desc" rows="4" class="form-control"></textarea>
          </div>
          <div class="form-group">
            <label for="tags">Tags *</label>
            <textarea v-model="product.tags" rows="4" class="form-control"></textarea>
          </div>
        </div>

      </div>
      <div class="col-md-4">
        <div class="card p-3">
          <h4 class="text-primary">Product Option</h4>
          <div class="form-group form-check">
            <input type="checkbox" v-model="product.offer" name="offer" value="on" class="form-check-input" id="offer">
            <label class="form-check-label" for="offer">Offer</label>
          </div>
          <div class="form-group form-check">
            <input type="checkbox" v-model="product.popular" name="popular" value="on" class="form-check-input" id="popular">
            <label class="form-check-label" for="popular">Popular</label>
          </div>
          <div class="form-group form-check">
            <input type="checkbox" v-model="product.stock_out" name="stock_out" value="on" class="form-check-input" id="stock_out">
            <label class="form-check-label" for="stock_out">Stock out</label>
          </div>
        </div>

        <Brand v-on:setBrand="setBrand" :brandtext="brandText"/>

        <Category :catid="category" v-on:getCategory="addCatItem" v-on:DeleteCategory="deleteCatItem"/>
      </div>
    </div>
  </main>
</template>

<script>
  import Brand from './Brand.vue'
  import Category from './Category.vue'
  export default {
    data(){
      return{
        image: '',
        product: {
          title: '',
          attri: '',
          price: '',
          offprice: '',
          desc: '',
          tags: '',
          brand_id: 0,
          attri1: '',
          val1: '',
          attri2: '',
          val2: '',
          attri3: '',
          val3: '',
          popular: false,
          offer: false,
          stock_out: false,
        },
        category: [],
        catid: [],
        brandText: '',
        config: {}
      }
    },

    components: {
      Brand,
      Category
    },
    mounted() {
    },
    
    methods: {
      addCatItem(item){
        this.category.push(item)
        this.catid.push(item.id)
      },

      deleteCatItem(id){
        this.category = this.category.filter(item => item.id != id)
        this.catid = this.catid.filter(item => item != id)
      },

      setBrand(brand){
        this.product.brand_id = brand.id
        this.brandText = brand.title
      },

      fileUpload(e){
        const file = e.target.files[0]
        this.image = file
      },

      createProduct(){
        if(this.product.title == '' || this.product.price == '' || this.product.tags == '' || this.product.brand_id == '' || this.image == ''){

          this.$toasted.error(" (*) fields are required", {
            duration: 3000,
          });

        }else{

          this.product.popular = this.product.popular ? 'on' : ''
          this.product.offer = this.product.offer ? 'on' : ''
          this.product.stock_out = this.product.stock_out ? 'on' : ''

          const fd = new FormData()
          fd.append('image', this.image)
          fd.append('title', this.product.title)
          fd.append('price', this.product.price)
          fd.append('offprice', this.product.offprice)
          fd.append('attri', this.product.attri)
          fd.append('attri1', this.product.attri1)
          fd.append('attri2', this.product.attri2)
          fd.append('attri3', this.product.attri3)
          fd.append('val1', this.product.val1)
          fd.append('val2', this.product.val2)
          fd.append('val3', this.product.val3)
          fd.append('desc', this.product.desc)
          fd.append('tags', this.product.tags)
          fd.append('popular', this.product.popular)
          fd.append('offer', this.product.offer)
          fd.append('stock_out', this.product.stock_out)
          fd.append('brand_id', this.product.brand_id)
          //this.catid.forEach(cat => fd.append(`${key}[]`, cat))
          //fd.append('category', this.catid)
          this.catid.forEach((cat) => {
            fd.append('category[]',cat)
          })

          const token = localStorage.getItem('info')

          this.config = {
            headers: {
              'Content-Type' : 'application/json',
              'Accept' : 'application/json',
              'Authorization' : 'Bearer '+token
            }
          }


          axios.post('/api/product/add', fd, this.config)
            .then(res => {
              this.$toasted.success("Added successful", {
                duration: 3000,
              });
              this.$router.push({ path: '/admin/product'})
            })
            .catch(err => console.log(err))
        }
      }
    }
  }
</script>

<style>
  .brand{
    max-height: 300px;
    overflow-y: scroll;
  }

  .cats{
    max-height: 300px;
    overflow-y: scroll;
  }
</style>

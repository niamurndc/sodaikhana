<template>
  <main>
    <div class="bg-primary text-white position-sticky sticky-top">
        <div class="container py-3 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <router-link to="/admin" class="text-white me-2"><i class="fa fa-arrow-left"></i></router-link>
                <h4 class="mb-0 pb-0">Edit Product</h4>
            </div>
            <button class="btn btn-warning text-dark" @click="createProduct">Publish</button>
        </div>
    </div>

    <div class="container">
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
              <img :src="product.image" alt="product old image" class="rounded mb-4" height="70px" width="70px"><br>
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
              <input type="checkbox" v-model="product.food" name="food" value="on" class="form-check-input" id="food">
              <label class="form-check-label" for="food">Food</label>
            </div>
            <div class="form-group form-check">
              <input type="checkbox" v-model="product.stock_out" name="stock_out" value="on" class="form-check-input" id="stock_out">
              <label class="form-check-label" for="stock_out">Stock out</label>
            </div>

            <div class="form-group">
              <label for="priority">Priority</label>
              <input type="number" v-model="product.priority" id="priority" class="form-control">
            </div>
          </div>

          <Brand v-on:setBrand="setBrand" :brandtext="brandText"/>

          <Category :catid="category" v-on:getCategory="addCatItem" v-on:DeleteCategory="deleteCatItem"/>
        </div>
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
        product: {},
        category: [],
        catid: [],
        brandText: '',
        product_id: this.$route.params.id,
        config: ''
      }
    },

    components: {
      Brand,
      Category
    },
    mounted() {
      this.getProdcut()
    },
    
    methods: {
      getProdcut(){
        const token = localStorage.getItem('info')

        this.config = {
          headers: {
            'Content-Type' : 'application/json',
            'Accept' : 'application/json',
            'Authorization' : 'Bearer '+token
          }
        }

        axios.get(`/api/product/${this.product_id}`, this.config)
          .then(res => {

            const pro = res.data.data
            this.product.title = pro.title
            this.product.price = pro.price
            this.product.offprice = pro.offprice == null ? '' : pro.offprice
            this.product.attri = pro.attri == null ? '' : pro.attri
            this.product.attri1 = pro.attri1 == null ? '' : pro.attri1
            this.product.attri2 = pro.attri2 == null ? '' : pro.attri2
            this.product.attri3 = pro.attri3 == null ? '' : pro.attri3
            this.product.val1 = pro.val1 == null ? '' : pro.val1
            this.product.val2 = pro.val2 == null ? '' : pro.val2
            this.product.val3 = pro.val3 == null ? '' : pro.val3
            this.product.desc = pro.desc == null ? '' : pro.desc
            this.product.priority = pro.priority == null ? '' : pro.priority
            this.product.tags = pro.tags
            this.product.brand_id = pro.brand.id
            this.brandText = pro.brand.title,
            
            this.product.image = pro.image,

            this.product.offer = pro.offer == 'on' ? true : false
            this.product.popular = pro.popular == 'on' ? true : false
            this.product.food = pro.food == 'on' ? true : false
            this.product.stock_out = pro.stock_out == 'on' ? true : false

            pro.category.forEach(cat => {
              let newCat = {
                id: cat.id,
                text: cat.title
              }

              this.category.push(newCat)
              this.catid.push(cat.id)
            })
          })
          .catch(err => console.log(err))
      },


      addCatItem(item){
        if(this.catid.includes(item.id)){
          this.$toasted.error("Already Added", {
            duration: 3000,
          });
        }else{
          this.category.push(item)
          this.catid.push(item.id)
        }
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
        if(this.product.title == '' || this.product.price == '' || this.product.tags == '' || this.product.brand_id == ''){

        }else{

          this.product.popular = this.product.popular ? 'on' : ''
          this.product.food = this.product.food ? 'on' : ''
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
          fd.append('priority', this.product.priority)
          fd.append('tags', this.product.tags)
          fd.append('popular', this.product.popular)
          fd.append('food', this.product.food)
          fd.append('offer', this.product.offer)
          fd.append('stock_out', this.product.stock_out)
          fd.append('brand_id', this.product.brand_id)

          this.catid.forEach((cat) => {
            fd.append('category[]',cat)
          })

          const token = localStorage.getItem('info')

          this.config = {
            headers: {
              'Content-Type' : 'multipart/form-data',
              'Accept' : 'application/json',
              'Authorization' : 'Bearer '+token
            }
          }

          axios.post(`/api/product/edit/${this.product_id}`, fd, this.config)
            .then(res => {
              this.$router.push({ path: '/admin/products'})
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

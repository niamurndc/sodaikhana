<template>
  <div class="card p-3 mt-4">
    <h4 class="text-primary">Select Brand * </h4>
    <input type="text" :value="brandtext" class="form-control mb-3" readonly>
    <div class="border p-2 mb-4 brand">
      <p v-for="brand in allbrands" :key="brand.id" v-on:click="setBrand(brand.id, brand.title)" class="alert alert-secondary mb-1 p-1 d-flex justify-content-between">{{brand.title}} <span><i class="fa fa-pencil text-primary border" v-on:click="editBrand(brand.id, brand.title)"></i><i class="fa fa-trash text-danger border ml-2" v-on:click="deleteBrand(brand.id)"></i></span> </p>
    </div>

    <h6>Add new brand</h6>
    <input type="text" v-model="brand_title" class="form-control">
    <button v-if="edit" class="btn btn-primary mt-3" v-on:click="addBrand"><i class="fa fa-plus"></i> Add Brand</button>
    <button v-else class="btn btn-primary mt-3" v-on:click="updateBrand"><i class="fa fa-pencil"></i> Update Brand</button>
    
  </div>
</template>

<script>

export default {
  props: ['brandtext'],
  data(){
    return{
      allbrands: [],
      brand_title: '',
      brandid: 0,
      edit: true,
      editid: 0,
      config: {}
    }
  },

  mounted(){
    this.getBrands()
  },

  methods: {
    getBrands(){
      const token = localStorage.getItem('info')

      this.config = {
        headers: {
          'Content-Type' : 'application/json',
          'Accept' : 'application/json',
          'Authorization' : 'Bearer '+token
        }
      }

      axios.get('/api/brands', this.config)
            .then(data => {
              this.allbrands = data.data.data
            })
            .catch(err => console.log(err))
    },

    setBrand(id, title){
      this.brandid = id
      const newBrand = {
        id: id,
        title: title
      }
      this.$emit('setBrand', newBrand)
    },

    addBrand(){
      const token = localStorage.getItem('info')

      this.config = {
        headers: {
          'Content-Type' : 'application/json',
          'Accept' : 'application/json',
          'Authorization' : 'Bearer '+token
        }
      }

      axios.post('/api/brand/add', {title: this.brand_title}, this.config)
            .then(data => {

              this.brand_title = ''
              this.getBrands()
            })
            .catch(err => console.log(err))
    },

    editBrand(id, title){
      this.edit = false
      this.editid = id
      this.brand_title = title
    },

    updateBrand(){
      const token = localStorage.getItem('info')

      this.config = {
        headers: {
          'Content-Type' : 'application/json',
          'Accept' : 'application/json',
          'Authorization' : 'Bearer '+token
        }
      }

      axios.post(`/api/brand/edit/${this.editid}`, {title: this.brand_title}, this.config)
            .then(data => {
              this.brand_title = ''
              this.edit = true
              this.editid = 0
              this.getBrands()
            })
            .catch(err => console.log(err))
    },

    deleteBrand(id){
      const token = localStorage.getItem('info')

      this.config = {
        headers: {
          'Content-Type' : 'application/json',
          'Accept' : 'application/json',
          'Authorization' : 'Bearer '+token
        }
      }

      axios.delete(`/api/brand/delete/${id}`, this.config)
            .then(data => {
              this.getBrands()
            })
            .catch(err => console.log(err))
    }
  }

  
}
</script>

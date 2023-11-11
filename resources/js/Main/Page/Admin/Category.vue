<template>
  <div class="card p-3 mt-4">
    <h4 class="text-primary">Select Category</h4>
    <div class="d-flex flex-wrap my-3">
      <span class="border p-1 ml-1 mb-1" v-for="text in catid" :key="text.id">{{text.text}} <i class="fa fa-times ml-3" v-on:click="deleteItem(text.id)"></i></span>
    </div>
      
    <div class="border p-2 mb-4 cats">
      <p v-for="cat in allcats" :key="cat.id" class="alert alert-secondary mb-1 p-1 d-flex justify-content-between">
        <span v-on:click="setCat(cat.id, cat.title)">
          {{cat.title}} - ({{cat.parent}})
        </span>
        <span>
          <i class="fa fa-pencil text-primary border" v-on:click="editCat(cat.id, cat.title, cat.parent)"></i>
          <i class="fa fa-trash text-danger border ml-2" v-on:click="deleteCat(cat.id)"></i>
        </span>
      </p>
    </div>

    <h6>Add new category</h6>
    <input type="text" v-model="cat_title" class="form-control">
    <select v-model="cat_parent" class="form-control mt-3">
      <option v-for="cat in allcats" :key="cat.id" :value="cat.id">{{cat.title}}</option>
    </select>
    <button v-if="edit" class="btn btn-primary mt-3" v-on:click="addCat"><i class="fa fa-plus"></i> Add Category</button>
    <button v-else class="btn btn-primary mt-3" v-on:click="updateCat"><i class="fa fa-pencil"></i> Update Category</button>
  </div>
</template>

<script>
export default {
  props: ['catid'],
  data(){
    return{
      allcats: [],
      cat_title: '',
      cat_parent: null,
      catText: '',
      edit: true,
      editid: 0,
      newcatlist: [],
      config: {}
    }
  },
  mounted() {
    this.getCats()
  },

  methods: {
    getCats(){
      axios.get('/api/categories')
            .then(data => {
              this.allcats = data.data.data
            })
            .catch(err => console.log(err))
    },

    setCat(id, title){
      if(this.newcatlist.includes(id)){
      }else{
        this.newcatlist.push(id)
        const newcat = {
          id: id,
          text: title
        }
        this.$emit('getCategory', newcat)
      }
    },

    deleteItem(id){
      this.$emit('DeleteCategory', id)
    },

    addCat(){
      const token = localStorage.getItem('info')

      this.config = {
        headers: {
          'Content-Type' : 'application/json',
          'Accept' : 'application/json',
          'Authorization' : 'Bearer '+token
        }
      }

      axios.post('/api/category/add', {title: this.cat_title, parent: this.cat_parent}, this.config)
            .then(data => {

              this.cat_title = ''
              this.cat_parent = null
              this.getCats()
            })
            .catch(err => console.log(err))
    },

    editCat(id, title, parent){
      this.edit = false
      this.editid = id
      this.cat_title = title
      this.cat_parent = parent
    },

    updateCat(){
      const token = localStorage.getItem('info')

      this.config = {
        headers: {
          'Content-Type' : 'application/json',
          'Accept' : 'application/json',
          'Authorization' : 'Bearer '+token
        }
      }

      axios.post(`/api/category/edit/${this.editid}`, {title: this.cat_title, parent: this.cat_parent}, this.config)
            .then(data => {

              this.cat_title = ''
              this.cat_parent = null
              this.edit = true
              this.editid = 0
              this.getCats()
            })
            .catch(err => console.log(err))
    },

    deleteCat(id){
      const token = localStorage.getItem('info')

      this.config = {
        headers: {
          'Content-Type' : 'application/json',
          'Accept' : 'application/json',
          'Authorization' : 'Bearer '+token
        }
      }

      axios.delete(`/api/category/delete/${id}`, this.config)
            .then(data => {
              this.getCats()
            })
            .catch(err => console.log(err))
    }
  }
}
</script>
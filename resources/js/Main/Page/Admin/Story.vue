<template>
    <main>
      <div class="bg-primary text-white position-sticky sticky-top">
          <div class="container py-3 d-flex justify-content-between align-items-center">
              <div class="d-flex align-items-center">
                  <router-link to="/admin" class="text-white me-2"><i class="fa fa-arrow-left"></i></router-link>
                  <h4 class="mb-0 pb-0">All Storys</h4>
              </div>
          </div>
      </div>
    <div class="container p-3 mt-4">
      <h4 class="text-primary">Story</h4>
      <div class="row mx-0 mb-4">
        <div class="col-4 col-md-1 border bg-secondary px-0" v-for="story in allstorys" :key="story.id">
            <img :src="story.image" class="w-100">
            <div class="p-1 d-flex justify-content-between">
                {{story.link}}
                <span class="btn btn-danger" v-on:click="deleteBrand(story.id)"><i class="fa fa-trash" ></i></span>
            </div>
        </div>
      </div>
  
      <h6>Add Story</h6>
      <div class="row">
        <div class="col-md-6 mb-3">
            <input type="file" @change="fileUpload" class="form-control">
        </div>
        <div class="col-md-6 mb-3">
            <input type="text" v-model="story_link" class="form-control">
        </div>
      </div>
      
      <button class="btn btn-primary" v-on:click="addStory"><i class="fa fa-plus"></i> Add Story</button>
      
    </div>
  </main>
</template>
  
  <script>
  
  export default {
    props: ['brandtext'],
    data(){
      return{
        image: '',
        allstorys: [],
        story_link: '',
        config: {}
      }
    },
  
    mounted(){
      this.getStorys()
    },
  
    methods: {
      fileUpload(e){
        this.image = e.target.files[0]
      },

      getStorys(){
        const token = localStorage.getItem('info')
  
        this.config = {
          headers: {
            'Content-Type' : 'application/json',
            'Accept' : 'application/json',
            'Authorization' : 'Bearer '+token
          }
        }
  
        axios.get('/api/storys', this.config)
              .then(data => {
                this.allstorys = data.data.data
              })
              .catch(err => console.log(err))
      },
  

  
      addStory(){
        const token = localStorage.getItem('info')
  
        this.config = {
          headers: {
            'Content-Type' : 'multipart/form-data',
            'Accept' : 'application/json',
            'Authorization' : 'Bearer '+token
          }
        }

        let fd = new FormData()
        fd.append('image', this.image)
        fd.append('link', this.story_link)
  
        axios.post('/api/story/add', fd, this.config)
              .then(data => {
  
                this.story_title = ''
                this.getStorys()
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
  
        axios.delete(`/api/story/delete/${id}`, this.config)
              .then(data => {
                this.getStorys()
              })
              .catch(err => console.log(err))
      }
    }
  
    
  }
  </script>
  
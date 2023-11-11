<template>
  <main>
    <div class="bg-primary text-white position-sticky sticky-top">
        <div class="container py-3 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <router-link to="/admin" class="text-white me-2"><i class="fa fa-arrow-left"></i></router-link>
                <h4 class="mb-0 pb-0">All Areas</h4>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row mt-3 mb-4">
            <div class="col-10"><input type="text" v-model="title" class="form-control" placeholder="Add new area"></div>
            <div class="col-2"><button class="btn btn-primary" @click="addArea"><i class="fa fa-plus-circle"></i></button></div>
        </div>

        <div class="d-flex border-bottom justify-content-between align-items-center py-2" v-for="area in allareas" :key="area.id">

            <div>
                <p class="mb-0 d-block text-dark">{{area.title}}</p>
            </div>
            <div class="text-end">
               <button class="btn btn-danger btn-sm" @click="deleteArea(area.id)"><i class="fa fa-trash"></i></button>
            </div>
        </div>
    </div>
  </main>
</template>

<script>
  export default {
    data(){
      return{
        allareas: [],
        config: {},
        title: '',
      }
    },
    mounted() {
      this.getAreas()
    },

    methods: {
      getAreas(){
        const token = localStorage.getItem('info')

        this.config = {
          headers: {
            'Content-Type' : 'application/json',
            'Accept' : 'application/json',
            'Authorization' : 'Bearer '+token
          }
        }

        axios.get('/api/areas', this.config)
              .then(data => {
                this.allareas = data.data.data
              })
              .catch(err => console.log(err))
      },

      addArea(){
        const token = localStorage.getItem('info')

        this.config = {
          headers: {
            'Content-Type' : 'application/json',
            'Accept' : 'application/json',
            'Authorization' : 'Bearer '+token
          }
        }

        axios.post('/api/area/add', {title: this.title}, this.config)
              .then(data => {
                this.getAreas()
                this.title = ''
              })
              .catch(err => console.log(err))
      },

      deleteArea(id){
        const token = localStorage.getItem('info')

        this.config = {
          headers: {
            'Content-Type' : 'application/json',
            'Accept' : 'application/json',
            'Authorization' : 'Bearer '+token
          }
        }

        axios.delete(`/api/area/delete/${id}`, this.config)
              .then(data => {
                this.getAreas()
              })
              .catch(err => console.log(err))
      }
    }
}
</script>

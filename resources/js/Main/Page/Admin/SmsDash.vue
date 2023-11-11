<template>
  <main>
    <div class="bg-primary text-white position-sticky sticky-top">
        <div class="container py-3 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <router-link to="/admin" class="text-white me-2"><i class="fa fa-arrow-left"></i></router-link>
                <h4 class="mb-0 pb-0">All SMS List</h4>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row mt-3 mb-4">
            <div class="col-10"><input type="text" v-model="title" class="form-control"></div>
            <div class="col-2"><button class="btn btn-primary" @click="addList"><i class="fa fa-plus-circle"></i></button></div>
        </div>

        <div class="bg-primary text-light p-2">
            <router-link class="text-decoration-none text-light" :to="`/admin/send-sms/0`">
                <p class="d-block text-light fw-bold mb-0">Send SMS to Customer</p>
            </router-link>
        </div>
        <div class="d-flex border-bottom justify-content-between align-items-center py-2" v-for="list in alllists" :key="list.id">
            <router-link class="text-decoration-none text-secondary" :to="`/admin/sms-list/${list.id}`">
                <p class="d-block text-dark fw-bold mb-0">{{list.title}}</p>
            </router-link>
        </div>
    </div>
  </main>
</template>

<script>
  export default {
    data(){
      return{
        alllists: [],
        config: {},
        title: ''
      }
    },
    mounted() {
      this.getList()
    },

    methods: {
      getList(){
        const token = localStorage.getItem('info')

        this.config = {
          headers: {
            'Content-Type' : 'application/json',
            'Accept' : 'application/json',
            'Authorization' : 'Bearer '+token
          }
        }

        axios.get('/api/sms-lists', this.config)
              .then(data => {
                this.alllists = data.data.data
                
              })
              .catch(err => console.log(err))
      },

      addList(){
        const token = localStorage.getItem('info')

        this.config = {
          headers: {
            'Content-Type' : 'application/json',
            'Accept' : 'application/json',
            'Authorization' : 'Bearer '+token
          }
        }

        axios.post('/api/sms-list/add', {title: this.title}, this.config)
              .then(data => {
                this.getList()
                this.title = ''
              })
              .catch(err => console.log(err))
      }
    }
  }
</script>

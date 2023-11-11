<template>
  <main>
    <div class="bg-primary text-white position-sticky sticky-top">
        <div class="container py-3 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <router-link to="/admin" class="text-white me-2"><i class="fa fa-arrow-left"></i></router-link>
                <h4 class="mb-0 pb-0">{{list.title}}</h4>
            </div>
            <router-link :to="`/admin/send-sms/${list.id}`" class="btn btn-warning text-dark" >Send SMS</router-link>
        </div>
    </div>

    <div class="container">
        <h6 class="text-primary mt-3 fw-bold">Search Customer</h6>
        <div class="position-relative">
            <input type="text" v-model="search" placeholder="Type customer name" class="form-control" @keyup="getUser">

            <div v-if="users != []" class="position-relative bottom-0 left-0 w-100">
                <div v-for="user in users" :key="user.id" class="border-bottom p-1"><div @click="addCustomer(user)">{{user.name}} -- {{user.phone}}</div></div>
            </div>
        </div>

        <h6 class="text-primary mt-3 fw-bold">Add customer Customer</h6>
        <div class="row">
          <div class="col-md-5">
            <input type="text" v-model="name" placeholder="Name" class="form-control">
          </div>
          <div class="col-md-5">
            <input type="text" v-model="phone" placeholder="Phone" class="form-control">
          </div>
          <div class="col-md-2">
            <button class="btn btn-primary" @click="addNewCustomer">Submit</button>
          </div>
        </div>
        <div class="d-flex border-bottom justify-content-between align-items-center py-2 mt-3" v-for="item in list.items" :key="item.id">
            <div>
                <small class="d-block text-dark fw-bold">{{item.name}}</small>
                <small class="d-block text-dark">{{item.phone}}</small>
            </div>
            <div>
                <button class="btn btn-danger" @click="deleteItem(item.id)"><i class="fa fa-trash"></i></button>
            </div>
        </div>

        <div class="mt-4 text-center">
            <button @click="deleteList" class="btn btn-primary btn-lg"> Delete Entire List</button>
        </div>
    </div>
  </main>
</template>

<script>
  export default {
    data(){
      return{
        users: [],
        config: {},
        list: {},
        title: '',
        listId: this.$route.params.id,
        search: '',
        name: '',
        phone: '',
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

        axios.get(`/api/sms-list/${this.listId}`, this.config)
              .then(data => {
                this.list = data.data.data
                
              })
              .catch(err => console.log(err))
      },

      getUser(){
        const token = localStorage.getItem('info')

        this.config = {
          headers: {
            'Content-Type' : 'application/json',
            'Accept' : 'application/json',
            'Authorization' : 'Bearer '+token
          }
        }

        axios.get(`/api/customer/search/${this.search}`, this.config)
              .then(data => {
                this.users = data.data.data
                
              })
              .catch(err => console.log(err))
      },

      addCustomer(user){
        const token = localStorage.getItem('info')

        this.config = {
          headers: {
            'Content-Type' : 'application/json',
            'Accept' : 'application/json',
            'Authorization' : 'Bearer '+token
          }
        }

        axios.post('/api/sms-item/add', {name: user.name, phone: user.phone, list_id: this.listId}, this.config)
              .then(data => {
                this.getList()
                this.users = []
                this.search = ''
              })
              .catch(err => console.log(err))
      },

      addNewCustomer(){
        const token = localStorage.getItem('info')

        this.config = {
          headers: {
            'Content-Type' : 'application/json',
            'Accept' : 'application/json',
            'Authorization' : 'Bearer '+token
          }
        }

        axios.post('/api/sms-item/add', {name: this.name, phone: this.phone, list_id: this.listId}, this.config)
              .then(data => {
                this.getList()
                this.users = []
                this.search = ''
              })
              .catch(err => console.log(err))
      },

      deleteItem(id){
        const token = localStorage.getItem('info')

        this.config = {
          headers: {
            'Content-Type' : 'application/json',
            'Accept' : 'application/json',
            'Authorization' : 'Bearer '+token
          }
        }

        axios.delete(`/api/sms-item/delete/${id}`, this.config)
              .then(data => {
                this.getList()
                this.users = []
                this.search = ''
              })
              .catch(err => console.log(err))
      },

      deleteList(){
        const token = localStorage.getItem('info')

        this.config = {
          headers: {
            'Content-Type' : 'application/json',
            'Accept' : 'application/json',
            'Authorization' : 'Bearer '+token
          }
        }

        axios.delete(`/api/sms-list/delete/${this.listId}`, this.config)
              .then(data => {
                this.$router.push({path: '/admin'})
              })
              .catch(err => console.log(err))
      }
    }
  }
</script>

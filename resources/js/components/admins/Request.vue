<template>
  <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
      <h1 class="h2">Request</h1>
      <div class="btn-toolbar mb-2 mb-md-0">

      </div>
    </div>

    <h3>Product Request</h3>
    <div class="table-responsive">
      <table class="table table-striped table-sm">
        <thead>
          <tr>
            <th>#</th>
            <th>Name</th>
            <th>Phone</th>
            <th>Product Name</th>
            <th>Price</th>
            <th>Time</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="request in allrequests" :key="request.id">
            <td>{{request.id}}</td>
            <td>{{request.name}}</td>
            <td>{{request.phone}}</td>
            <td>{{request.product.title}}</td>
            <td>{{request.product.price}}</td>
            <td>{{request.created}}</td>
            <td><i v-on:click="deleteRequest(request.id)" class="fa fa-trash text-danger"></i></td>
          </tr>
        </tbody>
      </table>
    </div>
  </main>
</template>

<script>
  export default {
    data(){
      return{
        allrequests: [],
        config: {}
      }
    },

    mounted() {
      this.getRequest()
    },

    methods: {
      getRequest(){
        const token = localStorage.getItem('info')

        this.config = {
            headers: {
                'Content-Type' : 'application/json',
                'Accept' : 'application/json',
                'Authorization' : 'Bearer '+token
            }
        }
        axios.get('/api/requests', this.config)
              .then(data => {
                this.allrequests = data.data.data
              })
              .catch(err => console.log(err))
      },

      deleteRequest(id){
        const token = localStorage.getItem('info')

        this.config = {
            headers: {
                'Content-Type' : 'application/json',
                'Accept' : 'application/json',
                'Authorization' : 'Bearer '+token
            }
        }
        
        axios.delete(`/api/request/delete/${id}`, this.config)
          .then(data => {
            this.$toasted.success(data.data.message, {
                duration: 3000,
              });
            this.getRequest()
          })
          .catch(err => console.log(err))
      }
    }
  }
</script>


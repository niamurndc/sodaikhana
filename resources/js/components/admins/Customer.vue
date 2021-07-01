<template>
  <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">

    <!-- modal -->
    <div v-if="getModal" class="modal" aria-hidden="true" tabindex="-1">
      <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Update User</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span v-on:click="destroyModal" aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <h5 class="text-primary">Customer Details</h5>
            <h6>Phone: {{active.phone}}</h6>
            <h6>Name</h6>
            <input type="text" v-model="active.name" class="form-control">
            <h6>Password</h6>
            <input type="text" v-model="password" class="form-control">
            <h6>Status: </h6>
            <select v-model="active.status" class="form-control">
              <option value="0">Inactive</option>
              <option value="1">Active</option>
              <option value="2">Baned</option>
            </select>
          </div>
          <div class="modal-footer">
            <button v-on:click="destroyModal" type="button" class="btn btn-secondary">Close</button>
            <button type="button" v-on:click="updateCustomer" class="btn btn-primary">Save changes</button>
          </div>
        </div>
      </div>
    </div>
    <!-- modal end -->

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
      <h1 class="h2">Customer</h1>
      <div class="btn-toolbar mb-2 mb-md-0">

      </div>
    </div>

    <h3>All Customers</h3>
    <div class="table-responsive">
      <table class="table table-striped table-sm">
        <thead>
          <tr>
            <th>#</th>
            <th>Name</th>
            <th>Phone</th>
            <th>Registerd</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="customer in allcustomers" :key="customer.id">
            <td>{{customer.id}}</td>
            <td>{{customer.name}}</td>
            <td>{{customer.phone}}</td>
            <td>{{customer.created}}</td>
            <td>
              <p v-if="customer.status == '1'" class="m-0 text-success">Active</p>
              <p v-else-if="customer.status == '0'" class="m-0 text-warning">Inactive</p>
              <p v-else class="m-0 text-danger">Baned</p>
            </td>
            <td><i v-on:click="viewCustomer(customer.id)" class="fa fa-pencil text-primary mr-1 pinter"></i><i v-on:click="deleteCustomer(customer.id)" class="fa fa-trash text-danger"></i></td>
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
        allcustomers: [],
        user_id: 0,
        active: {},
        getModal: false,
        password: '',
        config: {}
      }
    },
    mounted() {
      this.getCustomers()
    },

    methods: {
      getCustomers(){
        const token = localStorage.getItem('info')

        this.config = {
          headers: {
            'Content-Type' : 'application/json',
            'Accept' : 'application/json',
            'Authorization' : 'Bearer '+token
          }
        }
        axios.get('/api/customers', this.config)
              .then(data => {
                this.allcustomers = data.data.data
              })
              .catch(err => console.log(err))
      },

      viewCustomer(id){
        this.order_id = id

        const token = localStorage.getItem('info')

        this.config = {
          headers: {
            'Content-Type' : 'application/json',
            'Accept' : 'application/json',
            'Authorization' : 'Bearer '+token
          }
        }
        
        axios.get(`/api/customer/${id}`, this.config)
              .then(data => {
                
                const customer = data.data.data
                this.active.name = customer.name
                this.active.phone = customer.phone
                this.active.status = customer.status
                this.active.created = customer.created
                this.getModal = true
              })
              .catch(err => console.log(err))
      },

      updateCustomer(){
        const token = localStorage.getItem('info')

        this.config = {
          headers: {
            'Content-Type' : 'application/json',
            'Accept' : 'application/json',
            'Authorization' : 'Bearer '+token
          }
        }
        axios.post(`/api/customer/edit/${this.order_id}`, {status: this.active.status, password:this.password, name: this.active.name}, this.config)
              .then(data => {
                this.$toasted.success("Customer Updated Successful", {
                  duration: 3000,
                });
                this.destroyModal()
                this.getCustomers()
              })
              .catch(err => console.log(err))
      },

      destroyModal(){
        this.getModal = false
        this.user_id = 0
        this.active = {}
      },

      deleteCustomer(id){
        const token = localStorage.getItem('info')

        this.config = {
          headers: {
            'Content-Type' : 'application/json',
            'Accept' : 'application/json',
            'Authorization' : 'Bearer '+token
          }
        }

        axios.delete(`/api/customer/delete/${id}`, this.config)
          .then(data => {
            this.$toasted.success(data.data.message, {
                duration: 3000,
              });
            this.getCustomers()
          })
          .catch(err => console.log(err))
      }
    }
  }
</script>

<style>
  .modal{
    display: block;
  }
</style>

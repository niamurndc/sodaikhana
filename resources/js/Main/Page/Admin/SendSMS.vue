<template>
  <main>
    <div class="bg-primary text-white position-sticky sticky-top">
        <div class="container py-3 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <router-link to="/admin" class="text-white me-2"><i class="fa fa-arrow-left"></i></router-link>
                <h4 class="mb-0 pb-0">{{list.title}}</h4>
            </div>
        </div>
    </div>

    <div class="container">
        <h6 class="text-primary mt-4 fw-bold">Send SMS</h6>
        <textarea v-model="text" placeholder="Type sms" rows="5" class="form-control"></textarea>

        <button class="btn btn-primary mt-3" @click="sendSms">Send</button>
    </div>
  </main>
</template>

<script>
  export default {
    data(){
      return{
        config: {},
        list: {},
        listId: this.$route.params.id,
        text: '',
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

      sendSms(){
        const token = localStorage.getItem('info')

        this.config = {
          headers: {
            'Content-Type' : 'application/json',
            'Accept' : 'application/json',
            'Authorization' : 'Bearer '+token
          }
        }

        axios.post(`/api/sms-send/${this.listId}`, {text: this.text}, this.config)
              .then(data => {
                this.$router.push({path: '/admin'})
              })
              .catch(err => console.log(err))
      }
    }
  }
</script>

<template>
    <router-view></router-view>
</template>

<script>
  export default {
    data(){
      return{
        auth: false,
        config: {}
      }
    },

    beforeCreate() {
      const token = localStorage.getItem('info')

      if(!token){
        this.$router.push({path: '/'})
      }

      this.config = {
        headers: {
          'Content-Type' : 'application/json',
          'Accept' : 'application/json',
          'Authorization' : 'Bearer '+token
        }
      }

      axios.get('/api/profile', this.config)
        .then(res => {
          if(res.data.role != 23314){
            this.$toasted.error("Access Restricted", {
                duration: 3000,
            },)
            this.$router.push({path: '/'})
          }else{
            this.auth = true
            this.authkey = config
          }
        })
        .catch(err => {
          if(err.response.status == 401){
            this.$router.push({path: '/'})
          }
        })
    },

    methods: {
      logout(){
        const token = localStorage.getItem('info')

        this.config = {
          headers: {
            'Content-Type' : 'application/json',
            'Accept' : 'application/json',
            'Authorization' : 'Bearer '+token
          }
        }
        axios.post('/api/logout', {},this.config)
        .then(res => {
          if(res.data.message){
            this.$toasted.success("You are logged out", {
                duration: 3000,
            },)
          }
          this.$router.push({path: '/'})
          localStorage.removeItem('info')
          localStorage.removeItem('phone')
          localStorage.removeItem('key')
          localStorage.removeItem('name')
        })
        .catch(err => console.log(err))
      }
    }
  }
</script>
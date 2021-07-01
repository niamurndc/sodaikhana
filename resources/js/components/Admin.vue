<template>
  <div>
    <nav v-if="auth" class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
      <a class="navbar-brand col-md-3 col-lg-2 mr-0 px-3" href="#">Sodaikhana</a>
      <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-toggle="collapse" data-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <ul class="navbar-nav px-3">
        <li @click="logout" class="nav-item text-nowrap">
          <a class="nav-link text-green" href="#">Sign out</a>
        </li>
      </ul>
    </nav>

    <div v-if="auth" class="container-fluid">
      <div class="row">
        <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
          <div class="sidebar-sticky pt-3">
            <ul class="nav flex-column">
              <li class="nav-item">
                <router-link class="nav-link" to="/admin">
                  <i class="fa fa-home mr-1"></i>
                  Dashboard
                </router-link>
              </li>
              <li class="nav-item">
                <router-link class="nav-link" to="/admin/order">
                  <i class="fa fa-file-text-o mr-1"></i>
                  Orders
                </router-link>
              </li>
              <li class="nav-item">
                <router-link class="nav-link" to="/admin/product">
                  <i class="fa fa-shopping-basket mr-1"></i>
                  Products
                </router-link>
              </li>
              <li class="nav-item">
                <router-link class="nav-link" to="/admin/request">
                <i class="fa fa-bell-o mr-1"></i>
                  Request
                </router-link>
              </li>
              <li class="nav-item">
                <router-link class="nav-link" to="/admin/customer">
                <i class="fa fa-user-o mr-1"></i>
                  Customers
                </router-link>
              </li>
            </ul>
          </div>
        </nav>

        <router-view></router-view>
      </div>
    </div>

  </div>
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

<style>
  body {
    font-size: .875rem;
    font-family: 'Roboto', sans-serif;
  }

  .fa {
    width: 16px;
    height: 16px;
    vertical-align: text-bottom;
  }

  /*
  * Sidebar
  */

  .sidebar {
    position: fixed;
    top: 0;
    bottom: 0;
    left: 0;
    z-index: 100; /* Behind the navbar */
    padding: 48px 0 0; /* Height of navbar */
    box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
  }

  @media (max-width: 767.98px) {
    .sidebar {
      top: 5rem;
    }
  }

  .sidebar-sticky {
    position: relative;
    top: 0;
    height: calc(100vh - 48px);
    padding-top: .5rem;
    overflow-x: hidden;
    overflow-y: auto; /* Scrollable contents if viewport is shorter than content. */
  }

  @supports ((position: -webkit-sticky) or (position: sticky)) {
    .sidebar-sticky {
      position: -webkit-sticky;
      position: sticky;
    }
  }

  .sidebar .nav-link {
    font-weight: 500;
    color: #333;
  }

  .sidebar .nav-link .feather {
    margin-right: 4px;
    color: #999;
  }

  .sidebar .router-link-exact-active {
    color: #38c172;
  }

  /*
  * Navbar
  */

  .navbar-brand {
    padding-top: .75rem;
    padding-bottom: .75rem;
    font-size: 1rem;
    background-color: rgba(0, 0, 0, .25);
    box-shadow: inset -1px 0 0 rgba(0, 0, 0, .25);
  }

  .navbar .navbar-toggler {
    top: .25rem;
    right: 1rem;
  }

  .navbar .form-control {
    padding: .75rem 1rem;
    border-width: 0;
    border-radius: 0;
  }

  .form-control-dark {
    color: #fff;
    background-color: rgba(255, 255, 255, .1);
    border-color: rgba(255, 255, 255, .1);
  }

  .form-control-dark:focus {
    border-color: transparent;
    box-shadow: 0 0 0 3px rgba(255, 255, 255, .25);
  }
  .bd-placeholder-img {
    font-size: 1.125rem;
    text-anchor: middle;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
  }

  @media (min-width: 768px) {
    .bd-placeholder-img-lg {
      font-size: 3.5rem;
    }
  }
</style>
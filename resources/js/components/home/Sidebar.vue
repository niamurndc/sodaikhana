<template>
  <div class="mt-3">
    <ul class="side-nav-list">
      <li v-for="cat in parentcats" :key="cat.id" class="side-nav-item">

        <span class="d-flex justify-content-between align-items-center">
          <router-link :to="{path: `/category/${cat.id}`}" class="side-nav-link">{{cat.title}}</router-link>
          <span v-if="cat.parents.length != 0">
            <i v-if="child == cat.id" class="fa fa-chevron-down" v-on:click="showChild(cat.id)"></i>
            <i v-else class="fa fa-chevron-right" v-on:click="showChild(cat.id)"></i>
          </span>
        </span>
        
        <ul class="side-nav-list-child" v-if="child == cat.id">
          <li v-for="catchild in cat.parents" :key="catchild.id" class="side-nav-item-child">

            <span class="d-flex justify-content-between align-items-center">
              <router-link :to="{path: `/category/${catchild.id}`}" class="side-nav-link">{{catchild.title}}</router-link>
              <span v-if="catchild.parents.length != 0">
                <i v-if="gchild == catchild.id" class="fa fa-chevron-down" v-on:click="showGChild(catchild.id)"></i>
                <i v-else class="fa fa-chevron-right" v-on:click="showGChild(catchild.id)"></i>
              </span>
            </span>
            
            <ul class="side-nav-list-child-child" v-if="gchild == catchild.id">
              <li v-for="catgrandchild in catchild.parents" :key="catgrandchild.id" class="side-nav-item-child-child">

                <router-link :to="{path: `/category/${catgrandchild.id}`}" class="side-nav-link final">{{catgrandchild.title}}</router-link>

              </li>
            </ul>
          </li>
        </ul>
      </li>
    </ul>

  </div>
</template>

<script>
export default {
  data(){
    return{
      allcats: [],
      parentcats: [],
      child: 0,
      gchild: 0
    }
  },

  mounted() {
    this.getCats()
  },

  methods: {
    getCats(){
      axios.get('/api/categories')
              .then(res => {
                this.allcats = res.data.data
                this.getOnlyParent()
              })
              .catch(err => console.log(err))
    },

    getOnlyParent(){
      this.parentcats = this.allcats.filter(cat => cat.parent === null)
    },

    showChild(id){
      if(this.child == id){
        this.child = 0
      }else{
        this.child = 0
        this.child = id
      }
      
    },

    showGChild(id){
      if(this.gchild == id){
        this.gchild = 0
      }else{
        this.gchild = 0
        this.gchild = id
      }
    },

    gotosingle(id){
      this.$router.push({path: `/category/${id}`})
    }

  }

}
</script>

<style scoped>
  .side-nav-list{
    list-style: none;
    padding-left: 5px;
  }

  .side-nav-item{
    padding-left: 5px;
    font-size: 16px;
  }

  .side-nav-link{
    cursor: pointer;
    color: #2e2e2e;
    text-decoration: none;
    padding-top: 5px;
  }

  .side-nav-link:hover{
    color: #289672;
    text-decoration: none;
  }

  .final{
    padding-bottom: 5px;
  }

  .side-nav-list-child{
    list-style: none;
    padding-left: 5px;
    border-left: 1px solid #289672;
  }

  .side-nav-item-child{
    padding-left: 10px;
    font-size: 16px;
  }

  .side-nav-list-child-child{
    list-style: none;
    padding-left: 5px;
    border-left: 1px solid #289672;
  }

  .side-nav-item-child-child{
    padding-left: 10px;
    font-size: 16px;
  }
</style>
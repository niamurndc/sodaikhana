<template>
    <div class="mx-0">

        <router-view v-on:updateCart="updateCart"></router-view>
        

        <div class="footer bg-dark text-white px-5 pt-5">
            <div class="row">
                <div class="col-md-3">
                <h4>Sodaikhana</h4>
                </div>
                <div class="col-md-6">
                    <div class="d-flex align-items-center mt-1 justify-content-evenly">
                        <p><a href="#" class="text-white mt-3 text-decoration-none">About Us</a></p>
                        <p><a href="#" class="text-white mt-3 text-decoration-none">Terms & Conditons</a></p>
                        <p><a href="#" class="text-white mt-3 text-decoration-none">Privacy Policy</a></p>
                    </div>
                    
                </div>
                <div class="col-md-3">
                <div class="social-link d-flex justify-content-end">
                    <a href="#" class="text-white fs-4 ms-4"><i class="fa fa-facebook"></i></a>
                    <a href="#" class="text-white fs-4 ms-4"><i class="fa fa-twitter"></i></a>
                    <a href="#" class="text-white fs-4 ms-4"><i class="fa fa-instagram"></i></a>
                </div>
                </div>
            </div>

            <hr>
            <div class="d-flex justify-content-between pt-3">
                <p> Sodaikhana &copy; 2021 </p>
                <a href="https://www.facebook.com/bloopwebsolution" class="text-light text-decoration-none">Developed by Bloop Web Solution</a>
            </div>
        </div>
    </div>
</template>

<script>
    export default {

        data(){
          return{
          }
        },

        methods: {
          updateCart(){
            this.$emit('updateCart')
          }
        }
    }
</script>


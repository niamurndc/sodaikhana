<template>
<div>
    <div class="bg-primary text-white position-sticky sticky-top">
        <div class="container py-3 d-flex justify-content-between align-items-center">
            <router-link to="/admin" class="text-white text-decoration-none"><h4 class="mb-0 pb-0">Sodiakhana</h4></router-link>

            <router-link to="/admin/setting" class="text-white"><i class="fa fa-cog"></i></router-link>
        </div>
    </div>

    <div class="container my-5">
        <div class="row">
            <div class="col-6 col-md-4 mt-4">
                <router-link class="text-dark text-decoration-none" to="/admin/pos">
                <div class="card shadow-sm d-flex align-items-center justify-content-center  py-4">
                    <img :src="`/image/add_product.png`" alt="product">
                    <p>POS</p>
                </div>
                </router-link>
            </div>
            <div class="col-6 col-md-4 mt-4">
                <router-link class="text-dark text-decoration-none" to="/admin/daily-sale">
                <div class="card shadow-sm d-flex align-items-center justify-content-center  py-4">
                    <img :src="`/image/story.png`" alt="product">
                    <p>Daily Sale</p>
                </div>
                </router-link>
            </div>
            <div class="col-6 col-md-4 mt-4">
                <router-link class="text-dark text-decoration-none" to="/admin/orders">
                <div class="card shadow-sm d-flex align-items-center justify-content-center  py-4">
                    <img :src="`/image/product.png`" alt="product">
                    <p>Orders</p>
                </div>
                </router-link>
            </div>

            <div class="col-6 col-md-4 mt-4">
                <router-link class="text-dark text-decoration-none" to="/admin/customers">
                <div class="card shadow-sm d-flex align-items-center justify-content-center  py-4">
                    <img :src="`/image/customer.png`" alt="product">
                    <p>Customer</p>
                </div>
                </router-link>
            </div>

            <div class="col-6 col-md-4 mt-4">
                <router-link class="text-dark text-decoration-none" to="/admin/add-order">
                <div class="card shadow-sm d-flex align-items-center justify-content-center  py-4">
                    <img :src="`/image/add_product.png`" alt="product">
                    <p>Create Orders</p>
                </div>
                </router-link>
            </div>

            <div class="col-6 col-md-4 mt-4">
                <router-link class="text-dark text-decoration-none" to="/admin/products">
                <div class="card shadow-sm d-flex align-items-center justify-content-center  py-4">
                    <img :src="`/image/product.png`" alt="product">
                    <p>Product</p>
                </div>
                </router-link>
            </div>



            <div class="col-6 col-md-4 mt-4">
                <router-link class="text-dark text-decoration-none" to="/admin/stocks">
                <div class="card shadow-sm d-flex align-items-center justify-content-center  py-4">
                    <img :src="`/image/stock.png`" alt="product">
                    <p>Manage Stock</p>
                </div>
                </router-link>
            </div>

            <div class="col-6 col-md-4 mt-4">
                <router-link class="text-dark text-decoration-none" to="/admin/pro-request">
                <div class="card shadow-sm d-flex align-items-center justify-content-center  py-4">
                    <img :src="`/image/customer.png`" alt="product">
                    <p>Product Request</p>
                </div>
                </router-link>
            </div>

            <div class="col-6 col-md-4 mt-4">
                <router-link class="text-dark text-decoration-none" to="/admin/add-product">
                <div class="card shadow-sm d-flex align-items-center justify-content-center  py-4">
                    <img :src="`/image/add_product.png`" alt="product">
                    <p>Add Product</p>
                </div>
                </router-link>
            </div>

            <div class="col-6 col-md-4 mt-4">
                <router-link class="text-dark text-decoration-none" to="/admin/areas">
                <div class="card shadow-sm d-flex align-items-center justify-content-center  py-4">
                    <img :src="`/image/area.png`" alt="product">
                    <p>Area</p>
                </div>
                </router-link>
            </div>
            <div class="col-6 col-md-4 mt-4">
                <router-link class="text-dark text-decoration-none" to="/admin/story">
                <div class="card shadow-sm d-flex align-items-center justify-content-center  py-4">
                    <img :src="`/image/story.png`" alt="product">
                    <p>My Day</p>
                </div>
                </router-link>
            </div>

            <div class="col-6 col-md-4 mt-4">
                <router-link class="text-dark text-decoration-none" to="/admin/sms-marketing">
                <div class="card shadow-sm d-flex align-items-center justify-content-center  py-4">
                    <img :src="`/image/sms.png`" alt="product">
                    <p>SMS Marketing</p>
                </div>
                </router-link>
            </div>
        </div>
    </div>
</div>
</template>
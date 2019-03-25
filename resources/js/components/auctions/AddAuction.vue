<template>
    <div>
        <form @submit.prevent="addAuction" class="w-full max-w-md" enctype="multipart/form-data">
        <div class="flex flex-wrap -mx-3 mb-6">
            <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
            <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="car-name">
                Car Name
            </label>
            <input class="appearance-none block w-full  text-grey-darker border border-grey-lighter rounded py-3 px-4 leading-tight focus:outline-none bg-white focus:border-grey" id="car-name" type="text" placeholder="BMW" v-model="form.car_name">
             <p class="text-red text-xs italic" v-if="errors.car_name">{{ errors.car_name[0] }}</p>
            </div>
            <div class="w-full md:w-1/2 px-3">
            <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="start-bid-amount">
                Start Bid Amount
            </label>
            <input class="appearance-none block w-full  text-grey-darker border border-grey-lighter rounded py-3 px-4 leading-tight focus:outline-none bg-white focus:border-grey" id="start-bid-amount" type="number" max="100000" placeholder="$" v-model="form.start_bid_amount">
            <p class="text-red text-xs italic" v-if="errors.start_bid_amount">{{ errors.start_bid_amount[0] }}</p>
            </div>
        </div>
        <div class="flex flex-wrap -mx-3 mb-6">
            <div class="w-full md:w-1/2  px-3">
            <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="location">
                Location
            </label>
            <input class="appearance-none block w-full bg-white text-grey-darker border border-grey-lighter rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:border-grey" id="location" type="text" placeholder="Madinet Nasr, Cairo, Egypt" v-model="form.location">
            <p class="text-red text-xs italic" v-if="errors.location">{{ errors.location[0] }}</p>
            </div>
            <div class="w-full md:w-1/2  px-3">
            <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="end-time">
                End Time
            </label>
            <input class="appearance-none block w-full bg-white text-grey-darker border border-grey-lighter rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:border-grey" id="end-time" type="date" v-model="form.end_time">
            <p class="text-red text-xs italic" v-if="errors.end_time">{{ errors.end_time[0] }}</p>
            </div>
            </div>
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0 text-black">
                <input class="appearance-none block w-full bg-white text-grey-darker border border-grey-lighter rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:border-grey" id="car-image" type="file" v-on:change="onImageChange">
                <p class="text-red text-xs italic" v-if="errors.image">{{ errors.image[0] }}</p>
                </div>
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0 text-black h-32 min-h-full bg-grey max-auto text-center" id="add-auction-image" style="position: relative"><img :src="image" alt="car image" v-if="image">
                <button id="delete-image-button" class="bg-blue hover:bg-blue-dark text-white font-bold py-2 px-4 rounded" v-if="image" @click="removeImage">x</button>
                </div>
            </div>
            <div class="flex flex-wrap mb-6">
                <button class="bg-blue hover:bg-blue-dark text-white font-bold py-2 px-4 rounded">Submit</button>
            </div>
        </form>
    </div>
</template>

<script>
    import axios from 'axios'
    import { post } from '../../helper/api'
    import Auth from '../../store/auth'

    export default {
        mounted() {
            console.log('Add Acution Component mounted.')
        },
        data() {
            return {
                image: null,
                file: null,
                form: {
                    car_name: '',
                    start_bid_amount: null,
                    location: '',
                    end_time: null,
                },
                errors: []
            }
        }
        ,
        methods: {
            addAuction() {
                this.errors = []
                let formData = new FormData();
                formData.append('image', this.image);
                formData.append('car_name', this.form.car_name);
                formData.append('end_time', this.form.end_time);
                formData.append('location', this.form.location);
                formData.append('start_bid_amount', this.form.start_bid_amount);
                 const config = {
                    headers: { 'content-type': 'multipart/form-data',
                        'Authorization': Auth.state.access_token }
                }
                console.log(formData)
                axios.post(`http://localhost:8000/api/auctions/create`, formData, config)
                .then((res) => {
                    if(res.status === 201) {
                        // user created
                        swal("Auction has been created successfully!, You'll be redirected to login page.", {
                            buttons: false,
                            timer: 4000,
                            icon: "success"
                        }).then(() => this.$router.push("/login"));
                    }
                })
                .catch((e) => {
                    this.errors = e.response.data.errors
                });
                
            },
            onImageChange(e) {
                this.file = e.target.files[0]
                e.target.value = ""
                let fileReader = new FileReader()
                const  fileType = this.file['type'];
                const validImageTypes = ['image/gif', 'image/jpeg', 'image/png'];
                if (!validImageTypes.includes(fileType)) {
                    swal(`Please upload image with png, gif amd jpeg extension`, {
                        buttons: false,
                        timer: 3000,
                        icon: "error"
                    })
                } else {
                    var vm = this
                    fileReader.onload = (event) => {
                        vm.image = event.target.result
                    }
                    fileReader.readAsDataURL(this.file)
                }
            },
            removeImage() {
                this.image = null
                this.file = null
            }
        }
    }
</script>

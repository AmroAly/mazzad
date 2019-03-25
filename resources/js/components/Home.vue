<template>
    <div class="home my-container flex items-center inline-block xl:justify-between  flex-wrap w-4/5 mx-auto">
        <div class="sm:w-full lg:w-1/2 xl:w-1/3 mb-8 lg:mr-8 xl:mr-4 sm:mx-auto  max-w-xs min-w-xs  rounded overflow-hidden shadow-lg"  v-for="auction in auctions" :id="'auction'+auction.id">
        <img class="w-full" :src="'images/'+auction.image.name" alt="Sunset in the mountains">
        <div class="px-6 py-4">
            <div class="text-lg text-grey-darkest float-left mb-2">{{ auction.car_name }}</div>
            <div class="text-sm bg-teal-dark text-white p-1 rounded text-grey-darker float-right  mb-2">{{ daysBetweenTwoDates(auction.end_time, Date()) }}</div>
        </div>
        <div class="px-6 py-4">
            <div class="text-lg text-grey-darkest float-left mb-2">Start bid amount</div>
            <div class="text-sm text-grey-darker float-right  mb-2">$ {{ auction.start_bid_amount }}</div>
        </div>
        <div class="px-6 py-4">
            <div class="text-lg text-grey-darkest float-left mb-2">Final price</div>
            <div class="text-sm text-grey-darker float-right  mb-2" :id="'final-price-'+auction.id">$ {{ auction.final_price }}</div>
        </div>
        <div class="px-6 py-4">
        <button class="bg-blue-darkest w-full hover:bg-blue-darker text-white font-bold py-2 px-4 border-b-4 border-teal-dark hover:border-teal-dark rounded" @click.prevent="addBid(auction.id)">
        Add Bid
        </button>
        </div>
        </div>
    </div>
</template>

<script>
    // import axios from 'axios'
    import { get, post } from '../helper/api'
    import Auth from '../store/auth'
    import { daysBetweenTwoDates } from '../helper/api'

    export default {
        mounted() {
        console.log('called')
            get(`/api/auctions/open`)
                .then((res) => {
                    this.auctions = res.data.auctions
                })
                .catch((e) => console.log(e))
        },
        data() {
            return {
                auctions: []
            }
        },
        methods: {
            daysBetweenTwoDates,
            addBid (id) {
                if(! Auth.state.authenticated) {
                    swal(`You need to login to perform this action`, {
                        buttons: false,
                        timer: 3000,
                        icon: "error"
                    })
                    .then(() => this.$router.push('/login'))
                } else {
                    swal(`Please bid with a higher value than the final price`, {
                        buttons: {
                            cancel: true,
                            confirm: "Confirm"
                        },
                        content: {
                            element: "input",
                            attributes: {
                                type: "number",
                            },
                        }
                    })
                    .then((bid) => {                        
                        // if it's  not empty
                        if(!isNaN(bid) && bid != "" && bid != null) {
                            post(`/api/bids/add`, {
                                auc_id: id,
                                amount: bid
                            }).then(res => {
                                swal("Your Bid has been added successfully", {
                                    buttons: false,
                                    timer: 3000,
                                    icon: "success"
                                })
                                .then(() => {
                                    // update the view
                                    document.querySelector(`#final-price-${id}`).innerText = `$ ${parseFloat(bid).toFixed(2)}`;
                                })
                            })
                            .catch(e => {
                                if(e.response.status == 422 && e.response.data.message && !e.response.data.errors) {
                                    swal(`${e.response.data.message}`, {
                                        buttons: false,
                                        timer: 3000,
                                        icon: "error"
                                    })
                                }
                                else if(e.response.status == 422) {
                                    swal(`${e.response.data.errors['amount']}`, {
                                        buttons: false,
                                        timer: 3000,
                                        icon: "error"
                                    })
                                }
                            })
                        }
                    })
                }
            }
        },
    }
</script>

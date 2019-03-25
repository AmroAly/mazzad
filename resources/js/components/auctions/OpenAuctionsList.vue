<template>
    <div>
        <div class="max-w-md w-full clearfix">
            <h3 class="p-4 text-black float-left">Open Auctions</h3>
            <router-link :to="{name: 'auctions_add'}" class="bg-blue float-right hover:bg-blue-dark text-white font-bold py-2 px-4 rounded m-1">
                Add Auction
            </router-link>
        </div>
        <div class="max-w-md w-full mb-6 lg:flex" v-for="auction in auctions" :id="'auction-'+auction.id">
            <div class="h-48 lg:h-auto lg:w-48 flex-none bg-cover rounded-t lg:rounded-t-none lg:rounded-l text-center overflow-hidden"  title="">
                <img :src="'/images/'+auction.image.name" alt="">
            </div>
            <div class="border-r border-b w-full border-l border-grey-light lg:border-l-0 lg:border-t lg:border-grey-light bg-white rounded-b lg:rounded-b-none lg:rounded-r p-4 flex flex-col justify-between leading-normal">
                <div class="mb-8">
                
                <div class="text-black float-left font-bold text-xl mb-2">{{ auction.car_name }}</div>
                <div class="float-right font-bold text-blue text-xl mb-2">final price - $ {{ auction.final_price }}</div>
                </div>
                <div class="items-center text-sm">
                <form @submit.prevent="closeAuction(auction.id)" class="inline-block">
                    <button class="bg-red hover:bg-red-light text-white font-bold py-2 px-4 border-b-4 border-red-dark hover:border-red rounded">
                        Close
                    </button>
                </form>
                    <div class="float-right py-2 px-4 text-black">{{ daysBetweenTwoDates(auction.end_time, Date()) }}</div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    // import axios from 'axios'
    import { get, post } from '../../helper/api'
    import Auth from '../../store/auth'
    import { daysBetweenTwoDates } from '../../helper/api'

    export default {
        mounted() {
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
            closeAuction(id) {
                    post(`/api/auctions/edit/${id}`, {
                        _method: 'put',
                        close: true
                    }).then(res => {
                        const elem = document.querySelector('#auction-'+id);
                        elem.parentNode.removeChild(elem);
                        swal("The Auction has been closed successfully", {
                            buttons: false,
                            timer: 2000,
                            icon: "success"
                        })
                    }).catch(e => {
                        console.log(e)
                    })
            }
        },
    }
</script>

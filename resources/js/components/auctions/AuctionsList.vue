<template>
    <div>
        <div class="max-w-md w-full clearfix">
            <h3 class="p-4 text-black float-left">All Auctions</h3>
            <router-link :to="{name: 'auctions_add'}" class="bg-blue float-right hover:bg-blue-dark text-white font-bold py-2 px-4 rounded m-1">
                Add Auction
            </router-link>
        </div>
        <div class="max-w-md w-full mb-6 lg:flex" v-for="auction in auctions">
            <div class="h-48 lg:h-auto lg:w-48 flex-none bg-cover rounded-t lg:rounded-t-none lg:rounded-l text-center overflow-hidden" title="Woman holding a mug">
                <img :src="'/images/'+auction.image.name" alt="">
            </div>
            <div class="border-r border-b w-full border-l border-grey-light lg:border-l-0 lg:border-t lg:border-grey-light bg-white rounded-b lg:rounded-b-none lg:rounded-r p-4 flex flex-col justify-between leading-normal">
                <div class="mb-8">
                
                <div class="text-black float-left font-bold text-xl mb-2">{{ auction.car_name }}</div>
                <div class="float-right font-bold text-blue text-xl mb-2">final price - $ {{ auction.final_price, }}</div>
                </div>
                <div class="items-center text-sm">
                    <div class="float-left py-2 px-4 text-black">Bids - {{ auction.bids.length }}</div>
                    <div class="float-right py-2 px-4 text-black">{{ daysBetweenTwoDates(auction.end_time, Date()) }}</div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    // import axios from 'axios'
    import { get } from '../../helper/api'
    import Auth from '../../store/auth'
    import { daysBetweenTwoDates } from '../../helper/api'

    export default {
        mounted() {
        console.log('called')
            get(`/api/auctions/all`)
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
            daysBetweenTwoDates
        },
    }
</script>

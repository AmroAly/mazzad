<template>
    <div>
        <div class="max-w-md w-full clearfix">
            <h3 class="p-4 text-black float-left">Closed Auctions</h3>
            <router-link :to="{name: 'auctions_add'}" class="bg-blue float-right hover:bg-blue-dark text-white font-bold py-2 px-4 rounded m-1">
                Add Auction
            </router-link>
        </div>
        <div class="max-w-md w-full mb-6 lg:flex" v-for="auction in auctions" :id="'auction-'+auction.id">
            <div class="h-48 lg:h-auto lg:w-48 flex-none bg-cover rounded-t lg:rounded-t-none lg:rounded-l text-center overflow-hidden" title="">
            <img :src="'/images/'+auction.image.name" alt="">
            </div>
            <div class="border-r border-b w-full border-l border-grey-light lg:border-l-0 lg:border-t lg:border-grey-light bg-white rounded-b lg:rounded-b-none lg:rounded-r p-4 flex flex-col justify-between leading-normal">
                <div class="mb-8">
                
                <div class="text-black float-left font-bold text-xl mb-2">{{ auction.car_name }}</div>
                <div class="float-right font-bold text-blue text-xl mb-2">final price - $ {{ auction.final_price }}</div>
                </div>
                <div class="items-center text-sm">
                    <button @click="openAuction(auction.id)" class="bg-green hover:bg-green-light text-white font-bold py-2 px-4 border-b-4 border-green-dark hover:border-green rounded">
                        Reopen
                    </button>
                    <button class="bg-red hover:bg-red-light text-white font-bold py-2 px-4 border-b-4 border-red-dark hover:border-red rounded" @click.prevent="deleteAuction(auction.id)">
                        Delete
                    </button>
                    <div class="float-right py-2 px-4 text-black">{{ daysBetweenTwoDates(auction.end_time, Date()) }}</div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    // import axios from 'axios'
    import { get, post, delte } from '../../helper/api'
    import Auth from '../../store/auth'
    import { daysBetweenTwoDates } from '../../helper/api'

    export default {
        mounted() {
        console.log('called')
            get(`/api/auctions/closed`)
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
            openAuction(id) {
                swal("To Reopen an Auction you need to provide some date in the future",{
                    buttons: {
                        cancel: true,
                        confirm: "Confirm"
                    },
                    content: {
                        element: "input",
                        attributes: {
                        type: "date",
                        },
                    },
                }).then((date) => {
                    // if valid date
                    if((new Date(date) !== "Invalid Date") && !isNaN(new Date(date)) && date != null) {
                        post(`/api/auctions/edit/${id}`, {
                            _method: 'put',
                            end_time: date
                        }).then(res => {
                            swal("The Auction has been reopen successfully", {
                                buttons: false,
                                timer: 3000,
                                icon: "success"
                            })
                            .then(() => this.$router.push('/dashboard/auctions'));
                        }).catch(e => {
                            swal(`${e.response.data.errors["end_time"][0]}`, {
                                buttons: false,
                                timer: 4000,
                                icon: "error"
                            })
                        })
                    }
                })
            },
            deleteAuction(id) {
                delte(`/api/auctions/delete`, {
                    id
                }).then(res => {
                    const elem = document.querySelector('#auction-'+id);
                    elem.parentNode.removeChild(elem);
                    swal("The Auction has been deleted successfully", {
                        buttons: false,
                        timer: 3000,
                        icon: "success"
                    })
                }).catch(e => {
                    swal(`${e.response.data.errors["end_time"][0]}`, {
                        buttons: false,
                        timer: 4000,
                        icon: "error"
                    })
                })
            }
        },
    }
</script>

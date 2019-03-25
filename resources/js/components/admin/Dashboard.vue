<template>
    <div class="dashboard">
        <div class="flex md:flex-row-reverse flex-wrap">
            <div class="my-container flex justify-between flex-wrap w-4/5">
                <div class="w-full md:w-1/4  text-grey-darker sidebar">
                    <ul class="list-reset block">
                        <li class="mr-6">
                            <router-link class="text-blue block no-underline hover:bg-white p-4" :to="{name: 'auctions_list'}">All Auction</router-link>
                        </li>
                        <li class="mr-6">
                            <router-link class="text-blue block  no-underline hover:bg-white p-4" :to="{name: 'auctions_open'}">Open Auctions</router-link>
                        </li>
                        <li class="mr-6 ">
                            <router-link class="text-blue block no-underline hover:bg-white p-4" :to="{name: 'auctions_closed'}">Closed Auctions</router-link>
                        </li>
                    </ul>
                </div>
                <div class="w-full md:w-3/4 p-4  text-grey-lighter">
                    <router-view></router-view>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Dashboard Component mounted.')
        }
    }
</script>

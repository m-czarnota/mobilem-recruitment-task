<script setup>
import { reactive } from 'vue';
import { ApiRouteGenerator } from './../../http-client/ApiRouteGenerator';
import { RouteGenerator } from './../../router/RouteGenerator';

const resourceUrl = `${ApiRouteGenerator.generatePath('/announcements')}?page=1&limit=10`;
const response = await fetch(resourceUrl, {
    method: "GET",
});

const isResponseOk = reactive(response.ok);
const data = await response.json();

</script>

<template>
    <div class="gap-4 flex flex-col">
        <div v-if="isResponseOk" v-for="announcementData in data.records" class="p-3 shadow-md shadow-slate-800 rounded bg-slate-50">
            <div class="flex justify-between text-xl">
                <p class="font-semibold">{{ announcementData.title }}</p>
                <p class="font-bold">{{ announcementData.cost }} zł</p>
            </div>
            <hr class="my-3 border-slate-400">
            <p class="bg-slate-200 px-2 max-h-8 text-clip overflow-hidden ...">{{ announcementData.description }}</p>

            <RouterLink 
                :to="`/announcements/${announcementData.id}`" 
                class="text-blue-700 hover:underline"
            >
                
                See more
            </RouterLink>
        </div>

        <div v-if="isResponseOk && data.paginationDataDto.totalRecords === 0">
            No clients. First add one!
        </div>

        <div v-if="!isResponseOk">
            Cannot fetch data from api
        </div>
    </div>
</template>
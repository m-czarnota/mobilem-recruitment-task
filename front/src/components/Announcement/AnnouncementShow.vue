<script setup>
import { useRoute } from 'vue-router'
import { reactive } from 'vue';
import { ApiRouteGenerator } from './../../http-client/ApiRouteGenerator';

const route = useRoute();

const resourceUrl = `${ApiRouteGenerator.generatePath('/announcements/' + route.params.id)}`;
const response = await fetch(resourceUrl, {
    method: "GET"
});

const isResponseOk = reactive(response.ok);
const data = await response.json();

</script>

<template>
    <div class="gap-4 flex flex-col">
        <div v-if="isResponseOk" class="p-3 shadow-md shadow-slate-800 rounded bg-slate-50">
            <div class="flex justify-between text-xl">
                <p class="font-semibold">{{ data.title }}</p>
                <p class="font-bold">{{ data.cost }} zł</p>
            </div>
            <hr class="my-3 border-slate-400">
            <p>{{ data.description }}</p>

            <div v-if="data.files?.length > 0">
                <hr class="my-2 border-slate-400">
                <p>Files:</p>
                
                <a v-for="file in data.files" :href="file.path" target="_blank" class="pl-3 text-blue-700 hover:underline" download>
                    <font-awesome-icon icon="fa-solid fa-file" />
                    {{ file.name }}
                </a>
            </div>
        </div>

        <div v-if="!isResponseOk">
            Cannot fetch data from api
        </div>
    </div>
</template>
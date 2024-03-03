<script setup>
import SidebarLink from './SidebarLink.vue';
import { ref, computed, provide, reactive } from 'vue';
import { RouteGenerator } from '../../router/RouteGenerator';
import { Guard } from './../../Authenticator/Guard';

const collapsed = ref(false);
const toggleSidebar = () => (collapsed.value = !collapsed.value);

const SIDEBAR_WIDTH = 180;
const SIDEBAR_WIDTH_COLLAPSED = 45;
const sidebarWidth = computed(
  () => `${collapsed.value ? SIDEBAR_WIDTH_COLLAPSED : SIDEBAR_WIDTH}px`
);

const apiToken = ref(Guard.getToken());
setInterval(() => apiToken.value = Guard.getToken(), 100);

provide('collapsed', collapsed);
</script>

<template>
    <nav class="p-3 bg-zinc-800 text-white min-h-dvh transition-all duration-300 flex flex-col justify-between" :style="{ width: sidebarWidth }">
        <div>
            <h1 class="text-center">
                <span class="*:block" v-if="collapsed">
                    <span>M</span>
                    <span>b</span>
                </span>
                <span v-else>Mobilem</span>
            </h1>
            <hr class="rounded my-2">

            <div class="*:py-1.5 gap-2 flex flex-col">
                <SidebarLink :to="RouteGenerator.generateRoute('addAnnouncement')" icon="fa-solid fa-circle-plus" name="Add announcement"/>
                <SidebarLink :to="RouteGenerator.generateRoute('listAnnouncements')" icon="fa-solid fa-list" name="List of announcements"/>
                <SidebarLink v-if="apiToken" :to="RouteGenerator.generateRoute('logout')" icon="fa-solid fa-right-from-bracket" name="Logout"/>
            </div>
        </div>

        <button
            type="button"
            class="flex justify-center items-center px-3 border-2 rounded-full border-transparent transition duration-150 hover:border-white"
            @click="toggleSidebar"
        >
            <font-awesome-icon icon="fa-solid fa-angles-left" :class="{ 'rotate-180': collapsed }"/>
        </button>
    </nav>
</template>

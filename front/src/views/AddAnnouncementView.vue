<script setup lang="ts">
import AddAnnouncementForm from './../components/Announcement/AddAnnouncementForm.vue';
import { Guard } from './../Authenticator/Guard';
import router from './../router';

if (!Guard.getToken()) {
    router.push({
        name: 'login',
    });
}
</script>

<template>
    <div>
        <h1 class="text-2xl font-bold mb-3">Add Announcement</h1>
        <AddAnnouncementForm/>
    </div>
</template>
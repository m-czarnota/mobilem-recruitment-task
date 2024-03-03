<script setup>
import AnnouncementList from './../components/Announcement/AnnouncementList.vue';

</script>

<template>
    <h1 class="font-bold text-3xl mb-3">Announcements list</h1>
    <Suspense>
        <AnnouncementList/>

        <template #fallback>
            Loading...
        </template>
    </Suspense>
</template>
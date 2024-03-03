<script setup>
import AnnouncementShow from './../components/Announcement/AnnouncementShow.vue';

</script>

<template>
    <h1 class="font-bold text-3xl mb-3">Announcement details</h1>
    <Suspense>
        <AnnouncementShow/>

        <template #fallback>
            Loading...
        </template>
    </Suspense>
</template>
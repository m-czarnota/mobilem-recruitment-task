<script setup>
import Sidebar from './components/Sidebar/Sidebar.vue';
</script>

<template>
    <div style="display: flex;">
        <Sidebar/>

        <div class="w-full p-10" style="width: 100%; padding: 5rem;">
            <RouterView/>
        </div>
    </div>
</template>
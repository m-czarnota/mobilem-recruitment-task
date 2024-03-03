<script setup>
import LoginForm from './../components/Login/LoginForm.vue';
import { Guard } from './../Authenticator/Guard';
import router from './../router';
import { provide } from 'vue';

// get it from store like pinia
const titleForProtectedResource = 'Sign in to add an announcement';
const routeNameAfterLogin = 'addAnnouncement';

provide('routeNameAfterLogin', routeNameAfterLogin);

if (Guard.getToken()) {
    router.push({
        name: routeNameAfterLogin,
    });
}
</script>

<template>
    <div>
        <h1 class="font-bold text-3xl mb-3">{{ titleForProtectedResource }}</h1>
        <LoginForm/>
    </div>
</template>
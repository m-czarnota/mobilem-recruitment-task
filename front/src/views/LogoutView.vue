<script setup>
import { Guard } from './../Authenticator/Guard';
import router from './../router';

Guard.removeToken();
router.push({
    name: 'login',
});
</script>

<template></template>
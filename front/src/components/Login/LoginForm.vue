<script setup>
import { ref, inject } from 'vue';
import router from './../../router';
import { ApiRouteGenerator } from './../../http-client/ApiRouteGenerator';
import { Guard } from './../../Authenticator/Guard';

const routeNameAfterLogin = inject('routeNameAfterLogin');

const form = ref(null);
const loginError = ref(null);

const sendForm = async () => {
    loginError.value = null;
    const formData = new FormData(form.value);
    
    const response = await fetch(ApiRouteGenerator.generatePath('/login'), {
        method: "POST",
        body: formData,
    });
    const data = await response.json();

    if (!response.ok) {
        const error = data.error;
        loginError.value = error;

        return;
    }

    const token = data.token;
        Guard.saveToken(token);

        router.push({
            name: routeNameAfterLogin,
        });

        form.value.reset();
};
</script>

<template>
    <form @submit.prevent="sendForm" ref="form">
        <div v-if="loginError" class="p-3 mb-3 shadow-sm shadow-red-700 rounded text-red-600">
            {{ loginError }}
        </div>

        <div>
            <div>
                <label for="email">
                    Email:
                </label>
                <input type="email" name="email" id="email">
            </div>
        </div>    

        <div>
            <div>
                <label for="password">
                    Password:
                </label>
                <input type="password" name="password" id="password">
            </div>
        </div>

        <button type="submit">Sign in</button>
    </form>
</template>
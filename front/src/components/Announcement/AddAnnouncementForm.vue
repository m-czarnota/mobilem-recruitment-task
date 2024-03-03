<script setup>
import { reactive, ref } from 'vue';
import { Announcement } from './Announcement';
import { ApiRouteGenerator } from './../../http-client/ApiRouteGenerator';
import { Guard } from './../../Authenticator/Guard';

const announcement = reactive(new Announcement());
const form = ref(undefined);

const errors = ref({});
const filesErrors = ref([]);
const successInfo = ref(null);

const sendForm = async () => {
    errors.value = {};
    filesErrors.value = [];
    successInfo.value = null;

    const formData = new FormData(form.value);
    const resourceUrl = ApiRouteGenerator.generatePath('/announcements', true);

    try {
        const response = await fetch(resourceUrl, {
            method: "POST",
            body: formData,
            headers: {
                'Authorization': `Bearer ${Guard.getToken()}`,
            },
        });

        if (response.ok) {
            successInfo.value = "Successfully added an announcement";
            form.value.reset();
            announcement.reset();

            return;
        }

        const reponseErrors = await response.json();

        errors.value = reponseErrors;
        filesErrors.value = reponseErrors.files ?? [];
    } catch(tooLargeEntityError) {
        errors.value = {
            'generalError': 'Server error. Probably the uploaded files have too large a total size',
        };
    }

    
};
</script>

<template>
    <form @submit.prevent="sendForm" ref="form">
        <div v-if="successInfo" class="p-3 mb-3 shadow-sm shadow-green-700 rounded text-green-600">
            {{ successInfo }}
        </div>

        <div v-if="errors.generalError" class="p-3 mb-3 shadow-sm shadow-red-700 rounded text-red-600">
            {{ errors.generalError }}
        </div>

        <div>
            <div>
                <label for="title">
                    Title:
                </label>
                <input type="text" name="title" id="title" maxlength="80" v-model="announcement.title"> 
            </div>

            <span v-if="errors.title" class="form-input-error">
                {{ errors.title }}
            </span>
        </div>

        <div>
            <div class="flex flex-col">
                <label for="description">
                    Description:
                </label>
                <textarea name="description" id="description" v-model="announcement.description"></textarea>
            </div>

            <span v-if="errors.description" class="form-input-error">
                {{ errors.description }}
            </span>
        </div>

        <div>
            <div>
                <label for="cost">
                    Cost:
                </label>
                <input type="number" inputmode="numeric" min="0" name="cost" id="cost" v-model="announcement.cost"> 
                <span>
                    zł
                </span>
            </div>

            <span v-if="errors.cost" class="form-input-error">
                {{ errors.cost }}
            </span>
        </div>

        <div>
            <div>
                <label for="file">
                    Images:
                </label>
                <input type="file" name="files[]" id="files" accept="image/*, image/*" multiple="true"> 
                
                <div class="flex flex-col">
                    <span class="text-sm">Only images. Max 5 images.</span>
                    <span class="text-sm">Allowed size: 2MB</span>
                </div>
            </div>

            <div v-if="filesErrors.length">
                <div v-for="(fileErrors, key) in filesErrors">
                    <div v-if="Object.keys(fileErrors).length">
                        File {{ key + 1 }}:
                        <p v-for="(error, key) in fileErrors" class="form-input-error">
                            {{ key }}: {{ error }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <button type="submit">Submit</button>
    </form>
</template>
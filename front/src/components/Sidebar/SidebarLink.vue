<script setup>
import { computed, inject } from 'vue';
import { useRouter } from 'vue-router';

const props = defineProps({
    to: {
        type: Object,
        required: true,
    },
    icon: {
        type: String,
        required: true,
    },
    name: {
        type: String,
        required: true,
    },
});

const router = useRouter();
const isActive = computed(() => {
    const currentRouteName = router.currentRoute.value.name;

    return currentRouteName === props.to.name;
});

const collapsed = inject('collapsed');
</script>

<template>
    <RouterLink 
        :to="to.path" 
        class="flex gap-2 items-center cursor-pointer font-light select-none rounded text-slate-100 transition-all duration-150 hover:underline hover:text-white active:underline" 
        :class="{ 'bg-purple-500/25': isActive, 'px-2': !collapsed }"
        :title="name"
    >
        <font-awesome-icon :icon="icon" class="shrink-0 w-5" />
        <Transition name="fade">
            <span v-if="!collapsed">
                {{ name }}
            </span>
        </Transition>
    </RouterLink>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.1s;
}

.fade-enter,
.fade-leave-to {
  opacity: 0;
}
</style>
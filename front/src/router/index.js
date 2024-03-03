import { createRouter, createWebHistory } from "vue-router";
import { RouteGenerator } from "./RouteGenerator";

const router = createRouter({
    history: createWebHistory(import.meta.env.VITE_BASE_URL),
    routes: RouteGenerator.getAllRoutes(),
});

export default router;
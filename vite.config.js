import { defineConfig } from "vite";
import laravel, { refreshPaths } from "laravel-vite-plugin";
import livewire, { defaultWatches } from "@defstudio/vite-livewire-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: false,
        }),

        livewire({
            refresh: ["resources/css/app.css"],
            watch: [
                ...defaultWatches,
                ...refreshPaths,
                "app/Http/Livewire/**",
                "app/Forms/Components/**",
            ],
        }),
    ],
});

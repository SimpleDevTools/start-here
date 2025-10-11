import.meta.glob(["../images/**"]);

import { livewire_hot_reload } from "virtual:livewire-hot-reload";
livewire_hot_reload();

import "../../vendor/wire-elements/pro/resources/js/overlay-component.js";

Alpine.directive("open-modal", (el, { expression }, { evaluate }) => {
    el.addEventListener("click", () => {
        Livewire.dispatch("modal.open", {
            component: evaluate(expression),
        });
    });
});

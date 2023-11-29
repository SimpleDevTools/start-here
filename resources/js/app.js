import "./bootstrap";

import.meta.glob(["../images/**"]);

import {
    Livewire,
    Alpine,
} from "../../vendor/livewire/livewire/dist/livewire.esm";
Livewire.start();

import { livewire_hot_reload } from "virtual:livewire-hot-reload";
livewire_hot_reload();

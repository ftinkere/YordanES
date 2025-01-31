import './bootstrap';
import { x2i } from "./x2i.js";

// Flux setting

$flux.appearance = 'system'

// functions

function x2i_input(name) {
    const el = document.getElementById(name);
    el.value = x2i(el.value)
    el.dispatchEvent(new Event('input', { bubbles: true }));
}
window.x2i_input = x2i_input;

import './bootstrap';
import { x2i } from "./x2i.js";

// functions

function x2i_input(name) {
    const els = document.getElementsByName(name);
    els.forEach(el => {
        el.value = x2i(el.value)
        el.dispatchEvent(new Event('input', { bubbles: true }));
    })
}
window.x2i_input = x2i_input;

function x2i_select() {
    let selection = window.getSelection();
    if (!selection.rangeCount) return;

    let range = selection.getRangeAt(0);
    let selectedText = selection.toString();

    if (selectedText.trim() === "") return;

    let newText = x2i(selectedText);

    // Создаем новый текстовый узел и заменяем выделенный текст
    let textNode = document.createTextNode(newText);
    range.deleteContents();
    range.insertNode(textNode);

    // Сбрасываем выделение
    selection.removeAllRanges();
}
window.x2i_select = x2i_select;


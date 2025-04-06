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

function romanize(num) {
    if (isNaN(num))
        return NaN;
    var digits = String(+num).split(""),
        key = ["","C","CC","CCC","CD","D","DC","DCC","DCCC","CM",
            "","X","XX","XXX","XL","L","LX","LXX","LXXX","XC",
            "","I","II","III","IV","V","VI","VII","VIII","IX"],
        roman = "",
        i = 3;
    while (i--)
        roman = (key[+digits.pop() + (i * 10)] || "") + roman;
    return Array(+digits.join("") + 1).join("M") + roman;
}
window.romanize = romanize;

document.addEventListener('scrollTop', function () {
    window.scrollTo({ top: 0, behavior: 'smooth' });
});

let touchStartX = 0;
let touchEndX = 0;
const sidebarToggle = document.getElementById('sidebar-toggle');

document.addEventListener('touchstart', (e) => {
    touchStartX = e.changedTouches[0].screenX;
});

document.addEventListener('touchend', (e) => {
    touchEndX = e.changedTouches[0].screenX;
    handleGesture();
});

function handleGesture() {
    const swipeDistance = touchEndX - touchStartX;

    if (swipeDistance > 70) {
        // Свайп вправо с левого края экрана
        sidebarToggle.click();
    }

    // Если нужно — добавь условие для свайпа обратно (закрытие сайдбара)
    if (swipeDistance < -70) {
        // Свайп влево
        sidebarToggle.click();
    }
}

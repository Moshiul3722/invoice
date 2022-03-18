require("./bootstrap");
require("jquery-countdown");

window.$ = window.jQuery = require("jquery");

import Alpine from "alpinejs";

window.Alpine = Alpine;

Alpine.start();

jQuery(document).ready(function () {
    setTimeout(() => {
        $("#status_message").slideUp("slow");
    }, 2000);
});

CKEDITOR.replace("description");

let filter_btn = document.querySelector("#task_filter_btn");
let task_filter = document.querySelector("#task_filter");

filter_btn.onclick = () => {
    // alert(task_filter.innerHTML)
    // task_filter.classList.toggle('open');

    // elem.classList.toggle("open");
    if (filter_btn.innerHTML === "Filter") {
        filter_btn.innerHTML = "Close Filter";
    } else if ((filter_btn.innerHTML = "Close Filter")) {
        filter_btn.innerHTML = "Filter";
    }
    // $(this).val("hide")
};

$("#task_filter_btn").on("click", function () {
    $("#task_filter").slideToggle("slow");
});

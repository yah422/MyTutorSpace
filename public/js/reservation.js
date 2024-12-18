import flatpickr from "flatpickr";

flatpickr("#dateDebut", {
    enableTime: true,
    dateFormat: "Y-m-d H:i",
    minDate: new Date(),
});

flatpickr("#dateFin", {
    enableTime: true,
    dateFormat: "Y-m-d H:i",
    minDate: new Date(),
});
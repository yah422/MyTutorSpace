// Sélection des éléments et initialisation des variables
const csrfToken = $('meta[name="csrf-token"]').attr('content');

const $startDate = $("#appointment_startDate");
const $availableRdv = $("#available-rdv");
const $selectedSlot = $("#selectedSlot");
const $errorMsg = $("#date_error");

let selectedLabel = null;

$(document).ready(function () {
    initFlatpickr();
    handleSlotSelection();
    handleLeconSelection();

    // Annulation d'un RDV sur la vue profil
    $(document).on('click', '.cancel_appointment', function (e) {
        e.preventDefault();
        const appointmentId = $(this).data('id');

        if (confirm("Êtes-vous sûr de vouloir annuler ce rendez-vous ?")) {
            cancelAppointment(appointmentId, csrfToken);
        }
    });

    // Validation et soumission du formulaire
    $("#appointment_save").on("click", function (event) {
        $(".error_msg").text("");
        $(".data").removeClass("input_invalid");

        let isValid = true;

        $(".data").each(function () {
            if ($(this).val().trim() === "") {
                $(this).addClass("input_invalid");
                isValid = false;
            }
        });

        const name = $("#appointment_name").val().trim();
        if (name.length < 2 || name.length > 50) {
            $("#name_error").text("Le nom doit contenir entre 2 et 50 caractères");
            $("#appointment_name").addClass("input_invalid");
            isValid = false;
        }

        const email = $("#appointment_email").val().trim();
        if (!validateEmail(email)) {
            $("#email_error").text("Adresse email invalide !");
            $("#appointment_email").addClass("input_invalid");
            isValid = false;
        }

        const startDateValue = $startDate.val().trim();
        if (startDateValue === "") {
            $errorMsg.text("Veuillez sélectionner une date et une heure !");
            $startDate.addClass("input_invalid");
            isValid = false;
        }

        if (isValid) {
            $("#appointment_form").submit();
        }
    });
});

// Initialisation de Flatpickr pour la sélection de date et d'heure
function initFlatpickr() {
    $startDate.flatpickr({
        enableTime: true, // Activer la sélection de l'heure
        dateFormat: "Y-m-d H:i", // Format avec date et heure
        time_24hr: true, // Format 24 heures
        minDate: "today", // Empêcher la sélection de dates passées
        onChange: function (selectedDates, dateStr, instance) {
            // Réinitialise les erreurs à chaque changement
            $errorMsg.text("");
            $startDate.removeClass("input_invalid");
        },
    });
}

// Gestion des créneaux horaires
function handleSlotSelection() {
    $availableRdv.on("change", "input[type='radio']", function () {
        const $input = $(this);
        const slot = $input.val();

        if ($input.is(":checked")) {
            if (selectedLabel) {
                $(selectedLabel).removeClass("showRadioClass");
            }
            $input.closest('label').addClass("showRadioClass");
            selectedLabel = $input.closest('label');
            $selectedSlot.val(slot);
        }
    });
}

// Fonction pour annuler un rendez-vous
function cancelAppointment(appointmentId, csrfToken) {
    $.ajax({
        url: `/profil/appointment/${appointmentId}/delete`,
        method: "DELETE",
        headers: { "X-CSRF-TOKEN": csrfToken },
        success: function (data) {
            if (data.success) {
                $(`#appointment-${appointmentId}`).remove();
            } else {
                alert(data.message || "Erreur lors de l'annulation.");
            }
        },
        error: function () {
            alert("Une erreur est survenue.");
        },
    });
}

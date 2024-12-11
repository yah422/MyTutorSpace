document.addEventListener('DOMContentLoaded', function () {
    const button = document.getElementById('reservation-button');
    const modal = document.getElementById('reservation-modal');
    const closeModal = document.getElementById('close-modal');

    if (button && modal && closeModal) {
        button.addEventListener('click', function () {
            modal.classList.remove('hidden'); // Affiche le modal
        });

        closeModal.addEventListener('click', function () {
            modal.classList.add('hidden'); // Cache le modal
        });

        // Fermer le modal en cliquant à l'extérieur
        window.addEventListener('click', function (event) {
            if (event.target === modal) {
                modal.classList.add('hidden');
            }
        });
    }
});

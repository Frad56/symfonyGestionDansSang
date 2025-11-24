// Confirmation avant annulation d'un rendez-vous
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".annuler-btn").forEach(btn => {
        btn.addEventListener("click", function (e) {
            if (!confirm("Êtes-vous sûr de vouloir annuler ce rendez-vous ?")) {
                e.preventDefault();
            }
        });
    });
});

document.addEventListener("DOMContentLoaded", () => {
    const teacherRows = document.querySelectorAll(".teacher-row");
    const modal = document.getElementById("contact-modal");
    const closeModal = document.querySelector(".modal-close");
    const emailDisplay = document.getElementById("teacher-email");
    const emailLink = document.getElementById("email-link");
    const copyBtn = document.getElementById("copy-email");

    /* === Animación al hacer scroll === */
    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add("visible");
            }
        });
    }, { threshold: 0.2 });

    teacherRows.forEach(row => observer.observe(row));

    /* === Modal de contacto === */
    document.querySelectorAll(".btn-contact").forEach(btn => {
        btn.addEventListener("click", e => {
            const email = e.currentTarget.getAttribute("data-email");
            emailDisplay.textContent = email;
            emailLink.href = `mailto:${email}`;
            modal.setAttribute("aria-hidden", "false");
        });
    });

    closeModal.addEventListener("click", () => modal.setAttribute("aria-hidden", "true"));
    modal.querySelector(".modal-backdrop").addEventListener("click", () => modal.setAttribute("aria-hidden", "true"));

    /* === Copiar correo === */
    copyBtn.addEventListener("click", () => {
        navigator.clipboard.writeText(emailDisplay.textContent);
        copyBtn.innerHTML = `<i class="bi bi-check-circle-fill"></i> ¡Copiado!`;
        setTimeout(() => {
            copyBtn.innerHTML = `<i class="bi bi-clipboard-fill"></i> Copiar Email`;
        }, 2000);
    });
});

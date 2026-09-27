document.addEventListener("DOMContentLoaded", () => {
    document.addEventListener("DOMContentLoaded", () => {
        document.querySelectorAll(".password-toggle").forEach((button) => {
            button.addEventListener("click", () => {
                const input = document.getElementById(button.dataset.target);

                if (!input) return;

                input.type = input.type === "password" ? "text" : "password";
                button.textContent = input.type === "password" ? "◉" : "○";
            });
        });

        const fileInput = document.getElementById("foto_ktm");
        const fileName = document.getElementById("file-name");

        if (fileInput && fileName) {
            fileInput.addEventListener("change", () => {
                fileName.textContent = fileInput.files.length
                    ? fileInput.files[0].name
                    : "Pilih foto KTM";
            });
        }

        // Transisi pindah halaman login <-> daftar
        const switchLink = document.querySelector(".auth-switch a");
        const authPage = document.querySelector(".auth-page");

        if (switchLink && authPage) {
            switchLink.addEventListener("click", (e) => {
                e.preventDefault();
                const target = switchLink.getAttribute("href");
                authPage.classList.add("is-leaving");
                setTimeout(() => {
                    window.location.href = target;
                }, 250);
            });
        }
    });
    document.querySelectorAll(".password-toggle").forEach((button) => {
        button.addEventListener("click", () => {
            const input = document.getElementById(button.dataset.target);

            if (!input) return;

            input.type = input.type === "password" ? "text" : "password";
            button.textContent = input.type === "password" ? "◉" : "○";
        });
    });

    const fileInput = document.getElementById("foto_ktm");
    const fileName = document.getElementById("file-name");

    if (fileInput && fileName) {
        fileInput.addEventListener("change", () => {
            fileName.textContent = fileInput.files.length
                ? fileInput.files[0].name
                : "Pilih foto KTM";
        });
    }
});

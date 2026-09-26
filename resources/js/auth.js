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
});

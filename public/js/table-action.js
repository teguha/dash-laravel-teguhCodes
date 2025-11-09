
    document.addEventListener("DOMContentLoaded", function () {

        // Event delegation untuk klik tombol ⋯
        document.addEventListener("click", function (e) {
            const toggleBtn = e.target.closest(".toggleMenuAction");
            const dropdown = e.target.closest(".dropdown-menu");

            // Tutup semua dropdown kalau klik di luar
            if (!toggleBtn && !dropdown) {
                document.querySelectorAll(".dropdown-menu").forEach(menu => menu.classList.add("hidden"));
                return;
            }

            // Kalau klik tombol toggle
            if (toggleBtn) {
                const parent = toggleBtn.closest(".action-dropdown");
                const menu = parent.querySelector(".dropdown-menu");

                // Tutup dropdown lain
                document.querySelectorAll(".dropdown-menu").forEach(m => {
                    if (m !== menu) m.classList.add("hidden");
                });

                // Hitung posisi tombol
                const rect = toggleBtn.getBoundingClientRect();
                menu.style.position = 'fixed';
                menu.style.top = `${rect.bottom -40}px`;
                menu.style.left = `${rect.left + rect.width / 2}px`;
                menu.style.transform = 'translateX(-50%)';

                // Toggle dropdown ini
                menu.classList.toggle("hidden");
            }
        });



    });

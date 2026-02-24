
    document.addEventListener("DOMContentLoaded", function () {

        // Event delegation untuk klik tombol ⋯
        document.addEventListener("click", function (e) {
            const toggleBtn = e.target.closest(".toggleMenuAction");
            const dropdown = e.target.closest(".dropdown-menu");

            // .closet => mencari element terdekat (diri sendiri atau parent) yang memiliki class .kelas
            // <div class="toggleMenuAction">
            //     <span>Click me</span>
            // </div>
            // Jadi tidak perlu memastikan klik persis di div, klik anaknya juga terdeteksi.

            // Tutup semua dropdown kalau klik di luar
            if (!toggleBtn && !dropdown) {
                // kalo punya 1 ekspresi bisa langsung, ta[pi jika banyak ekspresi atau statement bisa pakai return]
                document.querySelectorAll(".dropdown-menu").forEach(menu => menu.classList.add("hidden"));
                // document.querySelector(".dropdown").forEach((menu) => (menu.classList.add("hidden")))

                return;
            }

            // Kalau klik tombol toggle
            if (toggleBtn) {
                const parent = toggleBtn.closest(".action-dropdown");
                const menu = parent.querySelector(".dropdown-menu");

                // Tutup dropdown lain
                document.querySelectorAll(".dropdown-menu").forEach(m => {
                    if (m !== menu) m.classList.add("hidden"); // jika dropdown bukan yang di klik sekarang maka tutup
                });

                // Hitung posisi tombol
                const rect = toggleBtn.getBoundingClientRect(); //mendapatkan ukuran dan posisi tombol di layar
                menu.style.position = 'fixed';
                menu.style.top = `${rect.bottom -100}px`;
                menu.style.left = `${rect.left + rect.width / 2}px`;
                menu.style.transform = 'translateX(-50%)';

                // Toggle dropdown ini
                menu.classList.toggle("hidden");
                // ika dropdown saat ini tersembunyi, maka tampil
                // ika sudah tampil, maka disembunyikan
            }
        });



    });

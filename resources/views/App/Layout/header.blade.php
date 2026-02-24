<header class="bg-white shadow-sm sticky top-0 z-20">
    <div class="flex items-center justify-between px-4 py-4">
        <div class="flex items-center">
            <button id="toggleSidebarDesktop" class="hidden lg:block text-gray-600 hover:text-gray-800 mr-4">
                <i class="fas fa-bars text-2xl"></i>
            </button>
            <button id="toggleSidebarMobile" class="lg:hidden text-gray-600 hover:text-gray-800 mr-4">
                <i class="fas fa-bars text-2xl"></i>
            </button>
            <h1 class="text-2xl font-bold text-gray-800">CMS</h1>
        </div>
        
        <div class="flex items-center space-x-4">
            {{-- <button class="relative text-gray-600 hover:text-gray-800">
                <i class="fas fa-bell text-lg"></i>
                <span class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 rounded-full text-xs text-white flex items-center justify-center">3</span>
            </button> --}}

            @php
                $unreadNotifications = Auth::user()->unreadNotifications;
            @endphp
            <button class="relative text-gray-600 hover:text-gray-800" id="notification-button">
                <i class="fas fa-bell text-lg"></i>
                <span id="notification-count" class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 rounded-full text-xs text-white flex items-center justify-center">
                    {{$unreadNotifications->count() }}
                </span>

                    <!-- Layer animasi ping di belakang badge -->
                <span class="absolute -top-1 -right-1 w-4 h-4 rounded-full bg-red-500 opacity-75 animate-ping"></span>

            </button>

            <!-- Dropdown untuk menampilkan daftar notifikasi -->
            <div id="notification-dropdown" class="absolute top-10 right-5 mt-2 w-100 bg-white shadow-lg rounded-md p-4 hidden">
                <h3 class="text-sm font-semibold text-gray-700">Notifikasi</h3>
                <ul id="notification-list" class="mt-2">
                    <!-- Daftar notifikasi akan muncul di sini -->
                </ul>
            </div>

        

            @php
                $user = Auth::user();
            @endphp
            <div class="flex items-center gap-3">
                {{-- <img src="https://ui-avatars.com/api/?name=John+Smith&background=3b82f6&color=fff" class="w-5 h-5 rounded-full shadow-sm" alt="User"> --}}
                <button id="user-menu-button" class="flex items-center justify-center w-8 h-8 text-[12px] rounded-full bg-{{random_color($user->id)}}-500 text-white font-semibold">
                    {{get_initial($user->name)}}
                </button>
                <span class="text-sm font-medium text-gray-900">Hi , {{$user->name}}</span>

                <!-- DROPDOWN -->
                <div id="user-dropdown" 
                    class="hidden absolute right-5 top-14 w-60 bg-white rounded-md shadow-lg border border-gray-200 z-50">

                    <a href="{{ route('admin.auth.profile') }}" 
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fa fa-user w-5 h-5"></i>
                        Profile
                    </a>

                    <form action="{{ route('admin.auth.logout') }}" method="POST">
                        @csrf
                        <button type="submit" 
                            class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                            <i class="fa fa-sign-out text-red-500 w-5 h-5"></i>
                            Logout
                        </button>
                    </form>
                </div>

            </div>

        </div>
    </div>
</header>

<script>
    const btn = document.getElementById('user-menu-button');
    const dropdown = document.getElementById('user-dropdown');

    btn.addEventListener('click', function (e) {
        dropdown.classList.toggle('hidden');
    });

    // Klik di luar → dropdown tertutup
    document.addEventListener('click', function(e) {
        if (!btn.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.classList.add('hidden');
        }
    });
</script>


<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Mengambil jumlah notifikasi dan daftar notifikasi
        function loadNotifications() {
            fetch("/api/notifications/count")  // Pastikan URL ini sesuai dengan route di Laravel Anda
                .then(response => response.json())
                .then(data => {
                    // Update jumlah notifikasi yang belum dibaca
                    const notificationCount = document.getElementById("notification-count");
                    notificationCount.textContent = data.unread_count;

                    // Tampilkan daftar notifikasi
                    const notificationList = document.getElementById("notification-list");
                    notificationList.innerHTML = ""; // Kosongkan daftar notifikasi

                    // Jika ada notifikasi yang belum dibaca
                    if (data.notifications.length > 0) {
                        data.notifications.forEach(notification => {
                            const li = document.createElement("li");
                            li.classList.add("text-[12px]","font-[arial]" ,"text-gray-700", "py-1", "border-b");

                            // Menampilkan detail notifikasi
                            li.innerHTML = `
                                <a href="/notification/${notification.id}/read" class="block hover:bg-gray-100 p-2">
                                    <p><i class="fas fa-user text-[12px] text-gray-500 mr-3"></i> ${notification.data.data.description}</p>
                                    <small class="text-xs text-gray-500 pl-6">${new Date(notification.created_at).toLocaleString()}</small>
                                </a>
                            `;
                            notificationList.appendChild(li);
                        });

                        // Tambahkan link “View All” di bawah
                        const liViewAll = document.createElement("li");
                        liViewAll.classList.add("text-[12px]","font-[arial]","text-gray-700","py-2","text-center");

                        liViewAll.innerHTML = `
                            <a href="/notifications" class="block hover:bg-gray-100 p-2 font-medium text-blue-600">
                                View All Notifications
                            </a>
                        `;

                        notificationList.appendChild(liViewAll);
                        
                    } else {
                        notificationList.innerHTML = "<li class='text-gray-500 text-sm'>Tidak ada notifikasi baru</li>";
                    }
                })
                .catch(error => console.error("Error fetching notifications:", error));
        }

        // Load notifikasi saat halaman dimuat
        loadNotifications();

        // Toggle dropdown notifikasi saat tombol notifikasi diklik
        const notificationButton = document.getElementById("notification-button");
        const notificationDropdown = document.getElementById("notification-dropdown");
        
        notificationButton.addEventListener("click", () => {
            notificationDropdown.classList.toggle("hidden");
        });
    });
</script>
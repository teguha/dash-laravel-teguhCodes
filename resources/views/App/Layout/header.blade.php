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
            <button class="relative text-gray-600 hover:text-gray-800">
                <i class="fas fa-bell text-lg"></i>
                <span class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 rounded-full text-xs text-white flex items-center justify-center">3</span>
            </button>
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
@php
  
    $user = Auth::user();
    $re_role = App\Models\Auth\Role::find($user->role_id ?? 26);
    $userPerms = json_decode($re_role->permission ?? '[]', true);

    // dd($userPerms);

  $menu = [
    [
      'href' => 'admin.setting.log.index', 
      'group_link' => 'dash.home',
      'text_header' => 'Dashboard',
      'perm' => 'view-dashboard',
      'text_menu' => 'Dashboard',
      'icon' => 'fas fa-home',
      'is_multi' => false,
    ],

    [
      'href' => 'admin.setting.log.index', 
      'group_link' => 'admin.auth.*',
      'text_header' => '',
      'perm' => 'view-dashboard',
      'text_menu' => 'Analytics',
      'icon' => 'fas fa-chart-line',
      'is_multi' => false,
    ],

    [
      'href' => 'admin.perencanaan.index', 
      'group_link' => 'admin.perencanaan.*',
      'text_header' => '',
      'perm' => 'view-perencanaan',
      'text_menu' => 'Perencanaan',
      'icon' => 'fas fa-chart-line',
      'is_multi' => false,
    ],

    [
      'href' => [
        ['href' => 'admin.user.index', 'text_menu' => 'User', 'icon' => 'fas fa-user', 'group_link' => 'admin.user.*', 'perm' => 'view-auth-user'],
        ['href' => 'admin.auth.profile', 'text_menu' => 'Profile', 'icon' => 'fas fa-user', 'group_link' => 'admin.auth.*' , 'perm' => 'view-auth-profile'],   
        // ['href' => 'admin.auth.index', 'text_menu' => 'Update Password', 'icon' => 'fas fa-user', 'group_link' => 'admin.auth.*', 'perm' => 'auth-view'],
      ],
      'group_link' => 'admin.user.*',
      'text_header' => "Auth",
      'text_menu' => 'Auth',
      'icon' => 'fas fa-shield',
      'is_multi' => true,
    ],

    [
      'href' => [
        ['href' => 'admin.setting.role.index', 'text_menu' => 'Role', 'icon' => 'fas fa-cog', 'group_link' => 'admin.setting.role.*', 'perm' => 'view-setting-role'],   
        ['href' => 'admin.setting.permission.index', 'text_menu' => 'Permission', 'icon' => 'fas fa-cog', 'group_link' => 'admin.setting.permission.*', 'perm' => 'view-setting-permission'],
        ['href' => 'admin.setting.log.index', 'text_menu' => 'Log Activity', 'icon' => 'fas fa-cog', 'group_link' => 'admin.setting.log.*', 'perm' => 'view-setting-log'],
        ['href' => 'admin.setting.approval.flow.index', 'text_menu' => 'Flow Approval', 'icon' => 'fas fa-cog', 'group_link' => 'admin.setting.approval.*', 'perm' => 'view-setting-approval'],
      ],
      'group_link' => 'admin.setting.*',
      'text_header' => "",
      'text_menu' => 'Setting',
      'icon' => 'fas fa-cog',
      'is_multi' => true,
    ],

    [
      'href' => [
        ['href' => 'admin.structure.corp.index', 'text_menu' => 'Main Corporate','icon' => 'fas fa-chess-queen',  'group_link' => 'admin.structure.corp.*', 'perm' => 'view-structure'],   
        ['href' => 'admin.structure.direksi.index', 'text_menu' => 'Direksi','icon' => 'fas fa-chess-knight',  'group_link' => 'admin.structure.direksi.*', 'perm' => 'view-structure'],
        ['href' => 'admin.structure.bagian.index', 'text_menu' => 'Bagian', 'icon' => 'fas fa-chess-rook', 'group_link' => 'admin.structure.bagian.*', 'perm' => 'view-structure'],
        ['href' => 'admin.structure.position.index', 'text_menu' => 'Position', 'icon' => 'fas fa-chess-rook', 'group_link' => 'admin.structure.position.*', 'perm' => 'view-structure-position'],
      ],
      'group_link' => 'admin.structure.*',
      'text_header' => "Master",
      'text_menu' => 'Structure',
      'icon' => 'fas fa-chess-king',
      'is_multi' => true,
    ],
    
  ];

  $sidebar = json_decode(json_encode($menu));
  
@endphp

<nav class="space-y-2">
    @foreach ($sidebar as $item)

      @php
        $hasMenu = false;

        if($item->is_multi) {
            // Filter child menu berdasarkan permission
            $children = collect($item->href)->filter(function($child) use ($userPerms) {
                return isset($child->perm) && in_array($child->perm, $userPerms);
            });
            $hasMenu = $children->isNotEmpty();
        } else {
            $hasMenu = isset($item->perm) && in_array($item->perm, $userPerms);
        }

        //dd($hasMenu);
      @endphp

      

      @if($hasMenu)
        @if($item->text_header != '')
            <span class="ml-3 font-semibold text-[10px] leading-normal tracking-widest text-gray-400 mt-2 mb-2 title-menu">
                {{ $item->text_header }}
            </span>
        @endif

        @if(!$item->is_multi)
            {{-- Single menu --}}
            <a href="{{ route($item->href) }}" class="{{ Request::routeIs($item->group_link) ? 'flex items-center px-4 py-3 text-white bg-blue-400 rounded-lg' : 'flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg' }}">
                <i class="{{ $item->icon }} w-5"></i>
                <span class="ml-3 font-medium menu-text">{{ $item->text_menu }}</span>
            </a>
        @else
            {{-- Multi menu --}}
            <div class="menu-parent">
                <button class="flex items-center justify-between w-full px-4 py-3 text-gray-700 rounded-lg hover:bg-gray-100">
                    <div class="flex items-center">
                        <i class="{{ $item->icon }} w-5"></i>
                        <span class="ml-3 font-medium menu-text">{{ $item->text_menu }}</span>
                    </div>
                    <i class="fas fa-chevron-down text-xs transition-transform duration-200 menu-text"></i>
                </button>

                <div class="{{ Request::routeIs($item->group_link) ? 'submenu ml-4 mt-2 space-y-1' : 'submenu hidden ml-4 mt-2 space-y-1' }}">
                    @foreach ($children as $child)
                        <a href="{{ route($child->href) }}" class="{{ Request::routeIs($child->group_link) ? 'flex items-center px-4 py-2 text-white bg-blue-400 rounded-lg' : 'flex items-center px-4 py-2 text-gray-600 rounded-lg hover:bg-gray-50' }}">
                            <i class="{{ $child->icon }} text-xs w-5"></i>
                            <span class="ml-3 menu-text">{{ $child->text_menu }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    @endif
    @endforeach
</nav>

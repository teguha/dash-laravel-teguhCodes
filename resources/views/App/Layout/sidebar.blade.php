@php
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
      'href' => [
        ['href' => 'dash.home', 'text_menu' => 'Profile', 'icon' => 'fas fa-user', 'group_link' => 'dash.home' , 'perm' => 'auth-view'],   
        ['href' => 'admin.auth.index', 'text_menu' => 'Update Password', 'icon' => 'fas fa-user', 'group_link' => 'admin.auth.*', 'perm' => 'auth-view'],
      ],
      'group_link' => 'dash.home',
      'text_header' => "Auth",
      'text_menu' => 'Auth',
      'icon' => 'fas fa-users',
      'is_multi' => true,
    ],

    [
      'href' => [
        ['href' => 'admin.setting.role.index', 'text_menu' => 'Role', 'icon' => 'fas fa-cog', 'group_link' => 'admin.setting.role.*', 'perm' => 'role-view'],   
        ['href' => 'admin.setting.permission.index', 'text_menu' => 'Permission', 'icon' => 'fas fa-cog', 'group_link' => 'admin.setting.permission.*', 'perm' => 'permission-view'],
        ['href' => 'admin.setting.log.index', 'text_menu' => 'Log Activity', 'icon' => 'fas fa-cog', 'group_link' => 'admin.setting.log.*', 'perm' => 'log-view'],
        ['href' => 'admin.setting.approval.index', 'text_menu' => 'Flow Approval', 'icon' => 'fas fa-cog', 'group_link' => 'admin.setting.approval.*', 'perm' => 'approval-view'],
      ],
      'group_link' => 'admin.setting.*',
      'text_header' => "",
      'text_menu' => 'Setting',
      'icon' => 'fas fa-cog',
      'is_multi' => true,
    ],

    [
      'href' => [
        ['href' => 'admin.structure.mainCorp.index', 'text_menu' => 'Main Corporate','icon' => 'fas fa-cog',  'group_link' => 'admin.structure.mainCorp.*', 'perm' => 'main-corporate-view'],   
        ['href' => 'admin.structure.subCorp.index', 'text_menu' => 'Sub Corporate','icon' => 'fas fa-cog',  'group_link' => 'admin.structure.subCorp.*', 'perm' => 'sub-corporate-view'],
        ['href' => 'admin.structure.bagian.index', 'text_menu' => 'Bagian', 'icon' => 'fas fa-cog', 'group_link' => 'admin.structure.bagian.*', 'perm' => 'bagian-view'],
        ['href' => 'admin.structure.subBagian.index', 'text_menu' => 'Sub Bagian','icon' => 'fas fa-cog',  'group_link' => 'admin.structure.subBagian.*', 'perm' => 'sub-bagian-view'],
        ['href' => 'admin.structure.subSubBagian.index', 'text_menu' => 'Sub Sub Bagian', 'icon' => 'fas fa-cog', 'group_link' => 'admin.structure.subSubBagian.*', 'perm' => 'sub-sub-bagian-view'],
      ],
      'group_link' => 'admin.structure.*',
      'text_header' => "Master",
      'text_menu' => 'Structur',
      'icon' => 'fas fa-cog',
      'is_multi' => true,
    ],
    
  ];

  $sidebar = json_decode(json_encode($menu));
  
@endphp

<nav class="space-y-2">
    @foreach ($sidebar as $item)
        @if($item->text_header != '')
            <span class="ml-3 font-semibold text-[10px] leading-normal tracking-widest text-gray-400 mt-2 mb-2 title-menu">{{$item->text_header}}</span>
        @endif

        @if($item->is_multi == false)
            <a href="{{$item->href}}" class=" {{ Request::routeIs($item->group_link) ? 'flex items-center px-4 py-3 text-white bg-gradient-to-r from-yellow-600 to-yellow-400 rounded-lg transition-all hover:shadow-md text-sm' : 'flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 transition-all text-sm' }}">
                <i class="{{$item->icon}} w-5"></i>
                <span class="ml-3 font-medium menu-text">{{$item->text_menu}}</span>
            </a>
        @else
            <div class="menu-parent">
                <button class="flex items-center justify-between w-full px-4 py-3 text-gray-700 rounded-lg hover:bg-gray-100 transition-all text-sm">
                    <div class="flex items-center">
                        <i class="{{$item->icon}} w-5"></i>
                        <span class="ml-3 font-medium menu-text">{{$item->text_menu}}</span>
                    </div>
                    <i class="fas fa-chevron-down text-xs transition-transform duration-200 menu-text"></i>
                </button>

                <div class="{{ Request::routeIs($item->group_link) ? 'submenu ml-4 mt-2 space-y-1' : 'submenu hidden ml-4 mt-2 space-y-1' }}">
                    @foreach ($item->href as $child)
                        @if(isset($child->text_menu))
                            <a href="{{$child->href}}" class="{{ Request::routeIs($child->href) ? 'flex items-center px-4 py-2 text-white bg-gradient-to-r from-yellow-600 to-yellow-400 rounded-lg transition-all text-sm' : 'flex items-center px-4 py-2 text-gray-600 rounded-lg hover:bg-gray-50 transition-all text-sm'}} ">
                                <i class="{{$child->icon}} text-xs w-5"></i>
                                <span class="ml-3 menu-text">{{$child->text_menu}}</span>
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif
    @endforeach
</nav>

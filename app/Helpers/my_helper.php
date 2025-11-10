<?php

if (!function_exists('greet_user')) {
    function greet_user($name)
    {
        return "Halo, " . ucfirst($name) . "!";
    }
}

if (!function_exists('datatable_user_time')) {
    function datatable_user_time($user, $time)
    {
        $name = $user?->name ?? 'System';
        $waktu = optional($time)
            ->timezone(config('app.timezone'))
            ->locale('id')
            ->translatedFormat('d M Y H:i');

        return '
        <td class="px-6 py-4 border-r border-gray-100">
            <div class="flex flex-col gap-2">
                <div class="flex items-center gap-2">
                    <i class="fa fa-user text-gray-500 text-sm"></i>
                    <span class="text-sm text-gray-700 font-medium">'.e($name).'</span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fa fa-clock text-gray-400 text-sm"></i>
                    <span class="text-sm text-gray-500">'.e($waktu).'</span>
                </div>
            </div>
        </td>
        ';
    }
}

if (!function_exists('datatable_user_time')) {
    function simple_track($item)
    {
        $name_created = $item->re_created_by?->name ?? 'System';
        $time_created = optional($item->created_at)
            ->timezone(config('app.timezone'))
            ->locale('id')
            ->translatedFormat('d M Y H:i');

        if($item->updated_at){
            $name_updated = $item->re_updated_by?->name ?? 'System';
            $time_updated = optional($item->updated_at)
            ->timezone(config('app.timezone'))
            ->locale('id')
            ->translatedFormat('d M Y H:i');

        }

        $html = ' <div class="relative">
                    <div class="absolute left-6 top-0 bottom-0 w-0.5 bg-gray-200"></div>
                    <div class="space-y-6">
                    
                        <div class="relative flex gap-4">
                            <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center font-bold z-10">
                                <i class="fa fa-clock text-blue-500"></i>
                            </div>
                            <div class="flex-1 pb-8">
                                <h3 class="font-bold text-gray-900 mb-2">'.e($item->name).'</h3>
                                <p class="text-sm text-gray-600 mb-2">'.e($name_created).'</p>
                                <p class="text-sm text-gray-500">'.e($time_created).'</p>
                            </div>
                        </div>';
        if($item->updated_at){
            $name_updated = $item->re_updated_by?->name ?? 'System';
            $time_updated = optional($item->updated_at)
            ->timezone(config('app.timezone'))
            ->locale('id')
            ->translatedFormat('d M Y H:i');

            $html += '  <div class="relative flex gap-4">
                            <div class="w-12 h-12 bg-purple-500 rounded-full flex items-center justify-center text-white font-bold z-10">
                                <i class="fa fa-clock text-blue-500"></i>
                            </div>
                            <div class="flex-1 pb-8">
                                <h3 class="font-bold text-gray-900 mb-2">'.e($item->name).'</h3>
                                <p class="text-sm text-gray-600 mb-2">'.e($name_updated).'</p>
                                <p class="text-sm text-gray-500">'.e($time_updated).'</p>
                            </div>
                        </div>
                    </div>
                </div>';
        }else{
            $html += '</div></div>';
        }

        return $html;
    }
}






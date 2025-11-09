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





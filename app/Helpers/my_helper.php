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

        $html = ' 
            <div class="relative">
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

            $html .= '  <div class="relative flex gap-4">
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
            $html += '
                </div>
            </div>
            ';
        }

        return $html;
    }
}

// Get initial user
if(!function_exists('get_initial')){
    function get_initial($user){
        $words = explode(' ',$user);
        $init = '';

        foreach($words as $i => $w){
            if($i > 1){
                break;
            }
            $init .=strtoupper($w[0]);
        }
        
        if(count($words) == 1 AND !empty($w[1])){
            $init .=strtoupper($w[1]);
        }
        
        return $init;
    }
   
}

// Get number to words
if (!function_exists('numberToWords')) {
    function numberToWords($number)
    {
        $words = [
            '',
            'Satu',
            'Dua',
            'Tiga',
            'Empat',
            'Lima',
            'Enam',
            'Tujuh',
            'Delapan',
            'Sembilan',
            'Sepuluh',
            'Sebelas',
        ];

        if ($number == 0) {
            return '';
        }
        
        if ($number < 12) {
            return $words[$number];
        } elseif ($number < 20) {
            return $words[$number - 10] . ' Belas';
        } elseif ($number < 100) {
            return $words[(int) ($number / 10)] . ' Puluh ' . $words[$number % 10];
        } elseif ($number < 200) {
            return 'Seratus ' . numberToWords($number - 100);
        } elseif ($number < 1000) {
            return $words[(int) ($number / 100)] . ' Ratus ' . numberToWords($number % 100);
        } elseif ($number < 2000) {
            return 'Seribu ' . numberToWords($number - 1000);
        } elseif ($number < 1000000) {
            return numberToWords((int) ($number / 1000)) . ' Ribu ' . numberToWords($number % 1000);
        } elseif ($number < 1000000000) {
            return numberToWords((int) ($number / 1000000)) . ' Juta ' . numberToWords($number % 1000000);
        } elseif ($number < 1000000000000) {
            return numberToWords((int) ($number / 1000000000)) . ' Miliar ' . numberToWords($number % 1000000000);
        }

        return 'Number out of range';
    }
}

// Helper function to encrypt text
if (!function_exists('cryptText')) {
    function cryptText($plainText) 
    {
        $base64 = base64_encode($plainText);
        $base64url = strtr($base64, '+/=', '-_,');
        return rtrim($base64url, ','); // Trim trailing comma
    }
}

// Helper function to decrypt text
if (!function_exists('decryptText')) {
    function decryptText($plainText)
    {
        $base64url = strtr($plainText, '-_,', '+/=');
        $base64 = base64_decode($base64url);
        return $base64;
    }
}

// Generate format date
if (!function_exists('format_date')) {
    function format_date($date, $format = 'd M Y H:i')
    {
        return \Carbon\Carbon::parse($date)->format($format);
    }
}

// Generate sort text
if (!function_exists('short_text')) {
    function short_text($text, $limit = 50)
    {
        return strlen($text) > $limit ? substr($text, 0, $limit) . '...' : $text;
    }
}

// Generate random color
if (!function_exists('random_color')) {
    function random_color($id)
    {
        $val = $id < 10 ? $id : $id % 10;
        $colors = ['red', 'blue', 'green', 'yellow', 'purple', 'pink',  'cyan', 'orange', 'brown', 'red'];
        return $colors[$val];
    }
}

// Generate reffcode
if(!function_exists('generateReffCode')){
    
    function generateReffCode($initial, $lastData){
        $limitDigits = 5;

        // $lastData = RegistrationEvent::where('booking_code', 'LIKE', '%'.$initial.'%')
        // ->orderByDesc('id')
        // ->first();
        if($lastData)
        {
            $lastNumber = (int) substr($lastData->booking_code, strlen($initial));
            $newNumber = $lastNumber + 1;

            // Buat kode baru dengan padding nol
            $code = $initial . str_pad($newNumber, $limitDigits, "0", STR_PAD_LEFT);

            return [
                'code'          => $code,
                'unique_code'   => $newNumber
            ];
        }
        else
        {
            $code = $initial.'00001';
        }
        
        return [
            'code'          => $code,
            'unique_code'   => 00001
        ];
    
    }
}






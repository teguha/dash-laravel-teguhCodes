<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Symfony\Component\HttpFoundation\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;
use App\Models\Master\Structure;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Psy\Util\Str;

class AjaxController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function searchStructure( Request $request){
        $search = $request->input('q');
        $datas = Structure::where('level','<>' ,'main_corp')->
        when(!empty($search), function($d) use ($search){
            $d->where('name', 'like', "%{$search}%");
        })
        ->get()
        ->map(function($item){
            return [
                'id' => $item->id,
                'text' => $item->name,
                'level' => $item->level
            ];
        });

        if(!empty($datas)){
            $results = [
                [
                    'text' => 'Direksi',
                    'children' => $datas->where('level', 'direksi')->values()
                ],
                [
                    'text' => 'Bagian',
                    'children' => $datas->where('level', 'bagian')->values()
                ]
            ];
        }else{
            $results = [];
        }

        return response()->json($results);
    }


    public function getNotificationCount()
    {
        $unreadNotificationsQuery = User::find(Auth::id())
            ->unreadNotifications()
            ->orderBy('created_at', 'desc')->limit(5);

        $unreadCount = $unreadNotificationsQuery->count();
        $unreadList  = $unreadNotificationsQuery->get();

        return response()->json([
            'unread_count' => $unreadCount,
            'notifications' => $unreadList,
        ]);

    }


    public function read($id)
    {
        $notification = auth()->user()->notifications()->where('id', $id)->first();
        if ($notification) {
            $notification->markAsRead();  // ini akan set read_at
            
            // Redirect ke URL asli dari notifikasi
            $url = $notification->data['data']['url'] ?? '/';
            return redirect($url);
        }

        return redirect()->back(); // fallback
    }


    
} 

//    public function searchStructure2(Request $request)
// {
//     $search = $request->input('q');

//     // Ambil semua direksi
//     $structs = Structure::with(['direksi.bagian'])
//         ->where('level', 'direksi')
//         ->whereHas('direksi.bagian', function($q) use ($search) {
//             $q->where('name', 'like', "%{$search}%");
//         })->orWhereHas('direksi', function($q) use ($search){
//             $q->where('name', 'like', "%{$search}%");
//         })
//         ->get();

    

//     $results = [];

//     foreach ($structs as $struct) {
//         $children = [];
//         // dd($struct);
//         foreach ($struct->direksi as $dir) {
//             // Tambahkan direksi
//             $children[] = [
//                 'id' => $dir->id,
//                 'text' => $dir->name
//             ];
            
//             // dari direksi punya child bagian
//             foreach ($dir->bagian as $b) {
//                 if (stripos($b->name, $search) !== false) {
//                     // if (strpos($b->id, 'B-') === 0) {
//                     //     $children[] = [
//                     //         'id' => $b->id,
//                     //         'text' => '<strong>' . $b->name . '</strong>'  // Membuat teks menjadi tebal
//                     //     ];
//                     // } else {
//                         $children[] = [
//                             'id' => $b->id,
//                             'text' => $b->name
//                         ];
//                     // }
//                 }
//             }
//         }

//         if (!empty($children)) {
//             $results[] = [
//                 'text' => $struct->name,
//                 'children' => $children
//             ];
//         }
//     }

//     return response()->json($results);
// }
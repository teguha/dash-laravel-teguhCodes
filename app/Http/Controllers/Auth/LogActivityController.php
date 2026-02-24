<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Symfony\Component\HttpFoundation\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Auth\ActivityLog;
use App\Models\Auth\Log;
use App\Models\User;
use App\Models\Auth\Role;
use App\Models\Auth\Permission;

class LogActivityController extends BaseController
{

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index()
    {
        return view('Auth.LogActivity.index'); // blade utama
    }

    // get data
    public function getData(Request $request)
    {
        $query = ActivityLog::with('re_user');

        // Filter pencarian
        if ($search = $request->search) {
            $query->where(function ($q) use ($search) {
                $q->where('action', 'like', "%$search%");
            });
        }

        if(isset($request->date_start) && isset($request->date_end)){
            $query->whereBetween('created_at', [$request->date_start, $request->date_end]);
        }

        // Pagination manual
        $perPage = 10;
        $page = $request->get('page', 1);

        $sortBy = match($request->sort_by ?? '') {
            'action'        => 'action',
            'updated'       => 'updated_at',
            default         => 'updated_at',
        };

        // Tentukan arah sort, default 'desc'
        $sortDir = $request->sort_dir ?? 'desc';

        // Jalankan query dengan orderBy, baru paginate
        $data = $query->orderBy($sortBy, $sortDir)->paginate($perPage, ['*'], 'page', $page);
        

        return response()->json([
            'data' =>$data->map(function ($item) {

                if(strtolower($item->action) == 'create'){
                    $color = 'blue';
                } elseif(strtolower($item->action) == 'update'){
                    $color = 'yellow';
                } elseif(strtolower($item->action) == 'delete'){
                    $color = 'red';
                } elseif(strtolower($item->action) == 'approve'){
                    $color = 'purple';
                } else{
                    $color = 'gray';
                }

                $parts = explode("\\", $item->model);
                $last = end($parts);

                return [
                    'id'                => $item->id,
                    'user'              => $item->re_user ? $item->re_user->name : 'System',
                    'initial'           => $item->re_user? get_initial($item->re_user->name) : 'S',
                    'user_color'        => $item->re_user? random_color($item->re_user->id) : 'gray',
                    'action'            => ucfirst($item->action),
                    'color'             => $color,
                    'menu'              => ucfirst($last),
                    'last_data'         => is_string($item->before)
                                        ? json_decode($item->before, true)
                                        : ($item->before ?? []),

                    'update'            => is_string($item->after)
                                        ? json_decode($item->after, true)
                                        : ($item->after ?? []),

                    'updated_at'        => datatable_user_time($item->re_updated_by ?? $item->re_created_by, $item->updated_at ?? $item->created_at),
                ];
            }),
            'pagination' => [
                'current_page'          => $data->currentPage(),
                'per_page'              => $data->perPage(),
                'last_page'             => $data->lastPage(),
                'total'                 => $data->total(),
                'from'                  => $data->firstItem(),
                'to'                    => $data->lastItem(),
            ]
        ]);
    }


    public function show($id){
        $data = ActivityLog::find($id);
        
        $parts = explode("\\", $data->model);
        $last = end($parts);
        return response()->json([
            'user' => $data->re_user? $data->re_user->name : 'System',
            'action' => $data->action,
            'menu' => $last,
            'before' => is_string($data->before)
                        ? json_decode($data->before, true)
                        : ($data->before ?? []),
            'after' => is_string($data->after)
                    ? json_decode($data->after, true)
                    : ($data->after ?? [])
        ]);
    }




}

<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use App\Models\User;
use App\Models\Auth\Role;
use App\Models\Auth\ActivityLog;
use Illuminate\Support\Facades\Hash;
use App\Models\Auth\Permission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Request;

class UserController extends BaseController
{

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index()
    {
        return view('Auth.User.index'); // blade utama
    }

    // get data
    public function getData(Request $request)
    {
        $query = User::with('re_role', 're_structure');

        // filter search
        if ($search = $request->search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%");
            });
        }

        // Filter tambahan (role, status, dsb)
        if ($role = $request->role) {
            $query->where('role_id', $role);
        }

        if(isset($request->date_start) && isset($request->date_end)){
            $query->whereBetween('created_at', [$request->date_start, $request->date_end]);
        }

        // Pagination manual
        $perPage = 10;
        $page = $request->get('page', 1);

        $sortBy = match($request->sort_by ?? '') {
            'name'      => 'name',
            'updated'   => 'updated_at',
            'role'      => 'role',
            default     => 'id',
        };

        // Tentukan arah sort, default 'desc'
        $sortDir = $request->sort_dir ?? 'desc';

        // Jalankan query dengan orderBy, baru paginate
        if($sortBy == 'role'){
            $data = $query->select('users.*')->leftJoin('set_role', 'users.role_id', '=', 'set_role.id')->orderBy('set_role.name', $sortDir)
            ->paginate($perPage, ['*'], 'page', $page);
        }else{
            $data = $query->orderBy($sortBy, $sortDir)->paginate($perPage, ['*'], 'page', $page);
        }



        return response()->json([
            'data' =>$data->map(function ($item) {

                $struct_level = $item->re_structure?->level ?? '';
                if($struct_level == 'direksi'){
                    $struct_color = 'green';
                }elseif($struct_level == 'bagian'){
                    $struct_color = 'yellow';
                }else{
                    $struct_color = 'blue';
                }

                return [
                    'id'                => $item->id,
                    'name'              => ucwords($item->name),
                    'initial'           => get_initial($item->name),
                    'color'             => random_color($item->id),
                    'email'             => $item->email,
                    'role'              => $item->re_role? $item->re_role->name : 'none',
                    'structure'         => $item->re_structure ? ucfirst($item->re_structure->level).' '.ucfirst($item->re_structure?->name) : '-',
                    'structure_color'   => $struct_color,
                    'status'            => ucfirst($item->status),
                    'phone'             => $item->phone ?? '-',
                    'updated_at'        => datatable_user_time($item->re_updated_by ?? $item->re_created_by, $item->updated_at ?? $item->created_at),
                ];
            }),
            'pagination' => [
                'current_page'  => $data->currentPage(),
                'per_page'      => $data->perPage(),
                'last_page'     => $data->lastPage(),
                'total'         => $data->total(),
                'from'          => $data->firstItem(),
                'to'            => $data->lastItem(),
            ]
        ]);
    }

    // private function activityLog($userId,$action, $model, $recordId, $before, $after){
    //     // Example
    //     // ActivityLog::create([
    //     //     'user_id'  => auth()->id(),
    //     //     'action'   => 'approval',
    //     //     'model'    => Order::class,
    //     //     'model_id' => $order->id,
    //     //     'before'   => ['status' => 'pending'],
    //     //     'after'    => ['status' => 'approved'],
    //     // ]);

    //     ActivityLog::create([
    //         'user_id'  => $userId,
    //         'action'   => $action,
    //         'model'    => $model,
    //         'model_id' => $recordId,
    //         'before'   => $before ? $before : null,
    //         'after'    => $after ? $after : null,
    //     ]);
    // }

    // store data
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users',
            'phone'     => 'required',
        ]);

        // check xss data
        if(has_xss($request)) {
             return response()->json([
                'success' => false,
                'message' => 'Failed to update data',
                'errors'  => '', // <-- ini kuncinya
                'fields'  => '', // opsional: hanya nama field yg error
            ], 422);
        }

        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => 'Failed to add data',
                'errors'  => $validator->errors(), // <-- ini kuncinya
                'fields'  => $validator->errors()->keys(), // opsional: hanya nama field yg error
            ], 422);
        }

        DB::beginTransaction();
        try {
            $user = Auth::user();
            // Simpan user baru
            $userData = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'phone'    => $request->phone,
                'password' => Hash::make($request->password),
                'role_id'  => $request->role,
                'status'   => $request->status_user,
                'struct_id' => $request->position,
                'created_by' => $user->id,
                'updated_by' => $user->id
            ]);

            DB::commit();
            // $this->ActivityLog(auth()->id(), 'create', User::class , $userData->id, null, $userData->toArray());

            return response()->json([
                'success' => true,
                'message' => 'Success add data',
            ]);

        } catch (\Exception $e) {
            DB::rollBack(); 
            return response()->json([
                'success' => false,
                'message' => 'Failed add data'.$e->getMessage(),
            ], 500);
        }
    }

    // edit data
    public function edit($id){
        $user = User::find($id);
        return response()->json([
            'id'        => $user->id,
            'name'      => $user->name,
            'phone'     => $user->phone,
            'email'     => $user->email,
            'status'    => $user->status,
            'role'      => $user->role_id,
            'position'  => $user->struct_id ?? '',
            // 'structure_text' => $user->re_structure?->name ?? ''
        ]);
    }

    // update data
    public function update(Request $request, $id){
        $user = User::find($id);
        $validator = Validator::make($request->all(), [
            'name'              => 'required|string|max:255',
            'email'             => 'required|email|unique:users,email,' . $id,
            'phone'             => 'required',
        ]);

        // check xss data
        if(has_xss($request)) {
             return response()->json([
                'success' => false,
                'message' => 'Failed to update data',
                'errors'  => '', // <-- ini kuncinya
                'fields'  => '', // opsional: hanya nama field yg error
            ], 422);
        }

        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => 'Failed to update data',
                'errors'  => $validator->errors(), // <-- ini kuncinya
                'fields'  => $validator->errors()->keys(), // opsional: hanya nama field yg error
            ], 422);
        }


        DB::beginTransaction();
        try {
            $userData = Auth::user();
            // $before = $user->getOriginal();
            $user->update([
                'name'     => $request->name,
                'email'    => $request->email,
                'phone'    => $request->phone,
                'role_id'  => $request->role,
                'status'   => $request->status_user,
                'struct_id' => $request->position,
                'updated_by' => $userData->id
            ]);
            DB::commit(); 
            // $after = $user->getChanges();
            // $this->ActivityLog(auth()->id(), 'update', User::class, $user->id, $before, $after);

            return response()->json([
                'success'   => true,
                'message'   => 'Success update data'
            ]);

        } catch (\Exception $e) {
            DB::rollBack(); 

            return response()->json([
                'success' => false,
                'message' => 'Failed update data',
            ], 500);
        }
        
    }

    // function active && not active user
    public function activateUser(Request $request, $id){
        $user = User::find($id);
        if(isset($user)){
            $new_status = $request->status == 1 ? 'active' : 'inactive';
            $user->update(['status' => $new_status]);
            return response()->json([
                'status' => true,
                'message' => 'Success update data',
            ]);

        }else{
            return response()->json([
                'success' => false,
                'message' => 'Failed update data',
            ], 500);
        }
    }

    // delete data
    public function destroy($id){
        $user = User::findOrFail($id);
        $check = Auth::user();
        if($user->id != $check->id ){
            $user->delete();
            return response()->json([
                'success' => true,
                'message' => 'Success delete data'
            ]);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'You cannot delete yourself'
            ], 403);
        }        
    }


    // simple track
    public function track($id){
        $item = User::find($id);

        $name_created = $item->re_created_by?->name ?? 'System';
        $time_created = optional($item->created_at)
            ->timezone(config('app.timezone'))
            ->locale('id')
            ->translatedFormat('d M Y H:i');

        $html = ' 
            <div class="relative">
                <div class="absolute left-6 top-0 bottom-0 w-0.5 bg-gray-200"></div>
                <div class="space-y-6">
                    
                <div class="relative flex gap-4">
                    <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center font-bold z-10">
                        <i class="fa fa-history text-white"></i>
                    </div>
                    <div class="flex-1 pb-8">
                        <h3 class="font-bold text-gray-900 mb-2"> Create User '.e($item->name).'</h3>
                        <p class="text-sm text-gray-600 mb-2"> Create by '.e($name_created).'</p>
                        <p class="text-sm text-gray-500">'.e($time_created).'</p>
                    </div>
                </div>
        ';
        if($item->updated_at){
            $name_updated = $item->re_updated_by?->name ?? 'System';
            $time_updated = optional($item->updated_at)
            ->timezone(config('app.timezone'))
            ->locale('id')
            ->translatedFormat('d M Y H:i');

            $html .= '  
                        <div class="relative flex gap-4">
                            <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center text-white font-bold z-10">
                                <i class="fa fa-history text-white"></i>
                            </div>
                            <div class="flex-1 pb-8">
                                <h3 class="font-bold text-gray-900 mb-2"> Update User '.e($item->name).'</h3>
                                <p class="text-sm text-gray-600 mb-2">Update by '.e($name_updated).'</p>
                                <p class="text-sm text-gray-500">'.e($time_updated).'</p>
                            </div>
                        </div>

                    </div>
                </div>
            ';
        }else{
            $html += '
                </div>
            </div>
            ';
        }

        return response()->json([
            'data' => $html
        ]);
    }


    // Example user notify approval

    // use App\Models\User;
    // use App\Notifications\RequestApprovalNotification;

    // // Misalnya user yang mengajukan adalah $user
    // $approverRole = 'manager';  // Role yang harus menyetujui pengajuan

    // // Menemukan user dengan role tertentu untuk approval
    // $approver = User::where('role', $approverRole)->first();  // Anda bisa menyesuaikan query untuk menemukan role yang sesuai

    // // Mengirimkan notifikasi
    // $actionDetails = [
    //     'id' => $action->id,
    //     'name' => $action->name
    // ];

    // $approver->notify(new RequestApprovalNotification($actionDetails, $user));



    // menampilkan notif
    // Mengambil notifikasi yang belum dibaca oleh pengguna
    // $notifications = auth()->user()->unreadNotifications;

    // return view('notifications.index', compact('notifications'));

    // @foreach ($notifications as $notification)
    //     <div>
    //         <p>Anda mendapatkan permintaan approval dari {{ $notification->data['requester'] }}</p>
    //         <p>Detail Tindakan: {{ $notification->data['action'] }}</p>
    //         <a href="{{ $notification->data['url'] }}">Klik untuk menyetujui</a>
    //     </div>
    // @endforeach




    // menandai sudah di baca
    // Menandai notifikasi sebagai dibaca
    // auth()->user()->unreadNotifications->markAsRead();

    // jika 1 notifkasi
    // $notification = auth()->user()->unreadNotifications->find($notificationId);
    // $notification->markAsRead();



    // menentukan role yang bertanggung jawab untuk setiap tindakan tertentu dan mengirimkan notifikasi sesuai dengan role tersebut
    // $approvers = User::role('manager')->get(); // Mengambil semua user dengan role manager
    // foreach ($approvers as $approver) {
    //     $approver->notify(new RequestApprovalNotification($actionDetails, $user));
    // }





}

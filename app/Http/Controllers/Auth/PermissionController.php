<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Symfony\Component\HttpFoundation\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Auth\User;
use App\Models\Auth\Role;
use App\Models\Auth\Permission;

class PermissionController extends BaseController
{

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    // public function index(Request $request){
    //     $query = Permission::query();
    //     $recentSearch = [];

    //     if ($request->filled('search')) {
    //         $query->where('name', 'like', '%' . $request->search . '%');
    //         $recentSearch['search'] = $request->search;
    //     }

    //     if ($request->filled('menu')) {
    //         $query->where('header_menu', 'like', '%' . $request->menu . '%')->orWhere('child_menu', 'like', '%' . $request->menu. '%');
    //         $recentSearch['menu'] = $request->menu;
    //     }

    //     $permissions = $query->orderBy('header_menu')
    //     ->orderBy('child_menu')
    //     ->orderBy('name')->paginate(10);

    //     return view('Dashboard.auth.permission.index', compact('permissions', 'recentSearch'));
    // }

     public function index()
    {
        return view('Dashboard.auth.permission.index'); // blade utama
    }

    public function getData(Request $request)
    {
        $query = Permission::query(); // kalau ada relasi permission

        return DataTables::of($query)
            ->filter(function ($query) use ($request) {
                if ($request->search) {
                    $query->where('name', 'like', '%' . $request->search . '%')->orWhere('menu', 'like', '%'. $request->search.'%');
                }
                if ($request->date) {
                    $query->whereDate('created_at', $request->date);
                }
            })
            ->editColumn('name', function($row){
            
                if($row->name == 'view'){
                    $color = 'primary';
                }elseif($row->name == 'edit'){
                    $color = 'warning';
                }elseif($row->name == 'delete'){
                    $color = 'danger';
                }elseif($row->name == 'approved'){
                    $color = 'success';
                }else{
                    $color = 'secondary';
                }
            
                return '
                <div style="font-size:14px">
                    <span class="badge badge-'.($color).'">
                        '.e($row->name).'
                    </span>
                </div>
                    
                ';
            })
            ->editColumn('menu', function ($row) {
                $child = '';

                if (!empty($row->child_menu)) {
                    $child = '
                        <div class="vertical-timeline-item dot-warning vertical-timeline-element">
                            <div>
                                <span class="vertical-timeline-element-icon bounce-in"></span>
                                <div class="vertical-timeline-element-content bounce-in">
                                    <p class="timeline-title">
                                        <span class="badge badge-warning" style="font-size: 10px !important;"> '
                                            . e($row->child_menu) . 
                                        ' </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    ';
                }

                return '
                    <div class="vertical-time-simple vertical-without-time vertical-timeline vertical-timeline--animate vertical-timeline--one-column">
                        <div class="vertical-timeline-item dot-danger vertical-timeline-element">
                            <div>
                                <span class="vertical-timeline-element-icon bounce-in"></span>
                                <div class="vertical-timeline-element-content bounce-in">
                                    <p class="timeline-title">
                                        <span class="badge badge-danger" style="font-size: 10px !important;"> '
                                            . e($row->header_menu) .
                                        ' </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        '.$child.'
                    </div>
                ';
            })


            ->editColumn('created_at', function ($row) {
                return datatable_user_time($row->re_created_by, $row->created_at);
            })
            ->editColumn('updated_at', function ($row) {
                return datatable_user_time($row->re_updated_by, $row->updated_at);
            })
            ->addColumn('action', function ($row) {
                 $resource = 'permission'; 
                return view('Dashboard.partials.action-type2', compact('row', 'resource'))->render();
            })
            ->rawColumns(['name','menu','created_at','updated_at','action']) // kalau pakai button/link HTML
            ->addIndexColumn()
            ->make(true);
    }

    public function create(){
        return view('Dashboard.auth.permission.create');
    }


    public function show($id){
        $permission = Permission::findOrFail($id);
        return response()->json([
            'id' => $permission->id,
            'name' => $permission->name,
            'header_menu' => $permission->header_menu,
            'child_menu' => $permission->child_menu ? $permission->child_menu : '',
        ]);
    }

    public function store(Request $request){
        
        $request->validate([
            'name' => 'required',   
            'header_menu' => 'required'
        ]);

        $combine = $request->name.' '.$request->header_menu;
        if(isset($request->child_menu)){
            $combine = $combine.' '.$request->child_menu;
        }

        $slug = Str::slug($combine);
        $duplicateName = Permission::where('slug', $slug)->first();

        if ($duplicateName) {
            return redirect()->back()
            ->withErrors(['name' => 'Permission has ready.'])
            ->withInput();
        }

        
        Permission::create([
            'name' => $request->name,
            'header_menu' => $request->header_menu,
            'slug' => $slug,
            'child_menu' => $request->child_menu ? $request->child_menu : null,
        ]); 

        return redirect()->back()->with('success', 'Success add data.');

    }

    public function edit($id){
        $permission = Permission::find($id);
        return view('Dashboard.auth.permission.edit', compact('permission'));
    }

    public function update(Request $request, $id){
        $request->validate([
            'name' => 'required',   
            'header_menu' => 'required'
        ]);

        $combine = $request->name.' '.$request->header_menu;
        if(isset($request->child_menu)){
            $combine = $combine.' '.$request->child_menu;
        }

        $slug = Str::slug($combine);

        $duplicateName = Permission::whereRaw('LOWER(slug) = ?', [strtolower($slug)])
        ->where('id', '!=', $id)
        ->first();
        

        if ($duplicateName) {
            return redirect()->back()
            ->withErrors(['name' => 'Permission has ready'])
            ->withInput();
        }

        $permission = Permission::findOrFail($id);
        $permission->update([
            'name' => $request->name,
            'slug' => $slug,
            'header_menu' => $request->header_menu,
            'child_menu' => $request->child_menu ? $request->child_menu : null,
        ]);

        return redirect()->route('admin.setting.permission.index')->with('success', 'Success updated data.');
    }

    public function destroy($id){
        $permission = Permission::findOrFail($id);
        $permission->delete();

        return response()->json(['message' => 'Permission deleted successfully.']);
    }


}

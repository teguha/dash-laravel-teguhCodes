<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Symfony\Component\HttpFoundation\Request;
use Illuminate\Support\Str;

use App\Models\Auth\User;
use App\Models\Auth\Role;
use App\Models\Auth\Permission;
use App\Models\Auth\Approval;
use App\Models\Master\Structure;

class ApprovalController extends BaseController
{

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index(Request $request){
        $query = Approval::query();
        $recentSearch = [];

        if ($request->filled('search')) {
            $query->whereHas('re_structure', function($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%');
            });
            $recentSearch['search'] = $request->search;
        }

        $approvals = $query->with('re_permission_by', 're_role_by', 're_struct_by')->orderBy('updated_at')->paginate(10);

        return view('Dashboard.auth.approval.index', compact('approvals', 'recentSearch'));
    }

    public function create(){
        $subCorps = Structure::where('level', 'sub_corp')->get();
        $roles = Role::all(); // tambahkan nanti konidisi where sub corporate
        $menus = Permission::all();  // tambahkan nanti konidisi where sub corporate
        return view('Dashboard.auth.approval.create', compact('subCorps', 'roles', 'menus'));
    }


    public function show($id){
        $approval = Approval::findOrFail($id);

        return response()->json([
            'id' => $approval->id,
            'approval_by' => $approval->approval_by,
            'role' => $approval->role_id != null ? $approval->re_role_by->name : '',
            'struct' => $approval->struct_id != null ? $approval->re_structure_by->name : '',
            'sub_corp' => $approval->sub_corp_id != null ? $approval->re_corp_by->name : '',
            'approval_type' => $approval->approval_type ,
            'menu' => $approval->permission_menu_id != null ? $approval->re_permission_by->name : '',
            'approval_position' => $approval->approval_position,
        ]);
    }

    public function store(Request $request){
        
        $request->validate([
            'approval_by' => 'required',   
            'sub_corp_id' => 'required',
            'approval_type' => 'required',
            'approval_by' => 'required',
            'permission_menu_id' => 'required',
            // 'approval_position' => 'required',
        ]);

        $seq = Approval::where('permission_menu_id', $request->permission_menu_id)->count('id');
        if(!empty($seq) && $request->approval_type == 'sequential'){
            $position = 1+ $seq;
        }else{
            $position = 0;
        }

        $menu = Permission::find($request->permission_menu_id);
        $subCorp = Structure::find($request->sub_corp_id);
        $combine = $menu->name.' '.$subCorp->name.' '.$request->approval_type.' '.$position;

        $slug = Str::slug($combine);
        $duplicateName = Approval::where('slug', $slug)->first();

        if ($duplicateName) {
            return redirect()->back()
            ->withErrors(['name' => 'Approval Flow has ready.'])
            ->withInput();
        }

        Approval::create([
            'approval_by' => $request->approval_by,
            'role_id' => $request->roleId,
            'struct_id' => $request->structId,
            'sub_corp_id' => $request->sub_corp_id,
            'approval_type' => $request->approval_type,
            'permission_menu_id' => $request->permission_menu_id,
            'approval_position' => $position,
            'slug' => $slug
        ]); 

        return redirect()->route('admin.setting.approval.index')->with('success', 'Success add data.');

    }

    public function edit($id){
        $approval = Approval::find($id);
        return view('Dashboard.auth.approval.edit', compact('approval'));
    }

    public function update(Request $request, $id){
        $request->validate([
            'approval_by' => 'required',   
            'sub_corp_id' => 'required',
            'approval_type' => 'required',
            'permission_menu_id' => 'required',
            'approval_position' => 'required',
        ]);

        $menu = Permission::find($request->menu_id);
        $subCorp = Structure::find($request->subCorp_id);
        $combine = $menu->name.' '.$subCorp->name.' '.$request->approval_type.' '.$request->approval_position;

        $slug = Str::slug($combine);

        $duplicateName = Approval::whereRaw('LOWER(slug) = ?', [strtolower($slug)])
        ->where('id', '!=', $id)
        ->first();
        
        if ($duplicateName) {
            return redirect()->back()
            ->withErrors(['name' => 'Approval Flow has ready'])
            ->withInput();
        }

        $approval = Approval::findOrFail($id);
        $approval->update([
            'approval_by' => $request->approval_by,
            'role_id' => $request->role_id,
            'struct_id' => $request->struct_id,
            'sub_corp_id' => $request->sub_corp_id,
            'approval_type' => $request->approval_type,
            'permission_menu_id' => $request->permission_menu_id,
            'approval_position' => $request->approval_position,
            'slug' => $slug
        ]);

        return redirect()->route('admin.setting.approval.index')->with('success', 'Success updated data.');
    }

    public function destroy($id){
        $approval = Approval::findOrFail($id);
        $approval->delete();

        return response()->json(['message' => 'Approval deleted successfully.']);
    }


}

<?php

namespace App\Http\Controllers\Master\Structur;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Symfony\Component\HttpFoundation\Request;
use Illuminate\Support\Str;

use App\Models\Auth\User;
use App\Models\Auth\Role;
use App\Models\Auth\Permission;
use App\Models\Master\Structur;
use App\Models\Master\Structure;

class BagianController extends BaseController
{

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index(Request $request){
        $query = Structure::query();
        $recentSearch = [];

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')->where('level','bagian');
            $recentSearch['search'] = $request->search;
        }

        $structures = $query->with('re_parent')->where('level','bagian')->orderBy('name')->paginate(10);

        return view('Dashboard.master.structure.bagian.index', compact('structures', 'recentSearch'));
    }

    public function create(){
        $subCorps = Structure::whereIn('level', ['sub_corp', 'main_corp'])->get();
        return view('Dashboard.master.structure.bagian.create', compact('subCorps'));
    }

    public function show($id){
        $structure = Structure::findOrFail($id);

        return response()->json([
            'id' => $structure->id,
            'name' => $structure->name,
            'name_sub_corp' => $structure->re_parent->name,
        ]);
    }

    public function store(Request $request){
        
        $request->validate([
            'name' => 'required',   
        ]);


        $subCorp = Structure::find($request->subCorp);
        $dataSlug = $subCorp->name.' '.'bagian'.' '.$request->name;
        $slug = Str::slug($dataSlug);
        $duplicateName = Structure::where('slug', $slug)->where('level', 'bagian')->first();

        if ($duplicateName) {
            return redirect()->back()
            ->withErrors(['name' => 'Structure Name has ready.'])
            ->withInput();
        }

        
        Structure::create([
            'name' => $request->name,
            'parent_id' => $subCorp->id,
            'level' => 'bagian',
            'slug' => $slug,
        ]); 

        return redirect()->route('admin.structure.bagian.index')->with('success', 'Success add data.');
    }

    public function edit($id){
        $structure = Structure::find($id);
        $subCorps = Structure::whereIn('level', ['sub_corp', 'main_corp'])->get();
        return view('Dashboard.master.structure.bagian.edit', compact('structure', 'subCorps'));
    }

    public function update(Request $request, $id){
        $request->validate([
            'name' => 'required',   
        ]);

        $subCorp = Structure::find($request->subCorp);
        $dataSlug = $subCorp->name.' '.'bagian'.' '.$request->name;
        $slug = Str::slug($dataSlug);
        $duplicateName = Structure::whereRaw('LOWER(slug) = ?', [strtolower($slug)])
        ->where('id', '!=', $id)
        ->where('level', 'bagian')
        ->first();
        

        if ($duplicateName) {
            return redirect()->back()
            ->withErrors(['name' => 'Structure name has ready'])
            ->withInput();
        }

        $structure = Structure::findOrFail($id);
        $structure->update([
            'name' => $request->name,
            'parent_id' => $subCorp->id,
            'level' => 'bagian',
            'slug' => $slug,
        ]);

        return redirect()->route('admin.structure.bagian.index')->with('success', 'Success updated data.');
    }

    public function destroy($id){
        $permission = Structure::findOrFail($id);
        $permission->delete();

        return response()->json(['message' => 'Structure deleted successfully.']);
    }


}

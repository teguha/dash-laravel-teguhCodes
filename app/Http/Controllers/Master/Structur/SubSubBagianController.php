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

class SubSubBagianController extends BaseController
{

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index(Request $request){
        $query = Structure::query();
        $recentSearch = [];

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')->where('level','sub_sub_bagian');
            $recentSearch['search'] = $request->search;
        }

        $structures = $query->with('re_parent')->where('level','sub_sub_bagian')->orderBy('name')->paginate(10);

        return view('Dashboard.master.structure.subSubBagian.index', compact('structures', 'recentSearch'));
    }

    public function create(){
        $subBagians = Structure::whereIn('level', ['sub_bagian'])->get();
        return view('Dashboard.master.structure.subSubBagian.create', compact('subBagians'));
    }

    public function show($id){
        $structure = Structure::findOrFail($id);

        return response()->json([
            'id' => $structure->id,
            'name' => $structure->name,
            'name_sub_bagian' => $structure->re_parent->name,
        ]);
    }

    public function store(Request $request){
        
        $request->validate([
            'name' => 'required',   
        ]);


        $subBagian = Structure::find($request->subBagian);
        $dataSlug = $subBagian->name.' '.'sub_sub_bagian'.' '.$request->name;
        $slug = Str::slug($dataSlug);
        $duplicateName = Structure::where('slug', $slug)->where('level', 'sub_sub_bagian')->first();

        if ($duplicateName) {
            return redirect()->back()
            ->withErrors(['name' => 'Structure Name has ready.'])
            ->withInput();
        }

        
        Structure::create([
            'name' => $request->name,
            'parent_id' => $subBagian->id,
            'level' => 'sub_sub_bagian',
            'slug' => $slug,
        ]); 

        return redirect()->route('admin.structure.subSubBagian.index')->with('success', 'Success add data.');
    }

    public function edit($id){
        $structure = Structure::find($id);
        $subBagians = Structure::whereIn('level', ['sub_bagian'])->get();
        return view('Dashboard.master.structure.subSubBagian.edit', compact('structure', 'subBagians'));
    }

    public function update(Request $request, $id){
        $request->validate([
            'name' => 'required',   
        ]);

        $subBagian = Structure::find($request->subBagian);
        $dataSlug = $subBagian->name.' '.'sub_sub_bagian'.' '.$request->name;
        $slug = Str::slug($dataSlug);
        $duplicateName = Structure::whereRaw('LOWER(slug) = ?', [strtolower($slug)])
        ->where('id', '!=', $id)
        ->where('level', 'sub_sub_bagian')
        ->first();
        

        if ($duplicateName) {
            return redirect()->back()
            ->withErrors(['name' => 'Structure name has ready'])
            ->withInput();
        }

        $structure = Structure::findOrFail($id);
        $structure->update([
            'name' => $request->name,
            'parent_id' => $subBagian->id,
            'level' => 'sub_sub_bagian',
            'slug' => $slug,
        ]);

        return redirect()->route('admin.structure.subSubBagian.index')->with('success', 'Success updated data.');
    }

    public function destroy($id){
        $permission = Structure::findOrFail($id);
        $permission->delete();

        return response()->json(['message' => 'Structure deleted successfully.']);
    }


}

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

class MainCorporateController extends BaseController
{

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index(Request $request){
        $query = Structure::query();
        $recentSearch = [];

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')->where('level','main_corp');
            $recentSearch['search'] = $request->search;
        }

        $structures = $query->where('level','main_corp')->orderBy('name')->paginate(10);

        return view('Dashboard.master.structure.mainCorp.index', compact('structures', 'recentSearch'));
    }

    public function create(){
        return view('Dashboard.master.structure.mainCorp.create');
    }

    public function show($id){
        $structure = Structure::findOrFail($id);
       
        return response()->json([
            'id' => $structure->id,
            'name' => $structure->name,
            'phone' => $structure->phone,
            'address' => $structure->address,
            'tax' => $structure->tax,
        ]);
    }

    public function store(Request $request){
        
        $request->validate([
            'name' => 'required',   
            'phone' => 'required',
            'address' => 'required',   
            'tax' => 'required'
        ]);

        $slug = Str::slug($request->name);
        $duplicateName = Structure::where('slug', $slug)->where('level', 'main_corp')->first();

        if ($duplicateName) {
            return redirect()->back()
            ->withErrors(['name' => 'Name has ready.'])
            ->withInput();
        }

        
        Structure::create([
            'name' => $request->name,
            'level' => 'main_corp',
            'phone' => $request->phone,
            'slug' => $slug,
            'address' => $request->address,
            'tax' => $request->tax,
        ]); 

        return redirect()->route('admin.structure.mainCorp.index')->with('success', 'Success add data.');

    }

    public function edit($id){
        $structure = Structure::find($id);
        return view('Dashboard.master.structure.mainCorp.edit', compact('structure'));
    }

    public function update(Request $request, $id){
        $request->validate([
            'name' => 'required',   
            'phone' => 'required',
            'address' => 'required',   
            'tax' => 'required'
        ]);

        $slug = Str::slug($request->name);

        $duplicateName = Structure::whereRaw('LOWER(slug) = ?', [strtolower($slug)])
        ->where('id', '!=', $id)
        ->where('level', 'main_corp')
        ->first();
        

        if ($duplicateName) {
            return redirect()->back()
            ->withErrors(['name' => 'Structure has ready'])
            ->withInput();
        }

        $structure = Structure::findOrFail($id);
        $structure->update([
            'name' => $request->name,
            'level' => 'main_corp',
            'phone' => $request->phone,
            'slug' => $slug,
            'address' => $request->address,
            'tax' => $request->tax,
        ]);

        return redirect()->route('admin.structure.mainCorp.index')->with('success', 'Success updated data.');
    }

    public function destroy($id){
        $permission = Structure::findOrFail($id);
        $permission->delete();

        return response()->json(['message' => 'Structure deleted successfully.']);
    }


}

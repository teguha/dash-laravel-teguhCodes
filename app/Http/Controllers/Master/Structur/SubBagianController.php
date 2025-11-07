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

class SubBagianController extends BaseController
{

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index(Request $request){
        $query = Structure::query();
        $recentSearch = [];

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')->where('level','sub_bagian');
            $recentSearch['search'] = $request->search;
        }

        $structures = $query->with('re_parent')->where('level','sub_bagian')->orderBy('name')->paginate(10);

        return view('Dashboard.master.structure.subBagian.index', compact('structures', 'recentSearch'));
    }

    public function create(){
        $bagians = Structure::whereIn('level', ['bagian'])->get();
        return view('Dashboard.master.structure.subBagian.create', compact('bagians'));
    }

    public function show($id){
        $structure = Structure::findOrFail($id);

        return response()->json([
            'id' => $structure->id,
            'name' => $structure->name,
            'name_bagian' => $structure->re_parent->name,
        ]);
    }

    public function store(Request $request){
        
        $request->validate([
            'name' => 'required',   
        ]);


        $bagian = Structure::find($request->bagian);
        $dataSlug = $bagian->name.' '.'sub_bagian'.' '.$request->name;
        $slug = Str::slug($dataSlug);
        $duplicateName = Structure::where('slug', $slug)->where('level', 'sub_bagian')->first();

        if ($duplicateName) {
            return redirect()->back()
            ->withErrors(['name' => 'Structure Name has ready.'])
            ->withInput();
        }

        
        Structure::create([
            'name' => $request->name,
            'parent_id' => $bagian->id,
            'level' => 'sub_bagian',
            'slug' => $slug,
        ]); 

        return redirect()->route('admin.structure.subBagian.index')->with('success', 'Success add data.');
    }

    public function edit($id){
        $structure = Structure::find($id);
        $bagians = Structure::whereIn('level', ['bagian'])->get();
        return view('Dashboard.master.structure.subBagian.edit', compact('structure', 'bagians'));
    }

    public function update(Request $request, $id){
        $request->validate([
            'name' => 'required',   
        ]);

        $bagian = Structure::find($request->bagian);
        $dataSlug = $bagian->name.' '.'sub_bagian'.' '.$request->name;
        $slug = Str::slug($dataSlug);
        $duplicateName = Structure::whereRaw('LOWER(slug) = ?', [strtolower($slug)])
        ->where('id', '!=', $id)
        ->where('level', 'sub_bagian')
        ->first();
        

        if ($duplicateName) {
            return redirect()->back()
            ->withErrors(['name' => 'Structure name has ready'])
            ->withInput();
        }

        $structure = Structure::findOrFail($id);
        $structure->update([
            'name' => $request->name,
            'parent_id' => $bagian->id,
            'level' => 'sub_bagian',
            'slug' => $slug,
        ]);

        return redirect()->route('admin.structure.subBagian.index')->with('success', 'Success updated data.');
    }

    public function destroy($id){
        $permission = Structure::findOrFail($id);
        $permission->delete();

        return response()->json(['message' => 'Structure deleted successfully.']);
    }


   public function searchSubBagian(Request $request)
    {
        $search = $request->input('q');

        // Ambil semua sub_corporate
        $subCorporates = Structure::with(['bagian.sub_bagian'])
            ->where('level', 'sub_corp')
            ->whereHas('bagian.sub_bagian', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhereHas('bagian', function($q) use ($search){
                $q->where('name', 'like', "%{$search}%");
            })
            ->get();

        $results = [];

        foreach ($subCorporates as $subCorp) {
            $children = [];

            foreach ($subCorp->bagian as $bagian) {
                // Tambahkan bagian (jika ingin)
                $children[] = [
                    'id' => 'B-' . $bagian->id,
                    'text' => $bagian->name
                ];

                foreach ($bagian->sub_bagian as $subBagian) {
                    if (stripos($subBagian->name, $search) !== false) {
                        $children[] = [
                            'id' => $subBagian->id,
                            'text' => $subBagian->name
                        ];
                    }
                }
            }

            if (!empty($children)) {
                $results[] = [
                    'text' => $subCorp->name,
                    'children' => $children
                ];
            }
        }

        return response()->json($results);
    }



    
    // public function searchSubBagian(Request $request)
    // {
    //     $search = $request->input('q');

    //     // $subCorporates = Structure::with('re_parent')
    //     //         ->whereHas('re_parent', function($q) use ($search){
    //     //             $q->where('level','bagian')->where('name', 'like', "%{$search}%");
    //     //         });

    //     $subCorporates = Structure::with('bagian.sub_bagian')
    //         ->whereHas('bagian.sub_bagian', function($q) use ($search) {
    //             $q->where('name', 'like', "%{$search}%");
    //         })
    //         ->get();

    //     $results = [];

    //     foreach ($subCorporates as $subCorp) {
    //         $children = [];

    //         foreach ($subCorp->bagian as $bagian) {
    //             $children[] = [
    //                 'id' => 'B-' . $bagian->id,
    //                 'text' => $bagian->name
    //             ];

    //             foreach ($bagian->sub_bagian as $subBagian) {
    //                 if (stripos($subBagian->name, $search) !== false) {
    //                     $children[] = [
    //                         'id' => $subBagian->id,
    //                         'text' => $subBagian->name
    //                     ];
    //                 }
    //             }
    //         }

    //         if (!empty($children)) {
    //             $results[] = [
    //                 'text' => $subCorp->nama,
    //                 'children' => $children
    //             ];
    //         }
    //     }

    //     return response()->json($results);
    // }


}

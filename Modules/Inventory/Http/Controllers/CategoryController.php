<?php

namespace Modules\Inventory\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Modules\Inventory\Http\Requests\RequestCategory;
use Modules\Inventory\Http\Requests\RequestEditCategory;
use Modules\Inventory\Entities\ItemCategory;
use Modules\Project\Entities\Project;
use Modules\Inventory\Entities\Brand;
use Modules\Inventory\Entities\BrandOfCategory;
use Auth;
use datatables;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $project = Project::all();
        $user = Auth::user();
        return view('inventory::category.show',compact('project','user'));
       // return view('Category.index', compact('categories', 'ItemCategory', 'item_category'));
    }

    public function getCategories()
    {
        $all_category = ItemCategory::all();
        foreach ($all_category as $key => $value) {
            # code...
            $sub_data['id'] = $value->id;
            $sub_data['name'] = $value->name;
            $sub_data['text'] = $value->name;

            $sub_data['tags'][0] = $value->child->count();

            $sub_data['parent_id'] = $value->parent_id;
            $data[] = $sub_data;

        }

        foreach ($data as $key => &$value) {
            # code...
            $output[$value['id']] = &$value;
        }

        foreach ($data as $key => &$value) {
            # code...
            if($value['parent_id'] && isset($output[$value['parent_id']]))
            {
                $output[$value['parent_id']]['nodes'][] = &$value;
            }
        }

        foreach ($data as $key => &$value) {
            # code...
            if($value['parent_id'] && isset($output[$value['parent_id']]))
            {
                unset($data[$key]);
            }
        }
        //$data = json_decode($data);
        return response()->json($data);
    }

    public function getparent()
    {
        $categories             = ItemCategory::where('parent_id',0)->get();

        return response()->json($categories);
    }

    public function add(Request $request)
    {
        $brands = Brand::all();
        $project = Project::all();
        $user = Auth::user();
        return view('inventory::category.add_form',compact('user','brands','project'));
    }
    
    public function addPost(RequestCategory $request) 
    {
        $stat                       = 0;
        $errMsg                     ='';
        
        $allBrand                   = json_decode($request->allItemStore);
       
        try
        {
            $ItemCategory               = new ItemCategory;
            $ItemCategory->parent_id    = $request->parent_id;
            $ItemCategory->name         = $request->name;
            $status = $ItemCategory->save();
            if($status)
            {
                for ($i=0; $i < count($allBrand); $i++) { 
                    # code...
                    $createBrandCategory = BrandOfCategory::create([
                        'category_id'=>$ItemCategory->id,
                        'brand_id'=>$allBrand[$i]->brand_id
                    ]);
                }
                $stat = 1;
            }
        }
        catch(Exception $e)
        {
            $errMsg = $e->getMessage();
        }
        
        
        

        return response()->json(['return'=>$stat,'errMsg'=>$errMsg]);
    }

    public function detail(Request $request)
    {
        $user = Auth::user();
        $project = Project::all();
        $categories = ItemCategory::find($request->id);
        $brands = Brand::all();

        return view('inventory::category.detail', compact('categories','project','user','brands'));
    }

    public function categorySource()
    {
        $pts = ItemCategory::select('id','name')->get();
        $retObj = [];
        foreach ($pts as $key => $value) {
            # code...
            $retObj[$value->id] = $value->name;
        }

        return response()->json($retObj);
    }

    public function update(Request $request)
    {
        $stat =0;
        $name = $request->name;
        $pk = $request->pk;
        $value = $request->value;
        $updated = ItemCategory::find($pk)->update([$name=>$value]);
        if($updated)
        {
            $stat = 1;
        }

        return response()->json(['return'=>$stat]);
        
    }

    public function getDetailMerek($id)
    {
        $datas = BrandOfCategory::where('category_id',$id)->get();
        $result = [];
        foreach ($datas as $key => $value) {
            # code...
            $arr = [
                'id'=>$value->id,
                'brand_name'=>$value->brand->name
            ];

            array_push($result, $arr);
        }

        return datatables()->of($result)->toJson();
    }

    public function delete(Request $request)
    {
        $ItemCategory               = ItemCategory::find($request->id);
        $status                 = $ItemCategory->delete();
        $deleteBrandCategory = BrandOfCategory::where('category_id',$request->id)->delete();
        $stat = false;
        if ($status && $deleteBrandCategory) 
        {
            $stat = true;
        }

        return response()->json($stat);
    }

    public function deleteBrandCategory(Request $request)
    {
        $status = BrandOfCategory::find($request->id)->delete();
        if ($status ) 
        {
            $stat = true;
        }

        return response()->json($stat);
    }

    public function addBrand(Request $request)
    {
        $stat = false;
        $category_id = $request->category_id;
        $brand_id = $request->brand_id;
        $check = BrandOfCategory::where([
                        ['category_id','=',$category_id],
                        ['brand_id','=',$brand_id]
                    ])->first();
        if($check == null)
        {
             $createBrandCategory = BrandOfCategory::create([
                        'category_id'=>$category_id,
                        'brand_id'=>$brand_id
                    ]);
            
            if($createBrandCategory)
            {
                $stat = true;
            }
        }
    
        return response()->json($stat);

    }
}

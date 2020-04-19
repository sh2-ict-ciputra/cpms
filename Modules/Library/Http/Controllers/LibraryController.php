<?php

namespace Modules\Library\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Library\Entities\Library;
use Modules\Library\Entities\LibrarySupplierDetail;
use Modules\Library\Entities\LibrarySupplierDetails;
use Modules\Library\Entities\LibrarySupplierProjectSPK;
use Modules\Library\Entities\LibrarySupplierProjectSPKDetail;
use Modules\Library\Entities\LibrarySupplierProjectPO;
use Modules\Library\Entities\LibrarySupplierProjectPODetail;
use Modules\Library\Entities\LibrarySupplierPricelistBerlaku;
use Modules\Library\Entities\LibraryMOUDetails;
use Modules\Library\Entities\LibraryHargaSatuan;
use Modules\Library\Entities\LibraryHargaSatuanTotal;
use Modules\Library\Entities\CTEItemPekerjaan;
use Modules\Library\Entities\CTEHargaSatuanTotal;
use Modules\Library\Entities\CTEJoin;
use Modules\Library\Entities\MOU;
use Modules\Library\Entities\Items;
use Modules\Library\Entities\ItemPekerjaans;
use Modules\Library\Entities\CTEItemPekerjaanSub;
use Modules\Project\Entities\Project;
use Modules\Rekanan\Entities\Rekanan;
use Modules\Library\Entities\RekananPricelist;
use Modules\Library\Entities\RekananItems;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Collection;
use Modules\Library\Http\Controllers;
use Storage;

class LibraryController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        return view('library::index',compact("project","user"));
    }

    public function showSupplier(Request $request)
    {
        $user = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        return view('library::supplier',compact("project","user"));
    }

    public function detailSupplier(Request $request)
    {
        $idSupplier = $request->supplier;
        $supplier = LibrarySupplierDetails::where('id', $idSupplier)->first();
        return response()->json($supplier, 200);
    }

    public function storeSupplierPricelist(Request $request)
    {
        $response = "";
        try {
            if($request->rekanan_group_id != null && $request->tanggal_berlaku != null && $request->pricefile != "undefined" && $request->namaBarang != null){
                if (!file_exists (public_path()."/assets/rekanan/".$request->rekanan_group_id )) {
                    mkdir(public_path()."/assets/rekanan/".$request->rekanan_group_id, 0755, true);
                    chmod(public_path()."/assets/rekanan/".$request->rekanan_group_id,0755);
                }
                $dateNow = date('YmdHis');
                $pricelist = new RekananPricelist();
                if ($_FILES['pricefile']['size'] != 0 && ($_FILES['pricefile']['error'] != 0 || $_FILES['pricefile']['error'] != 4))
                {
                    try {
                        $items = json_decode($request->items);
                        $item = new RekananItems();
                        foreach($items as $i){
                            $item->rekanan_group_id = $request->rekanan_group_id;
                            $item->item_id = $i;
                            $item->save();
                        }
                        $filename = $dateNow.'_'.$_FILES['pricefile']['name'];
                        $target_file = public_path()."/assets/rekanan/".$request->rekanan_group_id."/".$filename;
                        $move = move_uploaded_file($_FILES["pricefile"]["tmp_name"], $target_file);
                        if($move){
                            $pricelist->price_file = $filename;
                            $exBerlaku = explode('-', $request->tanggal_berlaku);
                            $berlaku_dari_tanggal = $exBerlaku[0];
                            $berlaku_sampai_tanggal = $exBerlaku[1];
                            $pricelist->berlaku_dari_tanggal = $berlaku_dari_tanggal;
                            $pricelist->berlaku_sampai_tanggal = $berlaku_sampai_tanggal;
                            $pricelist->keterangan = ($request->keterangan != null) ? $request->keterangan : "";
                            $pricelist->rekanan_group_id = $request->rekanan_group_id;
                            $pricelist->save();
                            if($pricelist){
                                $response = [ "status" => "success", "msg" => "Pricelist tersimpan"];
                            }else{
                                $response = [ "status" => "error", "msg" => "Pricelist tidak tersimpan"];
                            }
                        }
                    }catch(Exception $ex){
                        $response = ["status" => "error", "msg" => $ex];
                    }
                }
            }else{
                $response = [ "status" => "error", "msg" => "Data tidak boleh kosong"];
            }
        }catch(Exception $e){
            $response = ["status" => "error", "msg" => $e];
        }

        return response()->json($items, 200);

    }

    public function modifySupplierPricelist(Request $request)
    {
        $response = "";
        $pricelist = RekananPricelist::find($request->pricelist);
        if($request->keterangan != null && $request->keterangan != "undefined" ){
            $pricelist->keterangan = ($request->keterangan != null) ? $request->keterangan : "";
            //$pricelist->save();
        }
        if($request->tanggalBerlaku != null && $request->tanggalBerlaku != "undefined"){
            $exBerlaku = explode('-', $request->tanggalBerlaku);
            $berlaku_dari_tanggal = $exBerlaku[0];
            $berlaku_sampai_tanggal = $exBerlaku[1];
            $pricelist->berlaku_dari_tanggal = $berlaku_dari_tanggal;
            $pricelist->berlaku_sampai_tanggal = $berlaku_sampai_tanggal;
        }
        $pricelist->save();
        if($pricelist){
            $response = [ "status" => "success", "msg" => "Pricelist tersimpan"];
        }else{
            $response = [ "status" => "error", "msg" => "Pricelist tidak tersimpan"];
        }
        return response()->json($response, 200);

    }

    public function deleteSupplierPricelist(Request $request)
    {
        $response = "";
        $pricelist = RekananPricelist::find($request->pricelist);
        $pricelist->delete();
        if($pricelist){
            $response = [ "status" => "success", "msg" => "Pricelist dihapus"];
        }else{
            $response = [ "status" => "error", "msg" => "Pricelist tidak dihapus"];
        }
        return response()->json($response, 200);

    }

    public function getSupplierDataTable(Request $request){
        $data = DataTables::of(LibrarySupplierDetails::get())->make(true);
        return $data;
    }

    public function getSupplierProjectSPK(Request $request){
        $idSupplier = $request->supplier;
        $supplier = LibrarySupplierProjectSPK::where('rekan_id', $idSupplier)->first();
        return response()->json($supplier, 200);
    }

    public function getSupplierProjectPO(Request $request){
        $idSupplier = $request->supplier;
        $supplier = LibrarySupplierProjectPO::where('rekan_id', $idSupplier)->first();
        return response()->json($supplier, 200);
    }

    public function getSupplierProject(Request $request){
        $idSupplier = $request->supplier;
        $po = LibrarySupplierProjectPO::where('rekan_id', $idSupplier);
        $project = DataTables::of(LibrarySupplierProjectSPK::where('rekan_id', $idSupplier)->unionAll($po)->get())->make(true);
        return $project;
    }

    public function getSupplierPricelist(Request $request){
        $response = "";
        try{
            if($request->history){
                $idSupplier = $request->supplier;
                $response = RekananPricelist::where('rekanan_group_id', $idSupplier)
                    ->orderBy('updated_at', 'desc')
                    ->get();
            }else{
                $idSupplier = $request->supplier;
                $response = LibrarySupplierPricelistBerlaku::where('rekanan_group_id', $idSupplier)
                    ->orderBy('updated_at', 'desc')
                    ->get();
            }
        }catch(Exception $e){
            $response = $e;
        }
        return response()->json($response, 200);
    }

    public function getSupplierPricelistDataTable(Request $request){
        $idSupplier = $request->supplier;
        $response = RekananPricelist::where('rekanan_group_id', $idSupplier)
            ->orderBy('updated_at', 'desc')
            ->get();
        $data = DataTables::of($response)->make(true);
        return $data;
    }

    public function getSupplierProjectPODetail(Request $request){
        $response = "";
        $podetail = LibrarySupplierProjectPODetail::where('rekan_id', $request->rekan)
            ->where('proj_id', $request->proj)
            ->get();

        if($podetail){
            $response = [ "status" => "success", "msg" => "", "data" => $podetail];
        }else{
            $response = [ "status" => "error", "msg" => "", "data" => $podetail];
        }
        return response()->json($response, 200);
    }

    public function getSupplierProjectSPKDetail(Request $request){
        $response = "";
        $spkdetail = LibrarySupplierProjectSPKDetail::where('rekan_id', $request->rekan)
            ->where('proj_id', $request->proj)
            ->get();

        if($spkdetail){
            $response = [ "status" => "success", "msg" => "", "data" => $spkdetail];
        }else{
            $response = [ "status" => "error", "msg" => "", "data" => $spkdetail];
        }
        return response()->json($response, 200);
    }

    public function downloadFile(Request $req){
        $pricelist = $req->pricelist;
        $file = RekananPricelist::where('id', $pricelist)
            ->first();
        $filename = $file->price_list;
        $pathFile = public_path().'/assets/rekanan/'.$file->rekanan_group_id.'/'.$file->price_file;
        $mime = mime_content_type($pathFile);
        $headers = array(
            'Content-Type: '.$mime,
            'Content-disposition:attachment,filename='.$filename,
        );
        return Response::download($pathFile, $filename, $headers);
    }

    //#region MOU
    public function showMOU(Request $request)
    {
        $user = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        return view('library::mou',compact("project","user"));
    }

    public function getMOUDataTable(Request $request)
    {
        $mou_ = LibraryMOUDetails::select('nomor_mou')
            ->groupBy('nomor_mou')
            ->get();
        $arr = array();
        foreach($mou_ as $m_){
            $obj = new \stdClass();
            $mou = LibraryMOUDetails::where('nomor_mou', $m_->nomor_mou)
                ->get();
            $item = "";
            $ii = 0;
            foreach($mou as $m){
                $ii++;
                $item .= $m->item_name;
                if($ii != count($mou)){
                    $item .= ",";
                }
                $obj->proj_name = $m->proj_name;
                $obj->rekan_name = $m->rekan_name;
                $obj->jenis_mou = $m->jenis_mou;
                $obj->file_mou = $m->file_mou;
                $obj->rekan_id = $m->rekan_id;
                $obj->project_id = $m->project_id;
                $obj->id = $m->id;
            }
            $obj->nomor_mou = $m_->nomor_mou;
            $obj->item_name = $item;
            $arr[] = $obj;
        }

        $data = new Collection($arr);
        $dt = DataTables::of($data)->make(true);
        return $dt;
    }

    public function select2GetProject(Request $request)
    {
        $search = $request->search;
        $data = array();
        if($search == ''){
            $project = Project::orderby('name','asc')->select('id','name')->limit(10)->get();
        }else{
            $project = Project::orderby('name','asc')->select('id','name')->where('name', 'like', '%' .$search . '%')->limit(10)->get();
        }

        foreach ($project as $p) {
            $data[] = array("id" => $p->id, "text" => $p->name);
        }
        return response()->json($data, 200);
    }

    public function select2GetItem(Request $request)
    {
        $search = $request->search;
        $data = array();
        if($search == ''){
            $item = Items::orderby('name','asc')->select('id','name')->limit(10)->get();
        }else{
            $item = Items::orderby('name','asc')->select('id','name')->where('name', 'like', '%' .$search . '%')->limit(10)->get();
        }
        foreach ($item as $i) {
            $data[] = array("id" => $i->id, "text" => $i->name);
        }
        return response()->json($data, 200);
    }

    public function select2GetSupplier(Request $request)
    {
        $search = $request->search;
        $data = array();
        if($search == ''){
            $rekanan = Rekanan::orderby('name','asc')->select('id','name')->limit(10)->get();
        }else{
            $rekanan = Rekanan::orderby('name','asc')->select('id','name')->where('name', 'like', '%' .$search . '%')->limit(10)->get();
        }
        foreach ($rekanan as $r) {
            $data[] = array("id" => $r->id, "text" => $r->name);
        }
        return response()->json($data, 200);
    }

    public function detailMOU(Request $request)
    {
        $idMOU = $request->mou;
        $mou = LibraryMOUDetails::where('id', $idMOU)->first();
        return response()->json($mou, 200);
    }

    public function storeMOU(Request $request)
    {
        $response = "";
        $item = json_decode($request->item);
        try {
            if($request->jenisMOU != null && $request->nomorMOU != null && $request->mouFile != "undefined"
                && $request->rekan != null && $request->proyek != null && count($item) != 0
                && $request->rekan != null && $request->tanggalBerlaku != null){
                if (!file_exists (public_path()."/assets/mou/".$request->rekan )) {
                    $dir = public_path()."/assets/mou/".$request->rekan;
                    mkdir(public_path()."/assets/mou/".$request->rekan,0755,true);
                    chmod(public_path()."/assets/mou/".$request->rekan,0755);
                }
                $dateNow = date('YmdHis');
                if ($_FILES['mouFile']['size'] != 0 && ($_FILES['mouFile']['error'] != 0 || $_FILES['mouFile']['error'] != 4))
                {
                    try {
                        $mouFile = $_FILES['mouFile']['name'];
                        $filenames = "";
                        if(count($mouFile) > 0){
                            for($ii = 0; $ii < count($mouFile); $ii++){
                                $filename = $dateNow.'_'.$mouFile[$ii];
                                $target_file = public_path()."/assets/mou/".$request->rekan."/".$filename;
                                $move = move_uploaded_file($_FILES["mouFile"]["tmp_name"][$ii], $target_file);
                                $filenames .= $filename; 
                                if($ii != count($mouFile)){
                                    $filenames .= ",";
                                }
                            }
                        }
                        if($move){
                            $isSaved = false;
                            foreach($item as $i){
                                $mou = new MOU();
                                $mou->file_mou = $filenames;
                                $exBerlaku = explode('-', $request->tanggalBerlaku);
                                $berlaku_dari_tanggal = $exBerlaku[0];
                                $berlaku_sampai_tanggal = $exBerlaku[1];
                                $mou->berlaku_dari_tanggal = $berlaku_dari_tanggal;
                                $mou->berlaku_sampai_tanggal = $berlaku_sampai_tanggal;
                                $mou->nomor_mou = $request->nomorMOU;
                                $mou->jenis_mou = $request->jenisMOU;
                                $mou->rekan_id = $request->rekan;
                                $mou->project_id = $request->proyek;
                                $mou->item_id = $i;
                                $mou->save();
                                $isSaved = $mou;
                            }
                            if($isSaved){
                                $response = [ "status" => "success", "msg" => "MOU tersimpan"];
                            }else{
                                $response = [ "status" => "error", "msg" => "MOU tidak tersimpan"];
                            }
                        }
                    }catch(Exception $ex){
                        $response = ["status" => "error", "msg" => $ex];
                    }
                }
            }else{
                $response = [ "status" => "error", "msg" => "Data tidak boleh kosong"];
            }
        }catch(Exception $e){
            $response = ["status" => "error", "msg" => $e];
        }
        return response()->json($response, 200);
    }

    public function modifyMOUPricelist(Request $request)
    {
        $response = "";
        $mou = MOU::find($request->mou);
        $mou->nomor_mou = $request->nomorMOU;
        $mou->jenis_mou = $request->jenisMOU;
        $mou->rekan_id = $request->rekan;
        $mou->project_id = $request->proyek;
        $mou->item_id = $request->item;
        $mou->save();
        if($mou){
            $response = [ "status" => "success", "msg" => "Pricelist tersimpan"];
        }else{
            $response = [ "status" => "error", "msg" => "Pricelist tidak tersimpan"];
        }
        return response()->json($response, 200);
    }

    public function deleteMOUPricelist(Request $request)
    {
        $response = "";
        $pricelist = RekananPricelist::find($request->pricelist);
        $pricelist->delete();
        if($pricelist){
            $response = [ "status" => "success", "msg" => "Pricelist dihapus"];
        }else{
            $response = [ "status" => "error", "msg" => "Pricelist tidak dihapus"];
        }
        return response()->json($response, 200);
    }

    public function downloadFileMOU(Request $req){
        $mou = $req->mou;
        $file = MOU::where('id', $mou)
            ->first();
        $filename = $file->file_mou;
        $pathFile = public_path().'/assets/mou/'.$file->rekan_id.'/'.$file->file_mou;
        $mime = mime_content_type($pathFile);
        $headers = array(
            'Content-Type: '.$mime,
            'Content-disposition:attachment,filename='.$filename,
        );
        return Response::download($pathFile, $filename, $headers);
    }

    //#region HargaSatuan
    public function showHargaSatuan(Request $request)
    {
        $user = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        return view('library::harga-satuan',compact("project","user"));
    }

    public function getHargaSatuanDatatableDevCost(Request $req){
        $coa = LibraryHargaSatuan::select(['coa', 'ipk_name', 'ipk_parent', 'proj_id'])
            ->where('coa', '>=', '200')
            ->where('coa', '<', '300')
            ->groupBy(['coa', 'ipk_name', 'ipk_parent', 'proj_id'])
            ->get();
        $arr = array();
        $parentIdBefore = 0;
        foreach($coa as $c){
            $obj = new \stdClass();
            $ipk = ItemPekerjaans::select('id', 'code', 'name')
                ->where('id', $c->ipk_parent)
                ->first();
            if($ipk->id != $parentIdBefore){
                $item = LibraryHargaSatuanTotal::select(['coa', 'ipk_name', 'ipk_parent', 'proj_id', 'proj_name', 'proj_code'])
                    ->where('coa', '>=', '200')
                    ->where('coa', '<', '300')
                    ->where('ipk_parent', $ipk->id)
                    ->groupBy(['coa', 'ipk_name', 'ipk_parent', 'proj_id', 'proj_name', 'proj_code'])
                    ->get();
                $grandTotalMax = 0;
                $grandTotalMin = 0;
                $projCode = "";
                $projName = "";
                foreach($item as $i)
                {
                    $max = $this->getMaxNilaiCoa($i->coa, $i->proj_id);
                    $min = $this->getMinNilaiCoa($i->coa, $i->proj_id);
                    $grandTotalMax += $max->tmd_nilai_total;
                    $grandTotalMin += $min->tmd_nilai_total;
                    $projCode = $i->proj_code;
                    $projName = $i->proj_name;
                }
                $tag = LibraryHargaSatuanTotal::select('tmd_volume')
                    ->where('proj_id', $c->proj_id)
                    ->where('ipk_isDivider', 1)
                    ->where('ipk_parent', $ipk->id)
                    ->where('tmd_volume', '<>', '0')
                    ->first();
                $volume = ($tag == null) ? 1 : $tag->tmd_volume;
                $maxNilai = $grandTotalMax / $volume;
                $minNilai = $grandTotalMin / $volume;
                $objj = new \stdClass();
                $objj->isParent  = true;
                $objj->coa = $ipk->code;
                $objj->ipk_name = $ipk->name;
                $objj->ipk_id = $ipk->id;
                $objj->ipk_parent = $ipk->parent_id;
                $objj->min_proj_code = $projCode;
                $objj->min_proj_name = $projName;
                $objj->min_nilai = $minNilai;
                $objj->max_proj_code = $projCode;
                $objj->max_proj_name = $projName;
                $objj->max_nilai = $maxNilai;
                $objj->satuan = "";
                $arr[] = $objj;
            }
            $parentIdBefore = $ipk->id;
        }
        $data = new Collection($arr);
        $dt = Datatables::of($data)->make(true);
        return $dt;
    }

    public function getHargaSatuanDatatableConCost(Request $req){
        $cte = CTEItemPekerjaan::where('code', '<', '200')
            ->orderBy('code', 'asc')
            ->get();
        $arrSum = array();
        $arrConCost = array();
        $sum = 0;
        foreach($cte as $c){
            $arrCoa = array();
            $isHasChild = $this->isIPKParentHasChild($c->id);
            if($isHasChild){
                $cteSub = ItemPekerjaans::where('code', '<', '200')->where('parent_id', $c->id)->orderBy('code', 'asc')->get();
                foreach($cteSub as $cs){
                    $arrTotal = array();
                    $isHasChild = $this->isIPKParentHasChild($cs->id);
                    if($isHasChild){
                        $getTm = LibraryHargaSatuanTotal::select('tm_id')->where('ipk_parent', $cs->id)->groupBy('tm_id')->get();
                        foreach($getTm as $gt){
                            $arr = array();
                            $coa = DB::table('v_library_hargasatuan_total')
                                    ->selectRaw("sum(tmd_nilai_grandtotal) as tmd_nilai_grandtotal, tm_id")
                                    ->where("ipk_parent", $cs->id)
                                    ->where("tm_id", $gt->tm_id)
                                    ->groupBy("tm_id")
                                    ->first();
                            $tag = LibraryHargaSatuanTotal::where('ipk_parent', $cs->id)
                                    ->where("tm_id", $gt->tm_id)
                                    ->where('ipk_isDivider', 1)
                                    ->first();
                            $volume = $tag->tmd_volume;
                            $total = ($volume != 0) ? ($coa->tmd_nilai_grandtotal / $volume) : 0;
                            $arrTotal[$gt->tm_id] = $total;
                        }
                    }
                    $arrCoa[$cs->id] = $arrTotal;
                }
            }
            $getTm_ = LibraryHargaSatuanTotal::selectRaw('tm_id, MAX(proj_code) as proj_code, MAX(proj_name) as proj_name')->where('coa', '<', '200')->groupBy('tm_id')->get();
            $arr_ = array();
            foreach($getTm_ as $g){
                $tag_ = LibraryHargaSatuanTotal::where('ipk_parent', $c->id)
                                    ->where("tm_id", $gt->tm_id)
                                    ->where('ipk_isDivider', 1)
                                    ->first();
                $volume_ = ($tag_ != null) ? $tag_->tmd_volume : 0;
                $sum = ($volume_ != 0) ? (array_sum(array_column($arrCoa, $g->tm_id)) / $volume_) : 0;
                $arr_[$g->proj_code] = $sum;
                
            }
                $max = max($arr_);
                $min = min($arr_);
                $objj = new \stdClass();
                $objj->coa = $c->code;
                $objj->ipk_name = $c->name;
                $objj->ipk_id = $c->id;
                $objj->ipk_parent = $c->parent_id;
                $objj->ipk_child_parent = $c->child_parent_id;
                $objj->min_proj_code = array_search(min($arr_), $arr_);
                $objj->min_proj_name = ""; //$min->project_name;
                $objj->min_nilai = $min; //$minNilai;
                $objj->max_proj_code = array_search(max($arr_), $arr_);
                $objj->max_proj_name = ""; //$max->project_code;
                $objj->max_nilai = $max; //$maxNilai;
                $objj->satuan = "";//$tag->satuan;
                $arrConCost[] = $objj;
        }
        $data = new Collection($arrConCost);
        $dt = Datatables::of($data)->make(true);
        return $dt;
    }

    public function getH(){
        $cte = CTEItemPekerjaan::where('code', '<', '200')
            ->orderBy('code', 'asc')
            ->get();
        $arrSum = array();
        $arrConCost = array();
        $sum = 0;
        foreach($cte as $c){
            $arrCoa = array();
            $isHasChild = $this->isIPKParentHasChild($c->id);
            if($isHasChild){
                $cteSub = ItemPekerjaans::where('code', '<', '200')->where('parent_id', $c->id)->orderBy('code', 'asc')->get();
                foreach($cteSub as $cs){
                    $arrTotal = array();
                    $isHasChild = $this->isIPKParentHasChild($cs->id);
                    if($isHasChild){
                        $getTm = LibraryHargaSatuanTotal::select('tm_id')->where('ipk_parent', $cs->id)->groupBy('tm_id')->get();
                        foreach($getTm as $gt){
                            $arr = array();
                            $coa = DB::table('v_library_hargasatuan_total')
                                    ->selectRaw("sum(tmd_nilai_grandtotal) as tmd_nilai_grandtotal, tm_id")
                                    ->where("ipk_parent", $cs->id)
                                    ->where("tm_id", $gt->tm_id)
                                    ->groupBy("tm_id")
                                    ->first();
                            $tag = LibraryHargaSatuanTotal::where('ipk_parent', $cs->id)
                                    ->where("tm_id", $gt->tm_id)
                                    ->where('ipk_isDivider', 1)
                                    ->first();
                            $volume = $tag->tmd_volume;
                            $total = ($volume != 0) ? ($coa->tmd_nilai_grandtotal / $volume) : 0;
                            $arrTotal[$gt->tm_id] = $total;
                        }
                    }
                    $arrCoa[$cs->id] = $arrTotal;
                }
            }
            $getTm_ = LibraryHargaSatuanTotal::selectRaw('tm_id, MAX(proj_code) as proj_code, MAX(proj_name) as proj_name')->where('coa', '<', '200')->groupBy('tm_id')->get();
            $arr_ = array();
            foreach($getTm_ as $g){
                $tag_ = LibraryHargaSatuanTotal::where('ipk_parent', $c->id)
                                    ->where("tm_id", $gt->tm_id)
                                    ->where('ipk_isDivider', 1)
                                    ->first();
                $volume_ = ($tag_ != null) ? $tag_->tmd_volume : 0;
                $sum = ($volume_ != 0) ? (array_sum(array_column($arrCoa, $g->tm_id)) / $volume_) : 0;
                $arr_[$g->proj_code] = $sum;
                
            }
                $max = max($arr_);
                $min = min($arr_);
                $objj = new \stdClass();
                $objj->coa = $c->code;
                $objj->ipk_name = $c->name;
                $objj->ipk_id = $c->id;
                $objj->ipk_parent = $c->parent_id;
                $objj->ipk_child_parent = $c->child_parent_id;
                $objj->min_proj_code = array_search(min($arr_), $arr_);
                $objj->min_proj_name = ""; //$min->project_name;
                $objj->min_nilai = $min; //$minNilai;
                $objj->max_proj_code = array_search(max($arr_), $arr_);
                $objj->max_proj_name = ""; //$max->project_code;
                $objj->max_nilai = $max; //$maxNilai;
                $objj->satuan = "";//$tag->satuan;
                $arrConCost[] = $objj;
        }
        
        $data = new Collection($arrConCost);
        return response()->json($data, 200);
    }

    public function getAjaxCoaItemDevCost(Request $request){

        $coaParent = $request->parent;
        $coa = LibraryHargaSatuanTotal::select(['ipk_id', 'coa', 'ipk_name', 'ipk_parent', 'proj_id'])
            ->where('coa', '>=', '200')
            ->where('coa', '<', '300')
            ->where('ipk_parent', $coaParent)
            ->groupBy(['ipk_id', 'coa', 'ipk_name', 'ipk_parent', 'proj_id'])
            ->get();
        $arr = array();
        foreach($coa as $c){
            $tag = LibraryHargaSatuanTotal::where('ipk_id', $c->ipk_id)->first();
            $obj = new \stdClass();
            $max = $this->getMaxNilaiCoa($c->coa, $c->proj_id);
            $min = $this->getMinNilaiCoa($c->coa, $c->proj_id);
            $obj->coa = $c->coa;
            $obj->ipk_name = $c->ipk_name;
            $obj->ipk_parent = $c->ipk_parent;
            $obj->min_proj_code = $min->proj_code;
            $obj->min_proj_name = $min->proj_name;
            $obj->min_nilai = $min->tmd_nilai_total;
            $obj->max_proj_code = $max->proj_code;
            $obj->max_proj_name = $max->proj_name;
            $obj->max_nilai = $max->tmd_nilai_total;
            $obj->satuan = $max->tmd_satuan;
            $obj->volume = $max->tmd_volume;
            $obj->ipk_isDivider = $tag->ipk_isDivider;
            $arr[] = $obj;
        }
        $data = new Collection($arr);
        return response()->json($data, 200);
    }


    public function getAjaxCoaItemConCost(Request $request){
        $coaParent = $request->parent;
        $coa = CTEJoin::select(['id', 'code', 'name', 'parent_id', 'isDivider'])
            ->where('code', '<', '200')
            ->where('parent_id', $coaParent)
            ->groupBy(['id', 'code', 'name', 'parent_id', 'isDivider'])
            ->orderBy('code', 'asc')
            ->get();
        $arr = array();
        foreach($coa as $c){
            $obj = new \stdClass();
            $tag = LibraryHargaSatuanTotal::where('ipk_parent', $c->parent_id)
                ->where('ipk_isDivider', 1)
                ->first();
            $det = CTEJoin::where('id', $c->id)
                ->orderBy('code', 'asc')
                ->first();
            $obj->coa = $c->code;
            $obj->ipk_id = $c->id;
            $obj->ipk_name = $c->name;
            $obj->ipk_parent = $c->parent_id;
            if($det != null){
                $volume = ($tag != null) ? $tag->tmd_volume : 0;
                $max = $this->getCTEMaxNilaiCoa($c->id, $c->parent_id);
                $min = $this->getCTEMinNilaiCoa($c->id, $c->parent_id);
                $obj->min_proj_code = ($min != null) ? $min->proj_code : "";
                $obj->min_proj_name = ($min != null) ? $min->proj_name : "";
                $obj->min_nilai = ($min != null) ? $min->tmd_nilai_grandtotal : 0;
                $obj->max_proj_code = ($max != null) ? $max->proj_code : "";
                $obj->max_proj_name = ($max != null) ? $max->proj_name : "";
                $obj->max_nilai = ($max != null) ? $max->tmd_nilai_grandtotal : 0;
                $obj->satuan = ($det->satuan != null) ? $det->satuan : "";
                $obj->volume = ($det->volume != null) ? $det->volume : 0;
                $obj->ipk_isDivider = $c->isDivider;
            }else{
                $obj->min_proj_code = "";
                $obj->min_proj_name = "";
                $obj->min_nilai = 0;
                $obj->max_proj_code = "";
                $obj->max_proj_name = "";
                $obj->max_nilai = 0;
                $obj->satuan = "";
                $obj->volume = 0;
                $obj->ipk_isDivider = 0;
            }
            $arr[] = $obj;
        }
        $data = new Collection($arr);
        return response()->json($data, 200);
    }

    public function getCTEMaxNilaiCoa($ipkId_, $parentId_){
        $isHasChild = $this->isIPKParentHasChild($ipkId_);
        $arrMax = array();
        if($isHasChild){
            $obj = new \stdClass();
            $tm_id = 0;
            $groupTmId = LibraryHargaSatuanTotal::select('tm_id')->where('ipk_parent', $ipkId_)->groupBy('tm_id')->get();
            foreach($groupTmId as $g){
                $coa = DB::table('v_library_hargasatuan_total')
                        ->selectRaw("sum(tmd_nilai_grandtotal) as tmd_nilai_grandtotal, tm_id")
                        ->where("ipk_parent", $ipkId_)
                        ->where("tm_id", $g->tm_id)
                        ->groupBy("tm_id")
                        ->first();
                $tag = LibraryHargaSatuanTotal::where('ipk_parent', $ipkId_)
                        ->where('ipk_isDivider', 1)
                        ->where("tm_id", $g->tm_id)
                        ->first();
                $volume = ($tag != null) ? $tag->tmd_volume : 0;
                $grandTotalMax = ($volume != 0) ? ($coa->tmd_nilai_grandtotal / $volume) : 0;
                $tm_id = $coa->tm_id;
                $arrMax[] = $grandTotalMax;
            }
            $proj = LibraryHargaSatuanTotal::select('proj_id', 'proj_name', 'proj_code', 'tmd_satuan')
                ->where('tm_id',$tm_id)
                ->where('ipk_parent',$ipkId_)
                ->first();
            $obj->proj_id = ($proj != null) ? $proj->proj_id : 0;
            $obj->proj_name = ($proj != null) ? $proj->proj_name : "";
            $obj->proj_code = ($proj != null) ? $proj->proj_code : "";
            $obj->tmd_satuan = ($proj != null) ? $proj->tmd_satuan : "";
            $obj->tmd_nilai_grandtotal = max($arrMax);
            return $obj;
        }else{
            $coa = LibraryHargaSatuanTotal::where("ipk_id", $ipkId_)
                ->whereRaw("tmd_nilai_grandtotal = (select MAX(tmd_nilai_grandtotal)
                                                                from v_library_hargasatuan_total
                                                                where ipk_id = '$ipkId_' )")
                ->orderBy('tmd_id', 'desc')
                ->first();
            $min = $coa;
            return $coa;
        }
    }

    public function getCTEMinNilaiCoa($ipkId_, $parentId_){
        $isHasChild = $this->isIPKParentHasChild($ipkId_);
        $arrMin = array();
        if($isHasChild){
            $obj = new \stdClass();
            $tm_id = 0;
            $groupTmId = LibraryHargaSatuanTotal::select('tm_id')->where('ipk_parent', $ipkId_)->groupBy('tm_id')->get();
            foreach($groupTmId as $g){
                $coa = DB::table('v_library_hargasatuan_total')
                    ->selectRaw("sum(tmd_nilai_grandtotal) as tmd_nilai_grandtotal, tm_id")
                    ->where("ipk_parent", $ipkId_)
                    ->where("tm_id", $g->tm_id)
                    ->groupBy("tm_id")
                    ->first();
                $tag = LibraryHargaSatuanTotal::where('ipk_parent', $ipkId_)
                    ->where('ipk_isDivider', 1)
                    ->where("tm_id", $g->tm_id)
                    ->first();
                $volume = ($tag != null) ? $tag->tmd_volume : 0;
                $grandTotalMin = ($volume != 0) ? ($coa->tmd_nilai_grandtotal / $volume) : 0;
                $tm_id = $coa->tm_id;
                $arrMin[] = $grandTotalMin;
            }
            $proj = LibraryHargaSatuanTotal::select('proj_id', 'proj_name', 'proj_code', 'tmd_satuan')
                ->where('tm_id',$tm_id)
                ->where('ipk_parent',$ipkId_)
                ->first();
            $obj->proj_id = $proj->proj_id;
            $obj->proj_name = $proj->proj_name;
            $obj->proj_code = $proj->proj_code;
            $obj->tmd_satuan = $proj->tmd_satuan;
            $obj->tmd_nilai_grandtotal = min($arrMin);
            return $obj;
        }else{
            $coa = LibraryHargaSatuanTotal::where("ipk_id", $ipkId_)
                ->whereRaw("tmd_nilai_grandtotal = (select MIN(tmd_nilai_grandtotal)
                                                                from v_library_hargasatuan_total
                                                                where ipk_id = '$ipkId_' )")
                ->orderBy('tmd_id', 'desc')
                ->first();
            return $coa;
        }
    }

    public function getMaxNilaiCoa($coa_, $projId_){
        $coa = LibraryHargaSatuanTotal::where("coa", $coa_)
            ->where("proj_id", $projId_)
            ->whereRaw("tmd_nilai_total = (select MAX(tmd_nilai_total)
                                       from v_library_hargasatuan_total
                                       where coa = '$coa_' and proj_id = '$projId_' )")
            ->orderBy('tmd_id', 'desc')
            ->first();
        return $coa;
    }

    public function getMinNilaiCoa($coa_, $projId_){
        $coa = LibraryHargaSatuanTotal::where("coa", $coa_)
            ->where("proj_id", $projId_)
            ->whereRaw("tmd_nilai_total = (select MIN(tmd_nilai_total)
                                       from v_library_hargasatuan_total
                                       where coa = '$coa_' and proj_id = '$projId_' )")
            ->orderBy('tmd_id', 'desc')
            ->first();
        return $coa;
    }

    public function getListHargaSatuan(Request $request){
        $data = ItemPekerjaans::where('code', '>', '200')
            ->whereNotNull('parent_id')
            ->orderBy('code', 'asc')
            // ->whereRaw("code IN ( SELECT coa as code FROM v_library_hargasatuan_ GROUP BY coa )
            //             order by code asc")
            ->get();
        return response()->json($data, 200);

    }

    public function setIsDivider(Request $request){
        $id = $request->id;
        $isDivider = $request->isDivider;
        $item = ItemPekerjaans::find($id);
        $item->isDivider = $isDivider;
        $item->save();
        if($item){
            $response = ["status" => "success"];
        }else{
            $response = ["status" => "error"];
        }
        return response()->json($response, 200);
    }

    public function showTes(Request $request)
    {
        $user = \Auth::user();
        $project = Project::find($request->session()->get('project_id'));
        return view('library::tes',compact("project","user"));
    }


    public static function isIPKParentHasChild($parent){
        $db = DB::table('v_cte_itempekerjaan_sub')
            ->where('parent_id', $parent)
            ->count();

        return ($db > 0) ? true : false;
    }

}

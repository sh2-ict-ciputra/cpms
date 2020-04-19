<?php

namespace Modules\Report\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Project\Entities\Project;
use Modules\Project\Entities\ProjectKawasan;
use Modules\Budget\Entities\Budget;
use Modules\Budget\Entities\BudgetDetail;
use Modules\Budget\Entities\BudgetTahunan;
use Modules\Budget\Entities\BudgetTahunanPeriode;
use Modules\Spk\Entities\Spk;
use Modules\User\Entities\User;
use Modules\Spk\Entities\Bap;
use Illuminate\Support\Facades\DB;

class Report2Controller extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){

    }

    public function costreport(Request $request){
    	$user = User::find(\Auth::user()->id);
    	$project = Project::find($request->session()->get('project_id'));
    	$report = Spk::all();
    	return view('report::cost_report',compact("user","project","report"));
    }

    public function costreportss(Request $request){
        $columns = array( 
                            0 =>'no',
                            1 =>'name', 
                            // 2 =>'netto_kawasan',
                            // 3=> 'jumlah_blok',
                            // 4=> 'jumlah_unit',
                            // 5=> 'status_lahan',
                            // 6=> 'edit_blok',
                            // 7=> 'edit_kawasan',
                            // 8=> 'delete',
                        );
        
        // $project = Project::find($request->id_project);
        $totalData = Spk::all()->count();
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        // $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
            
        if(empty($request->input('search.value')))
        {            
            $posts = Spk::offset($start)
                         ->limit($limit)
                         ->orderBy('id','desc')
                         ->get();
        }
        else {
            $search = $request->input('search.value'); 

            $posts =  Spk::all()->where('id','LIKE',"%{$search}%")
                            ->orWhere('name', 'LIKE',"%{$search}%")
                            ->offset($start)
                            ->limit($limit)
                            // ->orderBy($order,$dir)
                            ->get();

            $totalFiltered = Spk::all()->where('id','LIKE',"%{$search}%")
                             ->orWhere('name', 'LIKE',"%{$search}%")
                             ->count();
        }

        $data = array();
        if(!empty($posts))
        {
            foreach ($posts as $post)
            {
                // $show =  route('posts.show',$post->id);
                // $edit =  route('posts.edit',$post->id);

                $nestedData['kapekno'] = $post->project->name."<br>".$post->no;
                $nestedData['tgl_spk'] =  date('d/M/Y',strtotime($post->date)) ;
                $nestedData['acuan'] = 3;
                $nestedData['pekerjaan'] = $post->name;
                $nestedData['rekanan'] =  $post->rekanan->name ;
                $nestedData['nilai_spk'] = 'Rp. ' .number_format($post->nilai);
                $nestedData['nilai_vo'] = 'Rp. ' .number_format($post->vo);

                $nilai_spk = $post->nilai;
                $nilai_vo = $post->nilai_vo;
                $tot = $nilai_spk + $nilai_vo;

                $nestedData['tgl_st1'] = $post->st_1;
                $nestedData['tgl_st2'] = $post->st_2;
                $nestedData['spknvo'] = 'Rp. '.number_format($tot);
                $nestedData['progres_lap'] = $post->lapangan .' %';
                $nestedData['prog_bap'] = $post->progress_sebelumnya. ' %';
                $nestedData['bap_terbayar'] = 'Rp. '.number_format($bap = $post->baps->sum('nilai_bap_dibayar'));
                $nestedData['budget'] = 12;
                $nestedData['kontrak'] = 'Rp. '.number_format($tot - $bap);
                $nestedData['terbayar'] = 14;
                $data[] = $nestedData;

            }
        }
          
        $json_data = array(
                    "draw"            => intval($request->input('draw')),  
                    "recordsTotal"    => intval($totalData),  
                    "recordsFiltered" => intval($totalFiltered), 
                    "data"            => $data   
                    );

        echo json_encode($json_data);
    }

    public function reportkontraktorss(Request $request){
        $project = Project::find($request->session()->get('project_id'));

        $columns = array( 
                            0 =>'no',
                            // 1 =>'name', 
                            // 2 =>'netto_kawasan',
                            // 3=> 'jumlah_blok',
                            // 4=> 'jumlah_unit',
                            // 5=> 'status_lahan',
                            // 6=> 'edit_blok',
                            // 7=> 'edit_kawasan',
                            // 8=> 'delete',
                        );
        
        // $project = Project::find($request->id_project);
        // $totalData = Spk::all()->count();
        // $totalFiltered = $totalData; 

        // $limit = $request->input('length');
        // $start = $request->input('start');
        // // $order = $columns[$request->input('order.0.column')];
        // $dir = $request->input('order.0.dir');
            
        // if(empty($request->input('search.value')))
        // {            
        //     $posts = Spk::offset($start)
        //                  ->limit($limit)
        //                 //  ->orderBy($order,$dir)
        //                  ->get();
        // }
        // else {
        //     $search = $request->input('search.value'); 

        //     $posts =  Spk::all()->where('id','LIKE',"%{$search}%")
        //                     ->orWhere('name', 'LIKE',"%{$search}%")
        //                     ->offset($start)
        //                     ->limit($limit)
        //                     // ->orderBy($order,$dir)
        //                     ->get();

        //     $totalFiltered = Spk::all()->where('id','LIKE',"%{$search}%")
        //                      ->orWhere('name', 'LIKE',"%{$search}%")
        //                      ->count();
        // }
        $posts = Spk::where('project_id',$project->id)->orderBy('rekanan_id','asc')->get();
        $data = array();
        $nestedData = [];
        $spk = '';
        // $nilai_spk = 0;
        
        // if(!empty($posts))
        // {
            $idrek = '';
    
            foreach ($posts as $post => $value)
            {
                // $show =  route('posts.show',$post->id);
                // $edit =  route('posts.edit',$post->id);
                // if($data == null){
                // foreach ($value->rekanan->spks as $key => $value2) {
                //    // if(count($value2) != null){
                //         $spk = $value2->id;
                //     // }
                // } 

                    $idrek = $value->rekanan->id;

                    // $nilai_spk = $nilai_spk + $value->nilai;
                    $nilai_spk = 0;
                    $nilai_vo = 0;
                    // foreach (Spk::where('rekanan_id',$value->rekanan_id)->get() as $key2 => $value2) {
                    //     # code...
                    //     $nilai_spk += $value2->nilai;
                    // }
                    // $spk = Spk::where('rekanan_id',$value->rekanan_id)->get();
                
                    // foreach (Spk::where('rekanan_id',$value->rekanan_id)->get() as $key2 => $value2) {
                    //     # code...
                    //     $nilai_spk += $value2->nilai;
                    //     $nilai_vo += $value2->nilai_vo;
                    // }

                    // foreach (Spk::where('rekanan_id',$value->rekanan_id)->get() as $key2 => $value2) {
                    //     # code...
                    //     $nilai_vo += $value2->nilai_vo;
                    // }
                    $sub_termyn = 0;
                    $total_termyn = 0;
                    foreach ($value->baps as $key => $value28){
                        # code...
                        if($value28->vouchers_date_cair != null){
                            if($value28->vouchers_date_cair->pencairan_date != null){
                                if($value28->st_status != 1){
                                    $total_termyn += $value28->nilai_bap2;
                                }else{
                                    $total_termyn += $value28->pph * $nilai_kontrak * $value2->retensis->sum('percent');
                                }
                            }
                        }

                    }
                    $sub_termyn = $sub_termyn + $total_termyn;

                    $nestedData['kosong'] = 1;
                    $nestedData['idrek'] =$value->rekanan_id;
                    $nestedData['rekanan'] = $value->rekanan->name;
                    $nestedData['spk'] = $value->nilai;
                    $nestedData['vo'] = $value->nilai_vo;
                    $nestedData['termyn'] = $sub_termyn;
                    // $nestedData['idspk'] = $value->id;
                    // $nestedData[$post->rekanan->id]['namerek'] = $post->rekanan->name;
                    // if($nestedData[$post->rekanan->id]['tonilai_spk']==null){
                    // $nestedData[$post->rekanan->id]['tonilai_spk'] = $post->nilai;

                    // }

                // }

                // if($bap!=null){
                //     $bap2 = Bap::find($bap->id);
                //     $spk1 = $bap2->spk;
                //     if(1 <=$bap2->st_status){
                //         $total_termyn = $spk1->baps->where('termin','<=',$spk1->baps->termin)->sum('nilai_bap_2') + ($spk1->baps->where('st_status',1)->first()->pph * $sub_total_dpp * $post->retensis->sum('percent'));
                //     }else{
                //         $total_termyn = $spk1->baps->where('termin','<=',$bap2->termin)->sum('nilai_bap_2');
                //     }
                // }
                // // $sub_total_dpp = $post->nilai + $post->nilai_vo;

                // // $nilai_progres = $nilai_progres_hingga_saat_ini + $nilai_talangan - $retensi - $pengembalian_dp - $pembayaran_kumulatif;
                // $sisa_kontrak = $tot - $total_termyn;
                // $nestedData['totmin'] = 'Rp. '. number_format($total_termyn);
                // $nestedData['sisakontrak'] = 'Rp. '.number_format($sisa_kontrak);;
                // $nestedData['tgl_st1'] = date('d/M/Y',strtotime($post->st_1));
                // $nestedData['tgl_st2'] = date('d/M/Y',strtotime($post->st_2));
                // $data[] = $nestedData;
                    array_push($data, $nestedData);

            // }
        }
          
        // $json_data = array(
        //             "draw"            => intval($request->input('draw')),  
        //             "recordsTotal"    => intval($totalData),  
        //             "recordsFiltered" => intval($totalFiltered), 
        //             "data"            => $data   
        //             );

        // echo json_encode($json_data);
        return datatables()->of($data)->toJson();
    }

    public function detailkaw(Request $request){
        $project = Project::find($request->session()->get('project_id'));
        $posts = Spk::where('rekanan_id',$request->id_rekanan)->orderBy('rekanan_id','asc')->get();
        $data = array();
        $nestedData = [];
        $spk = '';
        $kaw = '';
        // $nilai_spk = 0;
        
        // if(!empty($posts))
        // {
            $idrek = '';
    
            foreach ($posts as $post => $value)
            {
                // $show =  route('posts.show',$post->id);
                // $edit =  route('posts.edit',$post->id);
                // if($data == null){
                // foreach ($value->rekanan->spks as $key => $value2) {
                //    // if(count($value2) != null){
                //         $spk = $value2->id;
                //     // }
                // } 

                    $idrek = $value->rekanan->id;

                    // $nilai_spk = $nilai_spk + $value->nilai;
                    $nilai_spk = 0;
                    $nilai_vo = 0;
 
                    $sub_termyn = 0;
                    $total_termyn = 0;
                    // foreach ($value->baps as $key => $value28){
                    //     # code...
                    //     if($value28->vouchers_date_cair != null){
                    //         if($value28->vouchers_date_cair->pencairan_date != null){
                    //             if($value28->st_status != 1){
                    //                 $total_termyn += $value28->nilai_bap2;
                    //             }else{
                    //                 $total_termyn += $value28->pph * $nilai_kontrak * $value2->retensis->sum('percent');
                    //             }
                    //         }
                    //     }

                    // }
                    // $sub_termyn = $sub_termyn + $total_termyn;
                    if($value->tender->rab->workorder->projectKawasan != ''){
                            $kaw = $value->tender->rab->workorder->projectKawasan->name;
                        }else{
                            $kaw = 'Fasilitas Kota';
                        }

                    $nestedData['kawasan'] = $kaw;
                    // $nestedData['rekanan'] = $value->rekanan->name;
                    $nestedData['nilai_spk'] = $value->nilai;
                    $nestedData['nilai_vo'] = $value->nilai_vo;

                    $tot = $value->nilai + $value->nilai_vo;

                    $nestedData['total_kontrak'] = $tot;
                    $nestedData['nilai_termyn'] = $sub_termyn;

                    $sisa = $tot - $sub_termyn;

                    $nestedData['sisa_kontrak'] = $sisa;
                    $nestedData['nospk'] = $value->no;
                    $nestedData['tgl_spk'] = date('d/M/Y',strtotime($value->date));
                    $nestedData['pekerjaan'] = $value->name;
                    $nestedData['st1'] = date('d/M/Y',strtotime($value->st_1));
                    $nestedData['st2'] = date('d/M/Y',strtotime($value->st_2));

                    array_push($data, $nestedData);

            // }
        }
          
        // $json_data = array(
        //             "draw"            => intval($request->input('draw')),  
        //             "recordsTotal"    => intval($totalData),  
        //             "recordsFiltered" => intval($totalFiltered), 
        //             "data"            => $data   
        //             );

        // echo json_encode($json_data);
        return datatables()->of($data)->toJson();
    }

    public function concostdetailss(Request $request){
        $project = Project::find($request->session()->get('project_id'));
        $posts = Spk::where('project_id',$project->id)->orderBy('project_kawasan_id','asc')->get();
        $data = array();
        $nestedData = [];
        $spk = '';
        $kaw = '';
        $type = '';
        $luas_bang = 0;
        $aksi = 0;
        
        // $nilai_spk = 0;
        
        // if(!empty($posts))
        // {
            $idrek = '';
    
            foreach ($posts as $post => $value)
            {
                // $show =  route('posts.show',$post->id);
                // $edit =  route('posts.edit',$post->id);
                // if($data == null){
                // foreach ($value->rekanan->spks as $key => $value2) {
                //    // if(count($value2) != null){
                //         $spk = $value2->id;
                //     // }
                // } 

                    $idrek = $value->rekanan->id;
                    $kontrak = 0;
                    $nilai_spk = 0;
                    $nilai_vo = 0;
                    $percepatan = 0;
                    // $nilai_spk = $nilai_spk + $value->nilai;
                    
 
                    $sub_termyn = 0;
                    $total_termyn = 0;
                    foreach ($value->baps as $key => $value28){
                        # code...
                        if($value28->vouchers_date_cair != null){
                            if($value28->vouchers_date_cair->pencairan_date != null){
                                if($value28->st_status != 1){
                                    $total_termyn += $value28->nilai_bap_2;
                                }else{
                                    $total_termyn += $value28->pph * $nilai_kontrak * $value2->retensis->sum('percent');
                                }
                            }
                        }

                    }
                    $sub_termyn = $sub_termyn + $total_termyn;

                    if($value->tender->rab->workorder->projectKawasan != ''){
                            // $kaw = $value->tender->rab->workorder->projectKawasan->name;
                        foreach ($value->tender->rab->workorder->projectKawasan->unit_type as $key => $value2) {
                            # code...
                            $type = $value2->name;
                            $luas_bang = $value2->luas_bangunan;
                            $aksi = $value2->cluster_id;
                            
                        }
                           
                        }else{
                            // $kaw = 'Fasilitas Kota';
                            $type = 'Non Type';
                        }
                   
                    $kontrak = $value->nilai + $value->nilai_vo + $value->nilai_percepatan ;
                    // $nestedData['kawasan'] = $kaw;
                    $nestedData['type'] = $type;
                    // $nestedData['luas_bangunan'] = $luas_bang;
                    $nestedData['kontrak'] = $kontrak;
                    $nestedData['rab'] = 3;
                    $nestedData['prog_lap'] = $value->lapangan;
                    $nestedData['prog_bap'] = $value->progress_sebelumnya;
                    $nestedData['terbayar_tot'] = $sub_termyn;
                    $nestedData['aksi'] = $aksi;
                    // $sisa = $tot - $sub_termyn;

                    // $nestedData['sisa_kontrak'] = $sisa;
                    // $nestedData['nospk'] = $value->no;
                    // $nestedData['tgl_spk'] = date('d/M/Y',strtotime($value->date));
                    // $nestedData['pekerjaan'] = $value->name;
                    // $nestedData['st1'] = date('d/M/Y',strtotime($value->st_1));
                    // $nestedData['st2'] = date('d/M/Y',strtotime($value->st_2));

                    array_push($data, $nestedData);

            // }
        }
          
        // $json_data = array(
        //             "draw"            => intval($request->input('draw')),  
        //             "recordsTotal"    => intval($totalData),  
        //             "recordsFiltered" => intval($totalFiltered), 
        //             "data"            => $data   
        //             );

        // echo json_encode($json_data);
        return datatables()->of($data)->toJson();
    }

    public function detailconcost(Request $request){
        $project = Project::find($request->session()->get('project_id'));
        $posts = Spk::where('project_kawasan_id',$request->id_rekanan)->orderBy('project_kawasan_id','asc')->get();
        $data = array();
        $nestedData = [];
        $spk = '';
        $kaw = '';
        // $nilai_spk = 0;
        
        // if(!empty($posts))
        // {
            $idrek = '';
    
            foreach ($posts as $post => $value)
            {
                // $show =  route('posts.show',$post->id);
                // $edit =  route('posts.edit',$post->id);
                // if($data == null){
                // foreach ($value->rekanan->spks as $key => $value2) {
                //    // if(count($value2) != null){
                //         $spk = $value2->id;
                //     // }
                // } 

                    $idrek = $value->rekanan->id;

                    // $nilai_spk = $nilai_spk + $value->nilai;
                    $nilai_spk = 0;
                    $nilai_vo = 0;
 
                    $sub_termyn = 0;
                    $total_termyn = 0;
                    // foreach ($value->baps as $key => $value28){
                    //     # code...
                    //     if($value28->vouchers_date_cair != null){
                    //         if($value28->vouchers_date_cair->pencairan_date != null){
                    //             if($value28->st_status != 1){
                    //                 $total_termyn += $value28->nilai_bap2;
                    //             }else{
                    //                 $total_termyn += $value28->pph * $nilai_kontrak * $value2->retensis->sum('percent');
                    //             }
                    //         }
                    //     }

                    // }
                    // $sub_termyn = $sub_termyn + $total_termyn;
                    if($value->tender->rab->workorder->projectKawasan != ''){
                            $kaw = $value->tender->rab->workorder->projectKawasan->name;
                        }else{
                            $kaw = 'Fasilitas Kota';
                        }

                    $nestedData['kawasan'] = $kaw;
                    // $nestedData['rekanan'] = $value->rekanan->name;
                    // $nestedData['nilai_spk'] = $value->nilai;
                    // $nestedData['nilai_vo'] = $value->nilai_vo;

                    // $tot = $value->nilai + $value->nilai_vo;

                    // $nestedData['total_kontrak'] = $tot;
                    // $nestedData['nilai_termyn'] = $sub_termyn;

                    // $sisa = $tot - $sub_termyn;

                    // $nestedData['sisa_kontrak'] = $sisa;
                    // $nestedData['nospk'] = $value->no;
                    // $nestedData['tgl_spk'] = date('d/M/Y',strtotime($value->date));
                    // $nestedData['pekerjaan'] = $value->name;
                    // $nestedData['st1'] = date('d/M/Y',strtotime($value->st_1));
                    // $nestedData['st2'] = date('d/M/Y',strtotime($value->st_2));

                    array_push($data, $nestedData);

            // }
        }
          
        // $json_data = array(
        //             "draw"            => intval($request->input('draw')),  
        //             "recordsTotal"    => intval($totalData),  
        //             "recordsFiltered" => intval($totalFiltered), 
        //             "data"            => $data   
        //             );

        // echo json_encode($json_data);
        return datatables()->of($data)->toJson();
    }

    public function reportkawasanss(Request $request){
        $project = Project::find($request->session()->get('project_id'));
        $columns = array( 
                            0 =>'name',
                            1 =>'lahan_luas', 
                            // 2 =>'netto_kawasan',
                            // 3=> 'jumlah_blok',
                            // 4=> 'jumlah_unit',
                            // 5=> 'status_lahan',
                            // 6=> 'edit_blok',
                            // 7=> 'edit_kawasan',
                            // 8=> 'delete',
                        );
        
        // $project = Project::find($request->id_project);
        $totalData = ProjectKawasan::where("project_id",$project->id)->count();
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        // $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
            
        if(empty($request->input('search.value')))
        {            
            $posts = ProjectKawasan::where("project_id",$project->id)->offset($start)
                         ->limit($limit)
                        //  ->orderBy($order,$dir)
                         ->get();
        }
        else {
            $search = $request->input('search.value'); 

            $posts =  ProjectKawasan::where("project_id",$project->id)->where('id','LIKE',"%{$search}%")
                            ->orWhere('name', 'LIKE',"%{$search}%")
                            ->offset($start)
                            ->limit($limit)
                            // ->orderBy($order,$dir)
                            ->get();

            $totalFiltered = ProjectKawasan::where("project_id",$project->id)->where('id','LIKE',"%{$search}%")
                             ->orWhere('name', 'LIKE',"%{$search}%")
                             ->count();
        }

        $data = array();
        if(!empty($posts))
        {
            foreach ($posts as $post)
            {
                $nilai_spk = 0;
                $tot_nilaivo = 0;
                $percepatan = 0;
                $kontrak = 0;
                $termyn = 0;
                $total_termyn = 0;
                $sub_termyn = 0;

                foreach ($post->workorder as $key => $value) {
                    foreach ($value->rabs as $key2 => $value2) {
                        if(count($value2->tenders) != 0){
                            if(count($value2->tenders[0]->spks) != 0){
                                $nilai_spk = $nilai_spk + $value2->tenders[0]->spks[0]->nilai;
                                $tot_nilaivo =  $tot_nilaivo + $value2->tenders[0]->spks[0]->nilai_vo;
                                $percepatan = $percepatan + $value2->tenders[0]->spks[0]->nilai_percepatan;

                                $bap = $value2->tenders[0]->spks[0]->baps;
                                $kontrak = $nilai_spk + $tot_nilaivo + $percepatan;
                                $total_termyn = 0;
                                foreach ($value2->tenders[0]->spks[0]->baps as $key => $value28){
                                    # code...
                                    if($value28->vouchers_date_cair != null){
                                        if($value28->vouchers_date_cair->pencairan_date != null){
                                            if($value28->st_status != 1){
                                                $total_termyn += $total_termyn+ $value28->nilai_bap_2;
                                            }else{
                                                $total_termyn += $value28->pph * $kontrak * $value2->tenders[0]->spks[0]->retensis->sum('percent');
                                            }
                                        }
                                    }

                                }
                                $sub_termyn = $sub_termyn + $total_termyn;
                                // if(count($bap)!=0){
                                //     if(1 <= $bap->last()->st_status ){
                                //        $total_termyn = $value2->tenders[0]->spks[0]->baps->where('termin','<=',$bap->last()->termin)->sum('nilai_bap_2') + ($value2->tenders[0]->spks[0]->baps->where('st_status',1)->first()->pph * $nilai_kontrak * $value2->tenders[0]->spks[0]->retensis->sum('percent'));
                                //     }else{
                                //         $total_termyn = $value2->tenders[0]->spks[0]->baps->where('termin','<=',$bap->last()->termin)->sum('nilai_bap_2');
                                        
                                //     }
                                //     $sub_termyn = $sub_termyn + $total_termyn;
                                // }
                           }
                        }
                    }
                }           

                
                $nestedData['kawasan'] = $post->name;
                $nestedData['nilai_spk'] = $nilai_spk ;
                $nestedData['nilai_vo'] = $tot_nilaivo ;
                $nestedData['percepatan'] = $percepatan ;
                $nestedData['total_kontrak'] = $kontrak ;
                $nestedData['total_termyn'] = $sub_termyn ;

                $sisa = $kontrak - $sub_termyn;

                $nestedData['sisa_kontrak'] = $sisa ;
                $nestedData['aksi'] = '<a class="btn btn-sm btn-primary"  data-toggle="modal" title="Edit" 
                onclick="pekerjaan('."'".$post->id."'".","."'".$post->name."'".')" >Detail</a>';
                $data[] = $nestedData;

            }
        }
          
        $json_data = array(
                    "draw"            => intval($request->input('draw')),  
                    "recordsTotal"    => intval($totalData),  
                    "recordsFiltered" => intval($totalFiltered), 
                    "data"            => $data   
                    );

        echo json_encode($json_data); 
          
    }

    public function detailpek(Request $request){
        $idkaw = $request->id_item;
        $kawasan = ProjectKawasan::find($idkaw);
        $spk = [];
        $data = [];
        $nilai_kontrak = 0;
        $nilaispk = 0;
        $nilaivo = 0;
        $nilaiper = 0;
        $spk['namekaw'] = $kawasan->name;

        foreach ($kawasan->workorder as $key => $value) {
                    foreach ($value->rabs as $key2 => $value2) {
                        if(count($value2->tenders) != 0){
                            if(count($value2->tenders[0]->spks) != 0){
                                $spk['id'] = $value2->tenders[0]->spks[0]->id;
                                $spk['pekerjaan'] = $value2->tenders[0]->spks[0]->name;
                                $spk['tgl_spk'] = date("d/M/Y",strtotime($value2->tenders[0]->spks[0]->date));
                                $spk['rekanan'] = $value2->tenders[0]->spks[0]->rekanans->name;
                                $spk['nilai_spk'] = $value2->tenders[0]->spks[0]->nilai;
                                $spk['nilai_vo'] = $value2->tenders[0]->spks[0]->nilai_vo;
                                $spk['nilai_percepatan'] = $value2->tenders[0]->spks[0]->nilai_percepatan;

                                $nilaispk = $value2->tenders[0]->spks[0]->nilai;
                                $nilaivo = $value2->tenders[0]->spks[0]->nilai_vo;
                                $nilaiper = $value2->tenders[0]->spks[0]->nilai_percepatan;
                                $nilai_kontrak = $nilaispk + $nilaivo + $nilaiper;

                                $spk['nilai_kontrak'] =  $nilai_kontrak;

                                $bap = $value2->tenders[0]->spks[0]->baps;

                                $total_termyn = 0;

                                foreach ($value2->tenders[0]->spks[0]->baps as $key => $value28){
                                    # code...
                                    if($value28->vouchers_date_cair != null){
                                        if($value28->vouchers_date_cair->pencairan_date != null){
                                            if($value28->st_status != 1){
                                                $total_termyn += $value28->nilai_bap_2;
                                            }else{
                                                $total_termyn += $value28->pph * $nilai_kontrak * $value2->tenders[0]->spks[0]->retensis->sum('percent');
                                            }
                                        }
                                    }

                                }
                                // $sub_termyn = $sub_termyn + $total_termyn;

                                // if(count($bap)!=0){
                                //     if(1 <= $bap->last()->st_status ){
                                //        $total_termyn = $value2->tenders[0]->spks[0]->baps->where('termin','<=',$bap->last()->termin)->sum('nilai_bap_2') + ($value2->tenders[0]->spks[0]->baps->where('st_status',1)->first()->pph * $nilai_kontrak * $value2->tenders[0]->spks[0]->retensis->sum('percent'));
                                //     }else{
                                //         $total_termyn = $value2->tenders[0]->spks[0]->baps->where('termin','<=',$bap->last()->termin)->sum('nilai_bap_2');
                                        
                                //     }
                                // }

                                $spk['termyn'] = $total_termyn;

                                $sisa = $nilai_kontrak - $total_termyn;

                                $spk['sisa'] = $sisa;
                                $spk['nospk'] = $value2->tenders[0]->spks[0]->no;
                                $spk['st1'] = date("d/M/Y",strtotime($value2->tenders[0]->spks[0]->st_1));
                                $spk['st2'] = date("d/M/Y",strtotime($value2->tenders[0]->spks[0]->st_2));
                                
                                array_push($data, $spk);
                              }
                        }
                    }
                }
            // echo json_encode($spk);
            return datatables()->of($data)->toJson();
    }

    public function devcostdetailss(Request $request){
        $project = Project::find($request->session()->get('project_id'));
        $columns = array( 
                            0 =>'name',
                            1 =>'lahan_luas', 
                            // 2 =>'netto_kawasan',
                            // 3=> 'jumlah_blok',
                            // 4=> 'jumlah_unit',
                            // 5=> 'status_lahan',
                            // 6=> 'edit_blok',
                            // 7=> 'edit_kawasan',
                            // 8=> 'delete',
                        );
        
        // $project = Project::find($request->id_project);
        $totalData = ProjectKawasan::where("project_id",$project->id)->count();
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        // $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
            
        if(empty($request->input('search.value')))
        {            
            $posts = ProjectKawasan::where("project_id",$project->id)->offset($start)
                         ->limit($limit)
                        //  ->orderBy($order,$dir)
                         ->get();
        }
        else {
            $search = $request->input('search.value'); 

            $posts =  ProjectKawasan::where("project_id",$project->id)->where('id','LIKE',"%{$search}%")
                            ->orWhere('name', 'LIKE',"%{$search}%")
                            ->offset($start)
                            ->limit($limit)
                            // ->orderBy($order,$dir)
                            ->get();

            $totalFiltered = ProjectKawasan::where("project_id",$project->id)->where('id','LIKE',"%{$search}%")
                             ->orWhere('name', 'LIKE',"%{$search}%")
                             ->count();
        }

        $data = array();
        if(!empty($posts))
        {
            foreach ($posts as $post)
            {
                $nilai_spk = 0;
                $tot_nilaivo = 0;
                $percepatan = 0;
                $kontrak = 0;
                $termyn = 0;
                $total_termyn = 0;
                $sub_termyn = 0;
                $bap_terbayar = 0;
                $jumlah_spk = 0;
                $total_proglap = 0;
                $total_progbap = 0;
                $rata2_progbap = 0;
                $rata2_proglap = 0;
                $tahun = 0;
                $tot_bap_skrg = 0;
                $kontrak_tahun = 0;
                $bap_tahun = 0;
                $nilai_kontrak = 0;

                foreach ($post->workorder as $key => $value) {
                    foreach ($value->rabs as $key2 => $value2) {
                        if(count($value2->tenders) != 0){
                            if(count($value2->tenders[0]->spks) != 0){

                                if($request->op_tahun == 2019){
                                    // $tahun = (int)date('Y',strtotime($value2->tenders[0]->spks[0]->date));
                                    $tahun = 2019;
                                }else{
                                    $tahun = $request->op_tahun;
                                }

                                if(date('Y',strtotime($value2->tenders[0]->spks[0]->date)) == $tahun){
                                    $tot_nilai_skrg = $value2->tenders[0]->spks[0]->nilai + $value2->tenders[0]->spks[0]->nilai_vo + $value2->tenders[0]->spks[0]->nilai_percepatan;
                                    $kontrak_tahun = $kontrak_tahun + $tot_nilai_skrg;

                                    $total_termyn2 = 0;
                                    foreach ($value2->tenders[0]->spks[0]->baps as $key => $value29){
                                    # code...
                                    if($value29->vouchers_date_cair != null){
                                        if($value29->vouchers_date_cair->pencairan_date != null){
                                            if($value29->st_status != 1){
                                                $total_termyn2 += $value29->nilai_bap_2;
                                            }else{
                                                $total_termyn2 += $value29->pph * $tot_nilai_skrg * $value2->tenders[0]->spks[0]->retensis->sum('percent');
                                            }
                                        }
                                    }
                                }
                                $bap_tahun = $bap_tahun + $total_termyn2;
                                }

                                $nilai_spk = $nilai_spk + $value2->tenders[0]->spks[0]->nilai;
                                $tot_nilaivo =  $tot_nilaivo + $value2->tenders[0]->spks[0]->nilai_vo;
                                $percepatan = $percepatan + $value2->tenders[0]->spks[0]->nilai_percepatan;
                                $bap_terbayar = $value2->tenders[0]->spks[0]->baps->sum('nilai_bap_dibayar');
                                $total_proglap = $total_proglap + $value2->tenders[0]->spks[0]->lapangan;
                                $total_progbap = $total_progbap + $value2->tenders[0]->spks[0]->progress_sebelumnya;
                                $jumlah_spk = $jumlah_spk + 1;
                                
                                $bap = $value2->tenders[0]->spks[0]->baps;
                                $nilai_kontrak = $nilai_kontrak + $nilai_spk + $tot_nilaivo + $percepatan;
                                $total_termyn = 0;
                                foreach ($value2->tenders[0]->spks[0]->baps as $key => $value28){
                                    # code...
                                    if($value28->vouchers_date_cair != null){
                                        if($value28->vouchers_date_cair->pencairan_date != null){
                                            if($value28->st_status != 1){
                                                $total_termyn += $value28->nilai_bap_2;
                                            }else{
                                                $total_termyn += $value28->pph * $nilai_kontrak * $value2->tenders[0]->spks[0]->retensis->sum('percent');
                                            }
                                        }
                                    }
                                }
                                $sub_termyn = $sub_termyn + $total_termyn;
                                // if(count($bap)!=0){
                                //     if(1 <= $bap->last()->st_status ){
                                //        $total_termyn = $value2->tenders[0]->spks[0]->baps->where('termin','<=',$bap->last()->termin)->sum('nilai_bap_2') + ($value2->tenders[0]->spks[0]->baps->where('st_status',1)->first()->pph * $nilai_kontrak * $value2->tenders[0]->spks[0]->retensis->sum('percent'));
                                //     }else{
                                //         $total_termyn = $value2->tenders[0]->spks[0]->baps->where('termin','<=',$bap->last()->termin)->sum('nilai_bap_2');
                                        
                                //     }
                                //     $sub_termyn = $sub_termyn + $total_termyn;
                                // }
                           }
                        }
                    }
                }    

                if($total_progbap !=0){
                    $rata2_progbap = number_format(($total_progbap/$jumlah_spk), 2, '.', '');
                }else{
                    $rata2_progbap = 0;
                }

                if($total_proglap !=0){
                    $rata2_proglap = number_format(($total_proglap/$jumlah_spk), 2, '.', '');
                }else{
                    $rata2_proglap = 0;
                }

                $kontrak = $nilai_spk + $tot_nilaivo + $percepatan;
                $nestedData['kawasan'] = $post->name;
                $nestedData['budget_awal'] = 2;
                $nestedData['budget_akhir'] = 3;
                $nestedData['kontrak_total'] = $kontrak;
                $nestedData['kontrak_tahun'] = $kontrak_tahun;
                $nestedData['proglap'] = $rata2_proglap;
                $nestedData['progbap'] = $rata2_progbap;
                $nestedData['terbayar_tot'] = $sub_termyn;
                $nestedData['terbayar_tah'] = $bap_tahun;
                $nestedData['salbud_awal'] = 10;
                $nestedData['salbud_tah'] = 11;

                $terbayar = $kontrak - $bap_terbayar;
                $nestedData['saldo_total'] = $terbayar;
 
                $nestedData['aksi'] = '<a class="btn btn-sm btn-primary"  data-toggle="modal" title="Edit" 
                onclick="pekerjaan('."'".$post->id."'".","."'".$post->name."'".')" >Detail</a>';
                $data[] = $nestedData;

            }
        }
          
        $json_data = array(
                    "draw"            => intval($request->input('draw')),  
                    "recordsTotal"    => intval($totalData),  
                    "recordsFiltered" => intval($totalFiltered), 
                    "data"            => $data   
                    );

        echo json_encode($json_data); 
    }

    public function detaildevcost(Request $request){
        $idkaw = $request->id_item;
        $kawasan = ProjectKawasan::find($idkaw);
        $spk = [];
        $data = [];
        $nilai_kontrak = 0;
        $nilai_spk = 0;
        $nilaivo = 0;
        $nilaiper = 0;
        $sub_termyn = 0;
        $sub_termyn2 = 0;
        $spk['namekaw'] = $kawasan->name;      
        $kontrak_tahun = 0;

        foreach ($kawasan->workorder as $key => $value) {
                    foreach ($value->rabs as $key2 => $value2) {
                        if(count($value2->tenders) != 0){
                            if(count($value2->tenders[0]->spks) != 0){
                           
                                $tot_nilai_skrg = 0;
                                $bap_tahun = 0;
                                if(date('Y',strtotime($value2->tenders[0]->spks[0]->date)) == $request->tahun){
                                    $tot_nilai_skrg = $value2->tenders[0]->spks[0]->nilai + $value2->tenders[0]->spks[0]->nilai_vo + $value2->tenders[0]->spks[0]->nilai_percepatan;
                                    // $kontrak_tahun = $kontrak_tahun + $tot_nilai_skrg;
                                        $total_termyn2 = 0;
                                        foreach ($value2->tenders[0]->spks[0]->baps as $key => $value22){
                                        # code...
                                        if($value22->vouchers_date_cair != null){
                                            if($value22->vouchers_date_cair->pencairan_date != null){
                                                if($value22->st_status != 1){
                                                    $total_termyn2 += $value22->nilai_bap_2;
                                                }else{
                                                    $total_termyn2 += $value22->pph * $tot_nilai_skrg * $value2->tenders[0]->spks[0]->retensis->sum('percent');
                                                }
                                            }
                                        }

                                    }
                                    $sub_termyn2 = $sub_termyn2 + $total_termyn2;

                                }

                                $total_termyn = 0;
                                foreach ($value2->tenders[0]->spks[0]->baps as $key => $value28){
                                    # code...
                                    if($value28->vouchers_date_cair != null){
                                        if($value28->vouchers_date_cair->pencairan_date != null){
                                            if($value28->st_status != 1){
                                                $total_termyn += $value28->nilai_bap_2;
                                            }else{
                                                $total_termyn += $value28->pph * $tot_nilai_skrg * $value2->tenders[0]->spks[0]->retensis->sum('percent');
                                            }
                                        }
                                    }

                                }
                                $sub_termyn = $sub_termyn + $total_termyn;

                                $nilai_spk = $value2->tenders[0]->spks[0]->nilai_vo +  $value2->tenders[0]->spks[0]->nilai + $value2->tenders[0]->spks[0]->nilai_percepatan;

                                $spk['id'] = $value2->tenders[0]->spks[0]->id;
                                $spk['budget_tahun'] = 2;
                                $spk['budget_kontrak'] = 3;
                                $spk['totnil'] = $nilai_spk;
                                $spk['kontrak_tahun'] = $tot_nilai_skrg;
                                $spk['prog_lap'] = $value2->tenders[0]->spks[0]->lapangan;
                                $spk['prog_bap'] = $value2->tenders[0]->spks[0]->progress_sebelumnya;
                                $spk['terbayar_tot'] = $sub_termyn;
                                $spk['terbayar_tah'] = $sub_termyn2;
                                // $spk['a'] = 'Rp. '. number_format($nilai_spk);
                                $spk['pekerjaan'] = $value2->tenders[0]->spks[0]->name;


                                // $spk['nilai_kontrak'] =  'Rp. '. number_format($nilai_kontrak);

                                // $bap = $value2->tenders[0]->spks[0]->baps;

                                // $total_termyn = 0;

                                // foreach ($value2->tenders[0]->spks[0]->baps as $key => $value28){
                                //     # code...
                                //     if($value28->vouchers_date_cair != null){
                                //         if($value28->vouchers_date_cair->pencairan_date != null){
                                //             if($value28->st_status != 1){
                                //                 $total_termyn += $value28->nilai_bap2;
                                //             }else{
                                //                 $total_termyn += $value28->pph * $nilai_kontrak * $value2->tenders[0]->spks[0]->retensis->sum('percent');
                                //             }
                                //         }
                                //     }

                                // }
                                // $sub_termyn = $sub_termyn + $total_termyn;

                                // if(count($bap)!=0){
                                //     if(1 <= $bap->last()->st_status ){
                                //        $total_termyn = $value2->tenders[0]->spks[0]->baps->where('termin','<=',$bap->last()->termin)->sum('nilai_bap_2') + ($value2->tenders[0]->spks[0]->baps->where('st_status',1)->first()->pph * $nilai_kontrak * $value2->tenders[0]->spks[0]->retensis->sum('percent'));
                                //     }else{
                                //         $total_termyn = $value2->tenders[0]->spks[0]->baps->where('termin','<=',$bap->last()->termin)->sum('nilai_bap_2');
                                        
                                //     }
                                // }

                                // $sisa = $nilai_kontrak - $total_termyn;

                                // $spk['sisa'] = 'Rp. '. number_format($sisa);
                                
                                array_push($data, $spk);
                              }
                        }
                    }
                }
            // echo json_encode($spk);
            return datatables()->of($data)->toJson();
    }

    public function reportpekerjaanss(Request $request){
        $project = Project::find($request->session()->get('project_id'));
        $kaw = '';
        $columns = array( 
                            0 =>'no',
                            1 =>'name', 
                            // 2 =>'netto_kawasan',
                            // 3=> 'jumlah_blok',
                            // 4=> 'jumlah_unit',
                            // 5=> 'status_lahan',
                            // 6=> 'edit_blok',
                            // 7=> 'edit_kawasan',
                            // 8=> 'delete',
                        );
        
        // $project = Project::find($request->id_project);
        // $totalData = Spk::where('project_id',$project->id)->count();
        // $totalFiltered = $totalData; 

        // $limit = $request->input('length');
        // $start = $request->input('start');
        // // $order = $columns[$request->input('order.0.column')];
        // $dir = $request->input('order.0.dir');
            
        // if(empty($request->input('search.value')))
        // {            
        //     $posts = Spk::where('project_id',$project->id)->offset($start)
        //                  ->limit($limit)
        //                  // ->orderBy($order,$dir)
        //                  ->get();
        // }
        // else {
        //     $search = $request->input('search.value'); 

        //     $posts =  Spk::where('project_id',$project->id)->where('id','LIKE',"%{$search}%")
        //                     ->orWhere('name', 'LIKE',"%{$search}%")
        //                     ->offset($start)
        //                     ->limit($limit)
        //                     // ->orderBy($order,$dir)
        //                     ->get();

        //     $totalFiltered = Spk::where('project_id',$project->id)->where('id','LIKE',"%{$search}%")
        //                      ->orWhere('name', 'LIKE',"%{$search}%")
        //                      ->count();
        // }
        $posts = Spk::where('project_id',$project->id)->orderBy('name','asc')->get();
        $data = array();
        $nestedData = [];
        // if(!empty($posts))
        // {
            foreach ($posts as $post => $value)
            {
                //spk->tenders->rabs->workorder->kawasan

                // $show =  route('posts.show',$post->id);
                // $edit =  route('posts.edit',$post->id);

                $nestedData['reknspk'] = $value->name;
                // $nestedData['tgl_spk'] =  date('d/M/Y',strtotime($post->date)) ;

                if($value->tender->rab->workorder->projectKawasan != ''){
                    $kaw = $value->tender->rab->workorder->projectKawasan->name;
                }else{
                    $kaw = 'Fasilitas Kota';
                }

                $nestedData['acuan'] = $kaw;
                // $nestedData['pekerjaan'] = $post->name;
                // $nestedData['rekanan'] =  $post->rekanan->name ;
                // $nestedData['tgl_st1'] = date('d/M/Y',strtotime($post->st_1));
                // $nestedData['tgl_st2'] = date('d/M/Y',strtotime($post->st_2));
                // $nestedData['nilai_spk'] = 'Rp. ' .number_format($post->nilai);
                // $nestedData['nilai_vo'] = 'Rp. ' .number_format($post->vo);

                // $nilai_spk = $post->nilai;
                // $nilai_vo = $post->nilai_vo;
                // $tot = $nilai_spk + $nilai_vo;

                // $bap = $post->baps->last();
                                
                // $total_termyn = 0;

                // if($bap!=null){
                //     $bap2 = Bap::find($bap->id);
                //     $spk1 = $bap2->spk;
                //     if(1 <=$bap2->st_status){
                //         $total_termyn = $spk1->baps->where('termin','<=',$spk1->baps->termin)->sum('nilai_bap_2') + ($spk1->baps->where('st_status',1)->first()->pph * $sub_total_dpp * $post->retensis->sum('percent'));
                //     }else{
                //         $total_termyn = $spk1->baps->where('termin','<=',$bap2->termin)->sum('nilai_bap_2');
                //     }
                // }

                // $nestedData['spknvo'] = 'Rp. '.number_format($tot);
                // $nestedData['totmin'] = 'Rp. '.number_format($total_termyn);
                // $nestedData['sisakontrak'] = 'Rp. '. number_format($tot - $total_termyn);

                // $data[] = $nestedData;
                array_push($data, $nestedData);

            }
        // }
          
        // $json_data = array(
        //             "draw"            => intval($request->input('draw')),  
        //             "recordsTotal"    => intval($totalData),  
        //             "recordsFiltered" => intval($totalFiltered), 
        //             "data"            => $data   
        //             );

        // echo json_encode($json_data);
        return datatables()->of($data)->toJson();

    }

    // public function concostdetailss(Request $request){
    //     $columns = array( 
    //                         0 =>'no',
    //                         1 =>'name', 
    //                         // 2 =>'netto_kawasan',
    //                         // 3=> 'jumlah_blok',
    //                         // 4=> 'jumlah_unit',
    //                         // 5=> 'status_lahan',
    //                         // 6=> 'edit_blok',
    //                         // 7=> 'edit_kawasan',
    //                         // 8=> 'delete',
    //                     );
        
    //     // $project = Project::find($request->id_project);
    //     $totalData = Spk::all()->count();
    //     $totalFiltered = $totalData; 

    //     $limit = $request->input('length');
    //     $start = $request->input('start');
    //     // $order = $columns[$request->input('order.0.column')];
    //     $dir = $request->input('order.0.dir');
            
    //     if(empty($request->input('search.value')))
    //     {            
    //         $posts = Spk::offset($start)
    //                      ->limit($limit)
    //                     //  ->orderBy($order,$dir)
    //                      ->get();
    //     }
    //     else {
    //         $search = $request->input('search.value'); 

    //         $posts =  Spk::all()->where('id','LIKE',"%{$search}%")
    //                         ->orWhere('name', 'LIKE',"%{$search}%")
    //                         ->offset($start)
    //                         ->limit($limit)
    //                         // ->orderBy($order,$dir)
    //                         ->get();

    //         $totalFiltered = Spk::all()->where('id','LIKE',"%{$search}%")
    //                          ->orWhere('name', 'LIKE',"%{$search}%")
    //                          ->count();
    //     }

    //     $data = array();
    //     if(!empty($posts))
    //     {
    //         foreach ($posts as $post)
    //         {
    //             // $show =  route('posts.show',$post->id);
    //             // $edit =  route('posts.edit',$post->id);

    //             $nestedData['reknspk'] = $post->name."<br>".$post->no;
    //             $nestedData['tgl_spk'] =  date('d/M/Y',strtotime($post->date)) ;
    //             $nestedData['acuan'] = 3;
    //             $nestedData['pekerjaan'] = $post->name;
    //             $nestedData['rekanan'] =  $post->rekanan->name ;
    //             $nestedData['tgl_st1'] = date('d/M/Y',strtotime($post->st_1));
    //             $nestedData['tgl_st2'] = date('d/M/Y',strtotime($post->st_2));
    //             $nestedData['nilai_spk'] = 'Rp. ' .number_format($post->nilai);
    //             $nestedData['nilai_vo'] = 'Rp. ' .number_format($post->vo);

    //             $nilai_spk = $post->nilai;
    //             $nilai_vo = $post->nilai_vo;
    //             $tot = $nilai_spk + $nilai_vo;

    //             $bap = $post->baps->last();
                                
    //             $total_termyn = 0;

    //             if($bap!=null){
    //                 $bap2 = Bap::find($bap->id);
    //                 $spk1 = $bap2->spk;
    //                 if(1 <=$bap2->st_status){
    //                     $total_termyn = $spk1->baps->where('termin','<=',$spk1->baps->termin)->sum('nilai_bap_2') + ($spk1->baps->where('st_status',1)->first()->pph * $sub_total_dpp * $post->retensis->sum('percent'));
    //                 }else{
    //                     $total_termyn = $spk1->baps->where('termin','<=',$bap2->termin)->sum('nilai_bap_2');
    //                 }
    //             }

    //             $nestedData['spknvo'] = 'Rp. '.number_format($tot);
    //             $nestedData['totmin'] = 'Rp. '.number_format($total_termyn);
    //             $nestedData['sisakontrak'] = 'Rp. '. number_format($tot - $total_termyn);

    //             $data[] = $nestedData;

    //         }
    //     }
          
    //     $json_data = array(
    //                 "draw"            => intval($request->input('draw')),  
    //                 "recordsTotal"    => intval($totalData),  
    //                 "recordsFiltered" => intval($totalFiltered), 
    //                 "data"            => $data   
    //                 );

    //     echo json_encode($json_data);
    // }    

    public function reportkontraktor(Request $request){
    	$user = User::find(\Auth::user()->id);
    	$project = Project::find($request->session()->get('project_id'));
    	return view('report::report_by_kontraktor',compact("user","project"));
    }

    public function reportkawasan(Request $request){
    	$user = User::find(\Auth::user()->id);
    	$project = Project::find($request->session()->get('project_id'));
        foreach (ProjectKawasan::where('project_id',$project->id)->get() as $key3 => $value3) {
             // foreach ($value3->workorder as $key => $value) {
             //        foreach ($value->rabs as $key2 => $value2) {
             //            return $value2->tenders;
             //            $nilai_spk = $nilai_spk + $value2->tenders->spks->nilai;
             //        }
             //    }
            
        }
    	return view('report::report_by_kawasan',compact("user","project"));
    }

    public function reportpekerjaan(Request $request){
    	$user = User::find(\Auth::user()->id);
    	$project = Project::find($request->session()->get('project_id'));
    	return view('report::report_by_pekerjaan',compact("user","project"));
    }

    public function concostdetail(Request $request){
        $user = User::find(\Auth::user()->id);
        $project = Project::find($request->session()->get('project_id'));
        return view('report::concost_detail',compact("user","project"));
    }

    public function concostsummary(Request $request){
        $user = User::find(\Auth::user()->id);
        $project = Project::find($request->session()->get('project_id'));
        return view('report::concost_summary',compact("user","project"));
    }

    public function devcostdetail(Request $request){
        $user = User::find(\Auth::user()->id);
        $project = Project::find($request->session()->get('project_id'));

        $total_unit = DB::table('projects')
                        ->where("projects.id",$project->id)
                        ->where("project_kawasans.deleted_at",null)
                        ->where("bloks.deleted_at",null)
                        ->where("units.deleted_at",null)
                        ->where("units.is_sellable",1)
                        ->leftJoin('project_kawasans','project_kawasans.project_id','projects.id')
                        ->leftJoin('bloks','bloks.project_kawasan_id','project_kawasans.id')
                        ->leftJoin('units','units.blok_id','bloks.id')
                        ->groupBy('projects.id','projects.luas')
                        ->select('projects.id as id', DB::raw("(sum(units.tanah_luas)) as luas_netto"),"projects.luas as luas_brutto")
                        ->get();

        $total_netkaw = DB::table('projects')
                        ->where("projects.id",$project->id)
                        ->where("project_kawasans.deleted_at",null)
                        ->where("bloks.deleted_at",null)
                        ->where("units.deleted_at",null)
                        // ->where("units.is_sellable",1)
                        ->leftJoin('project_kawasans','project_kawasans.project_id','projects.id')
                        ->leftJoin('bloks','bloks.project_kawasan_id','project_kawasans.id')
                        ->leftJoin('units','units.blok_id','bloks.id')
                        ->groupBy('projects.id','projects.luas')
                        ->select('projects.id as id', DB::raw("(sum(units.tanah_luas)) as luas_kaw"))
                        ->get();

        return view('report::devcost_detail',compact("user","project","total_unit","total_netkaw"));
    }

    public function devcostsummary(Request $request){
        $user = User::find(\Auth::user()->id);
        $project = Project::find($request->session()->get('project_id'));
        return view('report::devcost_summary',compact("user","project"));
    }

    public function devcostsummaryss(Request $request){

        $project = Project::find($request->session()->get('project_id'));
        $columns = array( 
                            0 =>'name',
                            1 =>'lahan_luas', 
                            // 2 =>'netto_kawasan',
                            // 3=> 'jumlah_blok',
                            // 4=> 'jumlah_unit',
                            // 5=> 'status_lahan',
                            // 6=> 'edit_blok',
                            // 7=> 'edit_kawasan',
                            // 8=> 'delete',
                        );
        
        // $project = Project::find($request->id_project);
        $totalData = ProjectKawasan::where("project_id",$project->id)->count();
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        // $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
            
        if(empty($request->input('search.value')))
        {            
            $posts = ProjectKawasan::where("project_id",$project->id)->offset($start)
                         ->limit($limit)
                         // ->orderBy('name','asc')
                         ->get();
        }
        else {
            $search = $request->input('search.value'); 

            $posts =  ProjectKawasan::where("project_id",$project->id)->where('id','LIKE',"%{$search}%")
                            ->orWhere('name', 'LIKE',"%{$search}%")
                            ->offset($start)
                            ->limit($limit)
                            // ->orderBy('name','asc')
                            // ->orderBy($order,$dir)
                            ->get();

            $totalFiltered = ProjectKawasan::where("project_id",$project->id)->where('id','LIKE',"%{$search}%")
                             // ->orderBy('name','asc')
                             ->orWhere('name', 'LIKE',"%{$search}%")
                             ->count();
        }

        $data = array();
        if(!empty($posts))
        {
            foreach ($posts as $key20 => $post)
            {
                $nilai_spk = 0;
                $tot_nilaivo = 0;
                $percepatan = 0;
                $kontrak = 0;
                $termyn = 0;
                $total_termyn = 0;
                $sub_termyn = 0;
                $kontrak_netto = 0;
                $kontrak_brutto = 0;
                $realisasi_brutto = 0;
                $realisasi_netto = 0;

                 $luasan = DB::table('project_kawasans')
                            ->where("project_kawasans.project_id",$project->id)
                            ->where("project_kawasans.deleted_at",null)
                            ->where("bloks.deleted_at",null)
                            ->where("units.deleted_at",null)
                            ->leftJoin('bloks','bloks.project_kawasan_id','project_kawasans.id')
                            ->leftJoin('units','units.blok_id','bloks.id')
                            ->groupBy('project_kawasans.id','project_kawasans.name', 'project_kawasans.lahan_luas')
                            ->select('project_kawasans.id as id_kawasan','project_kawasans.name as name', DB::raw("(sum(units.tanah_luas)) as luas_netto"),DB::raw("(COUNT ( DISTINCT bloks.id )) AS jumlah_blok"),DB::raw("(COUNT (units.id)) as jumlah_unit"), 'project_kawasans.id as edit_blok', 'project_kawasans.id as edit_kawasan', DB::raw("(COUNT (bloks.id)) as deleted"), 'project_kawasans.id as status_lahan','project_kawasans.lahan_luas as lahan_luas')
                            // ->orderBy('project_kawasans.name','asc')
                            // ->orderBy($order,$dir)
                            // ->offset($start)
                            ->limit($limit)
                            ->get();

                            foreach ($luasan as $key => $value) {
                                $luas['nama'] = $value->name;
                                $luas['netto'] = $value->luas_netto;
                                $luas['brutto'] = $value->lahan_luas;

                                $data2[] = $luas;
                            }

                foreach ($post->workorder as $key => $value) {
                    foreach ($value->rabs as $key2 => $value2) {
                        if(count($value2->tenders) != 0){
                            if(count($value2->tenders[0]->spks) != 0){
                                $nilai_spk = $nilai_spk + $value2->tenders[0]->spks[0]->nilai;
                                $tot_nilaivo =  $tot_nilaivo + $value2->tenders[0]->spks[0]->nilai_vo;
                                $percepatan = $percepatan + $value2->tenders[0]->spks[0]->nilai_percepatan;

                                $bap = $value2->tenders[0]->spks[0]->baps;
                                $kontrak = $nilai_spk + $tot_nilaivo + $percepatan;
                                $total_termyn = 0;
                                foreach ($value2->tenders[0]->spks[0]->baps as $key => $value28){
                                    # code...
                                    if($value28->vouchers_date_cair != null){
                                        if($value28->vouchers_date_cair->pencairan_date != null){
                                            if($value28->st_status != 1){
                                                $total_termyn += $total_termyn+ $value28->nilai_bap_2;
                                            }else{
                                                $total_termyn += $value28->pph * $kontrak * $value2->tenders[0]->spks[0]->retensis->sum('percent');
                                            }
                                        }
                                    }

                                }
                                $sub_termyn = $sub_termyn + $total_termyn;

                                                                // if(count($bap)!=0){
                                //     if(1 <= $bap->last()->st_status ){
                                //        $total_termyn = $value2->tenders[0]->spks[0]->baps->where('termin','<=',$bap->last()->termin)->sum('nilai_bap_2') + ($value2->tenders[0]->spks[0]->baps->where('st_status',1)->first()->pph * $nilai_kontrak * $value2->tenders[0]->spks[0]->retensis->sum('percent'));
                                //     }else{
                                //         $total_termyn = $value2->tenders[0]->spks[0]->baps->where('termin','<=',$bap->last()->termin)->sum('nilai_bap_2');
                                        
                                //     }
                                //     $sub_termyn = $sub_termyn + $total_termyn;
                                // }
                           }
                        }
                    }
                }    
            
                $nestedData['kawasan'] = $data2[$key20]['nama'];
                $nestedData['efesiensi'] = 2;
                $nestedData['netto'] = $data2[$key20]['netto'];
                $nestedData['brutto'] = $data2[$key20]['brutto'];
                $nestedData['total_budget'] = 5;
                $nestedData['budget_netto'] = 6;
                $nestedData['budget_brutto'] = 7;
                $nestedData['kontrak_total'] = $kontrak ;

                if($data2[$key20]['netto'] != 0){
                    $kontrak_netto = ($kontrak/$data2[$key20]['netto']);
                    $realisasi_netto = $sub_termyn/$data2[$key20]['netto'];
                }else{
                    $kontrak_netto = 0;
                }

                $kontrak_brutto = ($kontrak/$data2[$key20]['brutto']);
                $realisasi_brutto = $sub_termyn/$data2[$key20]['brutto'];

                $nestedData['kontrak_netto'] = $kontrak_netto;
                $nestedData['kontrak_brutto'] = $kontrak_brutto;
                $nestedData['total_termyn'] = $sub_termyn ;
                $nestedData['realisasi_netto'] = $realisasi_netto;
                $nestedData['realisasi_brutto'] = $realisasi_brutto;

                $data[] = $nestedData;

            }
        }
          
        $json_data = array(
                    "draw"            => intval($request->input('draw')),  
                    "recordsTotal"    => intval($totalData),  
                    "recordsFiltered" => intval($totalFiltered), 
                    "data"            => $data   
                    );

        echo json_encode($json_data); 
    }
}
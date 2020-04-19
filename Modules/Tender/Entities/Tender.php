<?php 
namespace Modules\Tender\Entities;

use App\CustomModel;
use App\Traits\Approval;

class Tender extends CustomModel
{
    use Approval;

    protected $fillable = ['rab_id','sumber','kelas_id','no','name','ambil_doc_date','aanwijzing_date','penawaran1_date','klarifikasi1_date','penawaran2_date','klarifikasi2_date','penawaran3_date','recommendation_date','pengumuman_date','harga_dokumen','description'];
    protected $dates = ['ambil_doc_date','aanwijzing_date','penawaran1_date','klarifikasi1_date','penawaran2_date','klarifikasi2_date','penawaran3_date','recommendation_date','pengumuman_date'];
    
	public function getPtAttribute()
    {
        // return $this->rab->workorder->project->first()->pt->first()->pt;
        if($this->rab->workorder->pt_id != ''){
            return $this->rab->workorder->pt_wo;
        }else{
            return $this->rab->workorder->pt;
        }
    }

    public function units()
    {
        return $this->hasMany('Modules\Tender\Entities\TenderUnit')->where('deleted_at',null);;
    }

    public function tersisa_units()
    {
        return $this->units()->whereDoesntHave('menangs');
    }

    public function getTersisaAssetsAttribute()
    {
        $unit_dimenangkan = Unit::whereHas('tender_menangs')->get(['id'])->toArray();

        return Unit::whereHas('rab_units', function($q)
        {
            $q->whereHas('tender_unit', function($q){
                $q->where('tender_id', $this->id);
            });

        })->whereNotIn('id',$unit_dimenangkan)->get();
    }

    public function rekanans()
    {
        return $this->hasMany('Modules\Tender\Entities\TenderRekanan')->where('deleted_at',null);;
    }

    public function penawarans()
    {
        return $this->hasManyThrough('Modules\Tender\Entities\TenderPenawaran', 'Modules\Tender\Entities\TenderRekanan');
    }

    public function menangs()
    {
        return $this->hasManyThrough('Modules\Tender\Entities\TenderMenang', 'Modules\Tender\Entities\TenderRekanan');
    }

    public function spks()
    {
        return $this->hasManyThrough('Modules\Spk\Entities\Spk', 'Modules\Tender\Entities\TenderRekanan');
    }

    public function rab()
    {
        return $this->belongsTo('Modules\Rab\Entities\Rab');
    }

    public function workorder()
    {
        return $this->belongsTo('Modules\Workorder\Entities\Workorder');
    }

    public function budget_tahunan()
    {
        return $this->belongsTo('Modules\Budget\Entities\BudgetTahunan');
    }

    public function budget()
    {
        return $this->belongsTo('Modules\Budget\Entities\Budget');
    }

    public function tender_rekanan()
    {
        return $this->belongsTo('Modules\Tender\Entities\TenderRekanan');
    }

    public function getNilaiAttribute()
    {
        $nilai = 0;
        
        // $nilai = $this->rab->nilai * count($this->units);
        if ($this->rab != null) {
            # code...
            $nilai = $this->rab->nilai;
        } else {
            # code...
            $nilai = 0;
        }
        

        return $nilai;
    }

    public function getSelfApprovalAttribute(){
        return $this->hasMany("Modules\Approval\Entities\ApprovalHistory","document_id");
    }

    public function getProjectAttribute(){
        if ( $this->rab != null ){
            if ( $this->rab->workorder != null ){
                return $this->rab->workorder->project;
            }else{
                return $this->rab;
            }
        }else{
            return null;
            // return "1";
        }
    }

    public function getTenderApproveAttribute(){
        $nilai = array();
        foreach ($this->rekanans as $key => $value) {
            if( $value->approval != "" ){
                if ( $value->approval->approval_action_id == "6"){
                    $nilai[$key] = $value->id;
                }
            }
        }

        return $nilai;
    }

    public function tender_document(){
        return $this->hasMany("Modules\Tender\Entities\TenderDocument");
    }

    public function tender_dokumen(){
        return $this->hasMany("Modules\Tender\Entities\TenderDocument");
    }

    public function getCheckRejectedAttribute(){
        $nilai = 0;
        $nilai_pending = 0;
        foreach ($this->tender_document as $key => $value) {
            if ( $value->rejected > 0 ){
                $nilai = $nilai + 1;
            }
        }

        foreach ($this->tender_document as $key => $value) {
            # code...
            if ( $value->pending > 0 ){
                $nilai_pending = $nilai_pending + 1;
            }
        }
        return $nilai;
    }

    public function tender_type(){
        return $this->belongsTo("Modules\TenderMaster\Entities\TenderMaster","kelas_id");
    }

    public function termyn(){
        return $this->hasMany("Modules\Spk\Entities\SpkTermyn");
    }

    public function retensi(){
        return $this->hasMany("Modules\Spk\Entities\SpkRetensi");
    }

    public function aanwijing(){
        return $this->hasOne("Modules\Tender\Entities\TenderAanwijings");
    }

    public function berita_acara(){
        return $this->hasMany("Modules\Tender\Entities\TenderBeritaAcaras");
    }

    public function getKawasanAttribute(){
        if($this->rab->workorder != null){
            if($this->rab->workorder->projectKawasan != null){
                return $this->rab->workorder->projectKawasan;
            }else{
                return null;
            }
        }else{
            return null;
        }
    }

    public function tender_jadwal_penawaran(){
        return $this->hasMany("Modules\Tender\Entities\TenderJadwalPenawaran");
    }

    public function tunjuk_pemenang_tender(){
        return $this->belongsTo("Modules\Tender\Entities\TunjukPemenangTender","id","tender_id");
    }

    public function berita_acara_tunjuk_langsung(){
        return $this->belongsTo("Modules\Tender\Entities\BeritaAcaraTunjukLangsung","id","tender_id");
    }

    public function pengembalian_dp(){
        return $this->hasMany("Modules\Spk\Entities\SpkPengembalianDp");
    }
}

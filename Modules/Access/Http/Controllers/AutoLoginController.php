<?php 
namespace Modules\Access\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\User;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
use Modules\Tender\Entities\TunjukPemenangTender;
use Modules\Project\Entities\ProjectPt;
use Modules\Project\Entities\UnitProgress;
use Modules\Tender\Entities\TenderUnit;


class AutoLoginController extends Controller
{
    public function autoLogin(Request $request){
        $data = explode("||", decrypt($request->code));
        // printf(date("d/M/Y"));
        // echo("<br>");
        // return $data[0];
        // date('d/M/Y', strtotime(date("Y-m-d")))
        $user = User::where('id',$data[1])->first();
            if($user){
                \Auth::login($user); // login user automatically
                // if(date("d/M/Y") => $data[2]){
                    return redirect($data[0]);
                // }else{
                //     return "Link ini Tidak berlaku!";
                // }
            }else {
                return "User not found!";
            }
        // return $data[0];
        // return decrypt($request->code);
    }

    public function redirectLogin(Request $request){
        $user = User::where('user_login',$request->user_login)->first();
        \Auth::login($user);
        return redirect("/");
    }

    public function sendEmail(Request $request){
        $encript = encrypt('https://cpms.ciputragroup.com:81/access/usulanPemenang/detail/?id=59||149||'.date('d/M/Y', strtotime('+ 7 day', strtotime(date("Y-m-d")))));
        $link = 'https://cpms.ciputragroup.com:81/access/login/?code='.$encript;
        $usulan = TunjukPemenangTender::find(59);

        $project_pt = ProjectPt::where("project_id",61)->first();
        // $data_usulan["email"]="arifiradat@ciputra.com";
        // $data_usulan["email"]="aghi.wardani@ciputra.com";
        $data_usulan["email"]="fajrika.hadnis@ciputra.com";
        $data_usulan["client_name"]="Arif Iradat";
        $data_usulan["subject"]='Approval Tunjuk Pemenang Tender';
        $user = User::find(149);
        // $encript = encrypt('https://cpms.ciputragroup.com:81/access/usulanPemenang/detail/?id='.$usulan->id.'||'.$approval_history_usulan->user->id.'||'.date('d/M/Y', strtotime('+ 7 day', strtotime(date("Y-m-d")))));
        // $link_usulan = 'https://cpms.ciputragroup.com:81/access/login/?code='.$encript;

        $title_usulan = "Tunjuk Pemenang Tender";

        Mail::send('mail.bodyEmailApprove', ['link' => $link, 'title' => $title_usulan, 'user' => $user, 'project_pt' => $project_pt, 'name' => $usulan->tender->name], function($message)use($data_usulan) {
            $message->from(env('MAIL_USERNAME'))->to($data_usulan["email"], $data_usulan["client_name"])->subject($data_usulan["subject"]);
        });

        
        return $link;
        return redirect("hai");
    }

    public function perbaikanUnitProgress(Request $request){
        $unit_progress = UnitProgress::orderby('id', 'descs')->get();

        foreach ($unit_progress as $key => $value) {
            # code...
            $tender_unit = TenderUnit::find($value->unit_id);
            $tender_menang = $tender_unit->tender->menangs->first();
            foreach ($tender_menang->tender_rekanan->penawarans->last()->details as $key2 => $value2) {
                $unit_progress = UnitProgress::where("id", $value->id)->where("itempekerjaan_id", $value2->rab_pekerjaan->itempekerjaan_id)->update(['total_nilai' => $value2->total_nilai]);
                // $unit_progress->total_nilai = $value2->total_nilai;
                // $unit_progress->save();
            }

        }
        return "succes";

    }

}
?>
<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::post('ces-login', function() 
{

	if(!isset($_POST["user_login"]))
	{
		header('location: https://ces-test.ciputragroup.com');
	}
	
	$hasil = (explode("~#~",$_POST["user_login"]));
	 echo 'User ID :'.$hasil[0].'<br>';
	 echo 'Username :'.$hasil[1].'<br>';

	$user = \App\User::where('user_login', $hasil[1])->first();

	if ($user) 
	{
		\Auth::loginUsingId($user->id);

		// if ( $user->is_pic == "1"){
		// 	Session::put("level","PIC");
		// 	// Session::put("project_id",$value['project_id']);
        //     // $request->session()->put('level', 'pic');
        //     return redirect("/progress");
		// }
		
		if ( $user->is_rekanan == 0 ){           
			$jabatan = $user->jabatan;
		
            foreach ($jabatan as $key => $value) {
                if ( $value['level'] == "10" || $value['level'] == "1016"){
                    //$_SESSION['level'] = $value['level'];
                    Session::put("level",$value['level']);
		    		Session::put("project_id",$value['project_id']);
                    return redirect("/project/detail?id=".$value['project_id']);
					//return redirect("/project/detail?id=".61);
                }elseif($value['level'] <= 7){
                    //$request->session()->put('level', '');
                    return redirect("/access");
                }elseif ( $user->is_pic == "1"){
					Session::put("level","PIC");
					// Session::put("project_id",$value['project_id']);
					// $request->session()->put('level', 'pic');
					return redirect("/progress");
				}
            }
        }else {
            $user_rekanan_group = UserRekanan::where("user_login",$user->user_login)->get();
            if ( count($user_rekanan_group) > 0 ){
                $users = UserRekanan::find($user_rekanan_group->first()->id);
                $rekanan_group = $users->rekanan_group;
                //$request->session()->put('rekanan_id', $rekanan_group->id);
                return redirect("rekanan/user");
            }else{
                return redirect("rekanan/user/fail");
            }
        }
		
		return redirect()->route('project');
	}else{
		return redirect('https://ces-test.ciputragroup.com');
	}

});

/*Route::post('ces-login', function() 
{
	if(!isset($_POST["user_login"]))
	{
		header('location: https://ces-test.ciputragroup.com');
	}

	$hasil = (explode("~#~",$_POST["user_login"]));
	// echo 'User ID :'.$hasil[0].'<br>';
	// echo 'Username :'.$hasil[1].'<br>';

	$user = \App\User::where('user_login', $hasil[1])->first();

	if ($user) 
	{
		\Auth::loginUsingId($user->id);

		return redirect()->route('project');
	}else{
		return redirect('https://ces-test.ciputragroup.com');
	}

});*/

Route::get('restricted-login', function() 
{

	if(!isset($_GET["user_login"]))
	{
		header('location: https://ces-test.ciputragroup.com');
	}
	
	$hasil = (explode("~#~",$_POST["user_login"]));
	 echo 'User ID :'.$hasil[0].'<br>';
	 echo 'Username :'.$hasil[1].'<br>';

	$user = \App\User::where('user_login', $hasil[1])->first();

	if ($user) 
	{
		\Auth::loginUsingId($user->id);

		// if ( $user->is_pic == "1"){
        //     $request->session()->put('level', 'pic');
        //     return redirect("/progress");
		// }

		if ( $user->is_rekanan == 0 ){           
			$jabatan = $user->jabatan;
		
            foreach ($jabatan as $key => $value) {
                if ( $value['level'] == "10" || $value['level'] == "1016"){
                    //$_SESSION['level'] = $value['level'];
                    Session::put("level",$value['level']);
		    		Session::put("project_id",$value['project_id']);
                    return redirect("/project/detail?id=".$value['project_id']);
					//return redirect("/project/detail?id=".61);
                }elseif($value['level'] == "10"){
                    //$request->session()->put('level', '');
                    return redirect("/access");
                }elseif ( $user->is_pic == "1"){
					$request->session()->put('level', 'pic');
					return redirect("/progress");
				}
            }
        }else {
            $user_rekanan_group = UserRekanan::where("user_login",$user->user_login)->get();
            if ( count($user_rekanan_group) > 0 ){
                $users = UserRekanan::find($user_rekanan_group->first()->id);
                $rekanan_group = $users->rekanan_group;
                //$request->session()->put('rekanan_id', $rekanan_group->id);
                return redirect("rekanan/user");
            }else{
                return redirect("rekanan/user/fail");
            }
        }
		
		return redirect()->route('project');
	}else{
		return redirect('https://ces-test.ciputragroup.com');
	}

});


Route::get('/', 'PrivilegeController@index')->middleware("auth");
Route::post('/login/validation','PrivilegeController@validation');
Route::get('/logout','PrivilegeController@destroy');
Route::post('/workorder/save-nonbudget','WorkorderController@savenonbudget');
Route::get('/public', function(){return redirect("/");});
Route::get('/public/login	', function(){return redirect("/");});
Route::post('/getJabatan','PrivilegeController@getJabatan');
Route::post('/changeProjectJabatan','PrivilegeController@changeProjectJabatan');

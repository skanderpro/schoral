<?php

namespace Qubants\Scholar\Controllers;


use DB;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Input;
use App\Notifications\Main as MainNotification;
use Session;


class AdmBlockController extends BaseController
{
	public function sendEmails(){
		$users_id = Input::get('users_id');
		$subject = Input::get('subject');
		$message = Input::get('message');


		foreach ($users_id as $id_user) {
			$user_details = DB::table('tbl_user')->where('id',$id_user)->first();
			\Mail::to($user_details->email)
				->queue(new MainNotification($user_details, $subject, $message));
		}

		return redirect()->back()->with(['success_msg'=>'Messages sent successfully']);
	}
}
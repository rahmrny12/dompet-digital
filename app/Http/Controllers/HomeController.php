<?php

namespace App\Http\Controllers;

use Notification;
use App\Models\RechargeHistory;
use App\Models\Transaction;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Classroom;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Carbon\Carbon;
// use Kreait\Firebase\Messaging\CloudMessage;
// use Kreait\Firebase\Messaging\Notification;
use App\Notifications\PushNotification;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index()
    {
        // $message = CloudMessage::withTarget('topic', 'pengumuman')
        //     ->withNotification(Notification::create('Pengumuman Baru', 'Ada pengumuman baru di situs web'));

        // $firebase = app('firebase.messaging');
        // $firebase->send($message);

        $user = auth()->user();

        $announcement = Announcement::first();

        $dashboard = (object) [
            'transaction_total' => Transaction::whereDay('created_at', Carbon::now())->sum('total_payment'),
            'service_charge_total' => RechargeHistory::whereDay('created_at', Carbon::now())->sum('service_charge'),
            'student_count' => Student::count(),
            'teacher_count' => Teacher::count(),
            'classroom_count' => Classroom::count(),
        ];


        return view('home', compact('user', 'dashboard', 'announcement'));
    }

    public function announcement()
    {
        $announcement = Announcement::first();

        return view('admin.announcement', compact('announcement'));
    }

    public function updateAnnouncement(Request $request)
    {
        $this->validate($request, [
            'content' => 'required',
        ]);

        Announcement::updateOrCreate(
            [
                'id' => $request->id
            ],
            [
                'content' => $request->content,
            ]
        );

        return redirect()->route('home')->with('success', 'Data pengumuman berhasil diperbarui!');
    }

    public function notification(Request $request){
        $request->validate([
            'title'=>'required',
            'message'=>'required'
        ]);

        try{
            $fcmTokens = User::whereNotNull('fcm_token')->pluck('fcm_token')->toArray();

            //Notification::send(null,new SendPushNotification($request->title,$request->message,$fcmTokens));

            /* or */

            //auth()->user()->notify(new SendPushNotification($title,$message,$fcmTokens));

            /* or */

            Larafirebase::withTitle($request->title)
                ->withBody($request->message)
                ->sendMessage($fcmTokens);

            return redirect()->back()->with('success','Notification Sent Successfully!!');

        }catch(\Exception $e){
            report($e);
            return redirect()->back()->with('error','Something goes wrong while sending notification.');
        }
    }

    public function updateToken(Request $request){
        try{
            $request->user()->update(['fcm_token'=>$request->token]);
            return response()->json([
                'success'=>true
            ]);
        }catch(\Exception $e){
            report($e);
            return response()->json([
                'success'=>false
            ],500);
        }
    }

}

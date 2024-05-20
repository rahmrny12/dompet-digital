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
// use App\Notifications\PushNotification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index()
    {
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

        $user = auth()->user();
        
        $result = Announcement::updateOrCreate(
            [
                'id' => $request->id
            ],
            [
                'content' => $request->content,
            ]
        );

        $url = 'https://fcm.googleapis.com/fcm/send';
        $serverKey = 'key=' . env("FIREBASE_FCM_SERVER_KEY");

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => $serverKey,
            ])->post($url, [
                'to' => '/topics/announcement-all',
                'android' => [
                    'priority' => 'high',
                ],
                'data' => [
                    'title' => 'Dompet Digital Pengumuman',
                    'body' => strip_tags($result->content),
                    'extra' => [
                        'type' => 'announcement',
                        'path' => '/announcement',
                    ],
                ],
            ])->throw();

            if ($response->successful()) {
                return redirect()->route('home')->with('success', 'Data pengumuman berhasil diperbarui!');
            }
        } catch (\Exception $e) {
            Log::error('FCM Notification Error: ' . $e->getMessage());
            return redirect()->route('home')->with('warning', 'Data pengumuman gagal diperbarui!');
        }
    }
}

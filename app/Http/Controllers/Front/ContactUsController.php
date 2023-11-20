<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ContactUsController extends Controller
{

    public function index(Request $request)
    {
        if ($request->method() == 'POST') {
            try {
                $setting = Settings::find(1);
                $mailData = array(
                    'name' => $request->input('name'),
                    'email' => $request->input('email'),
                    'userMessage' => $request->input('message'),
                    'to' => $setting->email,
                );

                Mail::send('front.emails.contact-us', $mailData, function ($message) use ($mailData) {
                    $message->to($mailData['to'])->from($mailData['email'])
                        ->subject('Contact Us - K7Store');
                });

                return redirect()->back()->with(['success' => 'Your Query Submitted.']);
            } catch (\Exception $ex) {
                echo $ex->getMessage();
                exit;
            }
        }
        return view('front.contact-us');
    }
}

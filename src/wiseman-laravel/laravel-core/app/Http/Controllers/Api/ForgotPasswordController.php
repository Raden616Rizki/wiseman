<?php

namespace App\Http\Controllers\Api;

use App\Models\UserModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\UserEmailNotification;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    public function forgotPassword(Request $request)
    {

        $request->validate([
            'email' => 'required|email',
            'link' => 'required|url'
        ]);

        $email = $request->input('email');
        $link = $request->input('link');

        $user = UserModel::where('email', $email)->first();

        if ($user) {
            $linkWithId = $link . '?id=' . $user->id;

            Mail::to($email)
                ->send(new UserEmailNotification([
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'link' => $linkWithId
                ]));

            return response()->success([
                'message' => 'Email berhasil dikirim.',
                'email' => $user->email,
                'link' => $linkWithId
            ]);
        }

        return response()->failed(['message' => 'Email tidak ditemukan.'], 404);
    }
}

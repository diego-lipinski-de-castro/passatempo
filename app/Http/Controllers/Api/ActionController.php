<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\DefaultEmail;

use App\Result;

class ActionController extends Controller
{
    public function doSomething(Request $request)
    {
        $result = Result::findOrFail($request->id);

        collect($result->data['to_low']['data'])->each(function($uid) {
            $user = User::where('id', $uid)->withCount('orders')->first();
            if($user) {
                Mail::to($user->email)->send(new DefaultEmail($user));
            }
        });

        collect($result->data['to_top']['data'])->each(function($uid) {
            $user = User::where('id', $uid)->withCount('orders')->first();
            if($user) {
                Mail::to($user->email)->send(new DefaultEmail($user));
            }
        });

        collect($result->data['same']['all'])->each(function($uid) {
            $user = User::where('id', $uid)->withCount('orders')->first();
            if($user) {
                Mail::to($user->email)->send(new DefaultEmail($user));
            }
        });

        //

        collect($result->data['same']['still_low'])->each(function($uid) {
            $user = User::where('id', $uid)->withCount('orders')->first();
            if($user) {
                Mail::to($user->email)->send(new DefaultEmail($user));
            }
        });

        collect($result->data['same']['still_top'])->each(function($uid) {
            $user = User::where('id', $uid)->withCount('orders')->first();
            if($user) {
                Mail::to($user->email)->send(new DefaultEmail($user));
            }
        });

        collect($result->data['same']['still_middle'])->each(function($uid) {
            $user = User::where('id', $uid)->withCount('orders')->first();
            if($user) {
                Mail::to($user->email)->send(new DefaultEmail($user));
            }
        });

        return response()->json(['done' => true]);
    }
}

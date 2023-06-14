<?php

namespace App\Http\Controllers;

use App\Http\Middleware\Authenticate;
use App\Models\Prize;
use App\Models\PrizeUser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stringable;
use Illuminate\Support\Str;


class PrizeController extends Controller
{
    public function getPrizes()
    {

        $prizes = Prize::all();
        $prizesWithUserData = $prizes->map(function ($prize) {
            $user = Auth::user();
            $pivot = PrizeUser::where('user_id', $user->id)->where('prize_id', $prize->id)->first();
            $prize['redeemed'] = $pivot ? $pivot->redeemed : false;
            $score =  $user->score;

            $prize['valid'] = $prize->redeemed != true ? $score >= $prize->points : false;
            return $prize;
        });
        return $prizes;
    }

    public function getCode($id)
    {

        $currentTime = Carbon::now();
        $expires = $currentTime->addMinutes(2);

        $user = Auth::user();
        $prize = Prize::find($id);

        // $pivot = $user->prizes->wherePivot('prize_id', $prize->id)->first()->pivot;

        $pivot = PrizeUser::where('user_id', $user->id)->where('prize_id', $prize->id)->first();

        $code = Str::random(32) . bin2hex(random_bytes(16));

        if ($pivot) {
            $pivot->hash = $code;
            $pivot->expires = $expires;
            $pivot->save();
        } else {
            $prize->users()->attach($user, [
                'hash' => $code,
                'expires' => $expires,
                'redeemed' => false
            ]);
        }


        return [
            "code" => $code
        ];
    }

    public function storePrize()
    {
        request()->validate([
            'title' => 'required',
            'date' => 'required',
            'place' => 'required',
            'timeframe' => 'required',
            'points' => 'required',
        ]);

        $prize =  new Prize([
            'title' => request('title'),
            'date' => request('date'),
            'place' => request('place'),
            'timeframe' => request('timeframe'),
            'points' => request('points'),
        ]);
        $prize->save();

        return [
            'success' => true,
        ];
    }

    public function checkCode($code)
    {
        $pivot = PrizeUser::where('hash', $code)->first();

        $prize = Prize::find($pivot->prize_id);
        return [
            "prize" => $prize,
            "redeemed" => $pivot->redeemed,
        ];
    }

    public function redeemCode($code)
    {
        // request()->validate([
        //     'code' => 'required',
        // ]);

        $pivot = PrizeUser::where('hash', $code)->first();
        $pivot->redeemed = true;
        $pivot->save();

        $prize = Prize::find($pivot->prize_id);

        return [
            "prize" => $prize,
            "redeemed" => $pivot->redeemed,
        ];
    }
}

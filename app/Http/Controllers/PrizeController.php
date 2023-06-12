<?php

namespace App\Http\Controllers;

use App\Http\Middleware\Authenticate;
use App\Models\Prize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrizeController extends Controller
{
    public function getPrizes()
    {

        $prizes = Prize::all();
        $prizesWithUserData = $prizes->map(function ($prize) {
            $user = Auth::user();
            $score =  $user->score;
            $prize['valid'] = $score > $prize->points;
            $prize['redeemed'] = false;
            return $prize;
        });
        return $prizes;
    }

    public function getCode($id)
    {
        $user = Auth::user();
        $prize = Prize::find($id);

        $prize->users()->attach($user);
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
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VideoData;
use App\Models\AllowedEmail;
use Illuminate\Support\Arr;
use Psy\Readline\Hoa\Console;

class VideoDataController extends Controller
{
    public function check()
    {
        return [
            'success' => true
        ];
    }

    public function getAllowedEmail()
    {
        return AllowedEmail::all();
    }

    public function setAllowedEmail()
    {
        request()->validate([
            'email' => 'required',
        ]);

        AllowedEmail::create([
            'email' => request('email'),

        ]);

        return AllowedEmail::all();
    }

    public function deleteAllowedEmail($id)
    {
        AllowedEmail::destroy($id);
        return AllowedEmail::all();
    }

}

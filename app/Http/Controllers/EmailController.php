<?php

namespace App\Http\Controllers;

use App\Models\AllowedEmail;
use Illuminate\Http\Request;

class EmailController extends Controller
{
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

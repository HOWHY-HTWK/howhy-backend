<?php

namespace App\Http\Controllers;

use App\Models\PrizeMessage;
use Illuminate\Http\Request;

class PrizeMessageController extends Controller
{
    public function getMessage()
    {
        return PrizeMessage::find(1);
    }
    public function setMessage()
    {
        request()->validate([
            'text' => 'required'
        ]);

        $message = PrizeMessage::find(1);
        $message->text = request('text');
        $message->save();

        return PrizeMessage::find(1);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\QuestionUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function storeUserAnswer()
    {
        request()->validate([
            'data' => 'required',
            'question_id' => 'required',
        ]);

        // $questionUser =  new QuestionUser([
        //     'data' => request('data'),
        //     'correctAnswers' => request('data'),
        // ]);

        $question = Question::find(request('question_id'))->first();

        $question->users()->pivot();

        // $questionUser->question()->associate($question);

        // $user = Auth::user();
        // $questionUser->user()->associate($user);

        return [
            'question' => $question,
        ];
    }
}

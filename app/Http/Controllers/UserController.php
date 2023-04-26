<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\QuestionUser;
use App\Models\User;
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

        $question = Question::find(request('question_id'));

        //take a random user for testing
        $user = User::find(12);
        // $user = Auth::user();


        $user->questions()->attach($question->id, ['data' => json_encode(request('data'))]);
        $user->save();

        return [
            'question' => $question,
        ];
    }
}

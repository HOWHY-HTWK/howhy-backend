<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\User;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuestionController extends Controller
{
    public function getById($id)
    {
        $question = Question::find($id);
        $question->makeHidden(['correctAnswers']);
        return $question;
    }

    public function checkAnswers($id)
    {
        $success = false;
        $user = Auth::user();
        request()->validate([
            'answers' => 'required'
        ]);
        $question = Question::find($id);

        if ($question->correctAnswers == request('answers')) {
            $success = true;
            if ($user) {
                $question->users()->attach($user, ['data' => true]);
            }
        }
        return [
            'success' => $success,
        ];
    }

    public function storeQuestion()
    {
        request()->validate([
            'questionId' => 'nullable|integer',
            'videoId' => 'required',
            'questionText' => 'required',
            'timecode' => 'required',
            'type' => 'required',
            'correctAnswers' => 'required',
            'answers' => 'required',
        ]);

        $video = Video::where('videoId', request('videoId'))->first();

        if (!$video) {
            $video = new Video([
                'videoId' => request('videoId'),
                'user_id' => request('creatorId'),
            ]);
            $user = Auth::user();
            $video->user()->associate($user);
            $video->save();
        }

        if (request('questionId')) {
            Question::find(request('questionId'))->delete();
        }

        $question =  new Question([
            'questionText' => request('questionText'),
            'timecode' => request('timecode'),
            'data' => request('data'),
            'type' => request('type'),
            'correctAnswers' => request('correctAnswers'),
            'answers' => request('answers'),
        ]);

        $question->video()->associate($video);
        $question->save();

        return [
            'video' => $video,
            'question' => $question,
        ];
    }

    public function deleteQuestion()
    {
        request()->validate([
            'questionId' => 'required|integer',
        ]);
        Question::find(request('questionId'))->delete();
    }


    //just for changing the table once
    public function fixJsonInTable()
    {
        $questions = Question::all();
        foreach ($questions as $question) {
            $question->answers = json_decode($question->answers);
            $question->save();
        }
    }
}

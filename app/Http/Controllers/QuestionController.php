<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\User;
use App\Models\Video;
use GrahamCampbell\ResultType\Success;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class QuestionController extends Controller
{
    //get question without correct answers so the user cant cheat
    public function getById($id)
    {
        $question = Question::find($id);
        $question->makeHidden(['correctAnswers']);
        return $question;
    }

    //check the users answers, store the answer and increase user score if answer is correct
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
        }

        //store question result if user is logged in
        if ($user) {
            //check if answer is already stored
            $userWithPivot = $question->users->where('id', $user->id)->sortBy('created_at')->last();
            if ($userWithPivot && $userWithPivot->pivot->correct) {
                // do nothing because question has already bin answered correctly before
            } else {
                $question->users()->attach($user, ['correct' => $success]);
                if($success){
                    $user->score = $user->score + 100;
                    if($this->videoCompleteWithoutWrongAnswers($question, $user)){
                        $user->score = $user->score + 100;
                    }
                    $user->save();
                }
            }
        } else {
            // do nothing of no user is logged in
        }

        return [
            'success' => $success,
        ];
    }

    private function videoCompleteWithoutWrongAnswers($question, $user)
    {
        $questions = $question->video->questions;
        foreach ($questions as $element) {
            $userWithPivot = $element->users->find($user->id);
            Log::info($userWithPivot);
            if ($userWithPivot && $userWithPivot->pivot->correct) {
                // dont return
            } else {
                return false;
            }
        }
        return true;
    }

    public function storeQuestion()
    {
        request()->validate([
            'id' => 'nullable|integer',     //null if question is new
            'videoId' => 'nullable|string', //null if question is old
            'video_id' => 'nullable',       //null if question is new
            'questionText' => 'required',
            'timecode' => 'required',
            'type' => 'required',
            'correctAnswers' => 'required',
            'answers' => 'required',
        ]);

        $video = null;

        //find video for old question
        if (request('video_id') != null) {
            $video = Video::find(request('video_id'));
        }

        //find video for new question
        if (request('videoId') != null) {
            $video =  Video::where('videoId', request('videoId'))->first();
        }

        //if video is not yet in the database make a new entry
        if ($video == null) {
            $video = new Video([
                'videoId' => request('videoId'),
                'user_id' => request('creatorId'),
            ]);
            $user = Auth::user();
            $video->user()->associate($user);
            $video->save();
        }

        //if question already exists softdelete to be able to retrieve old data
        if (request('id')) {
            Question::find(request('id'))->delete();
        }

        $question =  new Question([
            'questionText' => request('questionText'),
            'timecode' => request('timecode'),
            'data' => request('answers'),
            'type' => request('type'),
            'correctAnswers' => request('correctAnswers'),
            'answers' => request('answers'),
        ]);

        $question->video()->associate($video);
        $question->save();

        return [
            'success' => true,
        ];
    }

    public function deleteQuestion($id)
    {
        Question::find($id)->delete();
        return [
            'success' => true,
        ];
    }

    //return user score if is logged in. else return zero
    public function score()
    {
        $score = 0;
        $user = Auth::user();
        if ($user) {
            $score = Auth::user()->score;
        }
        return [
            'score' => $score,
        ];
    }
}

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
    public function checkAnswers()
    {
        $success = false;
        $user = Auth::user();
        request()->validate([
            'questionId' => 'required',
            'answers' => 'required'
        ]);
        $question = Question::find(request('questionId'));

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
                if ($success) {
                    $user->score = $user->score + 100;
                    if ($this->videoCompleteWithoutWrongAnswers($question, $user)) {
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

    //user gets 100 bonus points when completing video without errors
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

        $videoId = request('videoId');
        $id = request('id');
        $video = null;

        if (request('video_id') != null) {      //find video for old question
            $video = Video::find(request('video_id'));
        }

        if ($videoId != null) {           //find video for new question
            $video = Video::where('videoId', $videoId)->first();
        }

        if($video == null) {           //if video is not yet in the database make a new entry
            $video = new Video([
                'videoId' => $videoId,
            ]);
            $user = Auth::user();
            $video->user()->associate($user);
            $video->save();
        }

        if ($id) { //if question already exists create a copy of old data and update
            $question = Question::find($id);
            $copy = $question->replicate();
            $copy->parent_id = $question->id;
            $copy->save();
            $copy->delete();

            //update question data
            $question->questionText = request('questionText');
            $question->timecode = request('timecode');
            $question->data = request('answers');
            $question->type = request('type');
            $question->correctAnswers = request('correctAnswers');
            $question->answers = request('answers');
            $question->save();

        } else { //if question is new create a new Question
            $question =  new Question([
                'questionText' => request('questionText'),
                'timecode' => request('timecode'),
                'data' => request('answers'),
                'type' => request('type'),
                'correctAnswers' => request('correctAnswers'),
                'answers' => request('answers'),
            ]);
            $question->video_id = $video->id;
            $question->save();
        }

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

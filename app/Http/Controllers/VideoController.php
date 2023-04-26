<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\User;
use App\Models\Video;
use App\Models\VideoData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VideoController extends Controller
{
    public function index()
    {
        return Video::all();
    }

    public function getById($videoId)
    {
        $response = Video::where('videoId', $videoId)->firstOrFail();
        return $response;
    }

    public function timecodes($videoId)
    {
        $video = Video::where('videoId', $videoId)->firstOrFail();
        $questions = $video->questions()->get();

        $timecodes = [];

        $user = Auth::user();
        if($user){

        }

        foreach ($questions as $question) {
            $correct = null;
            if($question->users->find($user->id)){
                $correct = $question->users->find($user->id)->pivot->correct;
            }
            array_push($timecodes, ['id' => $question->id, 'timecode' => $question->timecode, 'correct' => $correct]);
        }

        return $timecodes;
    }

    public function questions($videoId)
    {
        $video = Video::where('videoId', $videoId)->firstOrFail();
        $questions = $video->questions()->get();

        return $questions;
    }

    public function getUserAnswers($videoId)
    {
        $user = Auth::user();
        $video = Video::where('videoId', $videoId)->firstOrFail();
        $questions = $video->questions();
        $userAnswers = [];

        foreach ($questions as $question) {
            $userAnswer = $question->users()->where('user_id', $user->id)->first()->pivot->data;
            if ($userAnswer) {
                array_push($userAnswers, ['id' => $question->id, 'answer' => $userAnswer]);
            } else {
                array_push($userAnswers, ['id' => $question->id, 'answer' => '']);
            }
        }
    }

    public function transferScript()
    {
        $oldVideos = VideoData::all();

        foreach ($oldVideos as $oldVideo) {
            $video = new Video([
                'videoId' => $oldVideo->videoId,
            ]);

            $user = User::where('id', $oldVideo->creator)->first();
            if($user){
                $video->user()->associate($user);
            } else {
                $video->user()->associate(User::find(1)->first());
            }
            $video->save();

            $oldQuestions = $oldVideo->data;

            foreach ($oldQuestions as $oldQuestion) {
                $question = new Question([
                    'questionText' => $oldQuestion['question'],
                    'type' => $oldQuestion['type'],
                    'data' => json_encode($oldQuestion['answers']),
                    'answers' =>  json_encode($oldQuestion['answers']),
                    'timecode' => $oldQuestion['timecode'],
                    'correctAnswers' => json_encode( $oldQuestion['correctAnswers']),
                ]);

                // $question->video()->save($video);
                $video->questions()->save($question);

                $question->save();
            }
        }
    }
}

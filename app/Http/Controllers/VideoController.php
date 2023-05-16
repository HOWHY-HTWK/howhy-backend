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
        $videos = Video::all();
        $videosWithUserProgress = $videos->map(function ($video) {
            //add user progress to videodata
            $video['success'] = $this->getUserProgress($video);
            return $video;
        });
        return $videosWithUserProgress;
    }

    private function getUserProgress($video)
    {
        $user = Auth::user();
        $questions = $video->questions();
        $correctCount = 0;
        if ($user) {
            $correctCount = Question::whereHas('users', function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->where('correct', true);
            })->where('video_id', $video->id)->get()->count();
        }
        return [
            'questionCount' => $questions->count(),
            'correctCount' => $correctCount,
        ];
    }

    public function getById($videoId)
    {
        $response = Video::where('videoId', $videoId)->firstOrFail();
        return $response;
    }

    /*
    *   returns a list of all timecodes with the question id's and if the user is
    *   logged in the information if the user answered the question correctly in the past is returned
    */
    public function timecodes($videoId)
    {
        $questions = Video::where('videoId', $videoId)->firstOrFail()->questions()->get();
        $user = Auth::user();
        $questionTimecodes = $questions->map(function ($question) use ($user) {
            $correct = null;
            if ($user && $question->users->find($user->id)) {
                $correct = $question->users->find($user->id)->pivot->correct;
            }
            return ['id' => $question->id, 'timecode' => $question->timecode, 'correct' => $correct];
        });
        return $questionTimecodes;
    }

    public function questions($videoId)
    {
        $video = Video::where('videoId', $videoId)->first();

        if($video){
            $questions = $video->questions()->get();
            return $questions;
        } else {
            return [];
        }
    }

    //get all answers for one user for video (currently not used)
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

}

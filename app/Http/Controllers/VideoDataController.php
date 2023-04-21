<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VideoData;
use App\Models\AllowedEmail;
use Illuminate\Support\Arr;
use Psy\Readline\Hoa\Console;

class VideoDataController extends Controller
{
    public function index()
    {
        return VideoData::all();
    }

    public function getVideoList()
    {
        $response = VideoData::all()->pluck('videoId');

        return $response;
    }

    public function showByVideoId($videoId)
    {
        $response = VideoData::where('videoId', $videoId)->firstOrFail();
        //TODO dont comment in production
        // $response->makeHidden(['correctAnswerIndexes']);

        return $response;
    }

    public function check()
    {
        return [
            'success' => true
        ] ;
    }

    public function checkAnswers($videoId)
    {
        request()->validate([
            'questionId' => 'required',
            'answers' => 'required'
        ]);

        $questions = VideoData::where('videoId', $videoId)->first()->data;

        function findObjectById($array, $id)
        {
            foreach ($array as $element) {
                if ($id == $element['id']) {
                    return $element;
                }
            }
            return false;
        }

        $correctAnswers = findObjectById($questions, request('questionId'))['correctAnswers'];

        if ($correctAnswers == request('answers')) {
            $success = true;
        } else {
            $success = false;
        }

        return [
            'success' => $success
        ];
    }



    public function store()
    {
        request()->validate([
            'videoId' => 'required',
            'creator' => 'required',
            'data' => 'present|array',
        ]);

        VideoData::where('videoId', request('videoId'))->delete();

        return VideoData::create([
            'videoId' => request('videoId'),
            'creator' => request('creator'),
            'data' => request('data'),
        ]);
    }

    public function delete(Request $request, $videoId)
    {
        VideoData::where('videoId', $videoId)->delete();

        // $keep = VideoData::where('videoId', $videoId)
        //     ->latest()
        //     ->take(1)
        //     ->pluck('id');

        //     VideoData::where('videoId', $videoId)
        //     ->whereNotIn('id', $keep)
        //     ->delete();

        return 204;
    }

    public function getAllowedEmail(Request $request)
    {
        return AllowedEmail::all();
    }

    public function setAllowedEmail(Request $request)
    {
        request()->validate([
            'email' => 'required',
        ]);

        AllowedEmail::create([
            'email' => request('email'),

        ]);

        return AllowedEmail::all();
    }

    public function deleteAllowedEmail($id){
        AllowedEmail::destroy($id);
        return AllowedEmail::all();
    }


    //TODO not done
    // public function update(VideoData $correctAnswerIndexes)
    // {
    //     request()->validate([
    //         'videoId' => 'required',
    //         'questions' => 'required'
    //     ]);

    //     $success = $correctAnswerIndexes->update([
    //         'videoId' => request('videoId'),
    //         'questions' => request('questions')
    //     ]);

    //     return [
    //         'success' => $success
    //     ];
    // }
}

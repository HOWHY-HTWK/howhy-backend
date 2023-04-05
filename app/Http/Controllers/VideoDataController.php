<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VideoData;
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
            'correctAnswerIndexes' => 'present|array'
        ]);

        VideoData::where('videoId', request('videoId'))->delete();

        return VideoData::create([
            'videoId' => request('videoId'),
            'creator' => request('creator'),
            'data' => request('data'),
            'correctAnswerIndexes' => request('correctAnswerIndexes')
        ]);
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
}

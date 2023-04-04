<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VideoData;

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
        $response = VideoData::where('videoId', $videoId)->get();
        //TODO dont comment in production
        // $response->makeHidden(['correctAnswerIndexes']);

        return $response;
    }

    public function checkAnswers($videoId)
    {
        $correctAnswers = VideoData::where('videoId', $videoId)->correctAnswerIndexes;

        request()->validate([
            'questionIndex' => 'required',
            'answers' => 'required'
        ]);

        $success = false;

        if ($correctAnswers[json_decode(request('questionIndex'))] == request('answers')) {
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

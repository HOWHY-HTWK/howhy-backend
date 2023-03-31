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
 
    public function show($id)
    {
        return VideoData::find($id);
    }

    public function showByVideoId($videoId)
    {
        $response = VideoData::where('videoId', $videoId)->latest()->firstOrFail();
        //TODO dont comment in production
        // $response->makeHidden(['correctAnswerIndexes']);

        return $response;
    }
    public function checkAnswers($videoId)
    {
        $correctAnswers = VideoData::where('videoId', $videoId)->latest()->first()->correctAnswerIndexes;

        request()->validate([
            'questionIndex' => 'required',
            'answers' => 'required'
        ]);

        $success = false;

        if($correctAnswers[json_decode(request('questionIndex'))] == request('answers')){
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

        // if(VideoData::where('videoId', request('videoId'))->first()==null ){
            return VideoData::create([
                'videoId' => request('videoId'),
                'creator' => request('creator'),
                'data' => request('data'),
                'correctAnswerIndexes' => request('correctAnswerIndexes')
            ]);
            
        // } else{
        //     return [
        //         'error' => "videoId already exits"
        //     ];
        // }

    }

    //TODO not done
    public function update(VideoData $correctAnswerIndexes)
    {
        request()->validate([
            'videoId' => 'required', 
            'questions' => 'required'
        ]);

        $success = $correctAnswerIndexes->update([
            'videoId' => request('videoId'),
            'questions' => request('questions') 
        ]);

        return [
            'success' => $success
        ];

    }

    public function delete(Request $request, $videoId)
    {
        VideoData::where('videoId', $videoId)->delete();

        return 204;
    }
}


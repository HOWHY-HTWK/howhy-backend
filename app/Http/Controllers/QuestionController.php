<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Video;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    
    public function storeQuestion()
    {
        request()->validate([
            'videoId' => 'required',
            'creatorId' => 'required',
            'data' => 'present|array',
        ]);

        $video = (Video::where('videoId', request('videoId') )->first());

        if(!$video){
            $video =  Video::create([
                'videoId' => request('videoId'),
                'user_id' => request('creatorId'),
            ]);
        } 

        $question =  new Question([
            'data' => request('data'),
            'correctAnswers' => request('data'),
        ]);

        $question->video()->associate($video);
        $question->save();

        return [
            'video' => $video, 
            'question' => $question,
        ];
    }
}

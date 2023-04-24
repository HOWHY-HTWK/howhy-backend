<?php

use Illuminate\Database\Eloquent\Model;
use App\Models\Question;
use App\Models\User;
use App\Models\Video;
use App\Models\VideoData;

$oldVideos = VideoData::all();

foreach($oldVideos as $oldVideo){
    $video = new Video([
        'videoId' => $oldVideo->videoId,
    ]);
    $video->user()->associate(User::where('id', $oldVideo->creator)->first());
    $video->save();

    $oldQuestions = json_decode($oldVideo->data);

    foreach($oldQuestions as $oldQuestion){
        $question = new Question([
            'questionText' => $oldQuestion['question'],
            'type' => $oldQuestion['type'],
            'data' => $oldQuestion['answers'],
            'correctAnswers' => $oldQuestion['correctAnswers'],
        ]);

        $question->video()->associate($video);

        $question->save();
    }
}
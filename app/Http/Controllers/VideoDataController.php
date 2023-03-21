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

    public function store()
    {
        request()->validate([
            'videoId' => 'required',
            'creator' => 'required',
            'data' => 'required'
        ]);

        return VideoData::create([
            'videoId' => request('videoId'),
            'creator' => request('creator'),
            'data' => request('data')
        ]);
    }

    public function update(VideoData $videoData)
    {
        request()->validate([
            'videoId' => 'required', 
            'questions' => 'required'
        ]);

        $success = $videoData->update([
            'videoId' => request('videoId'),
            'questions' => request('questions') 
        ]);

        return [
            'success' => $success
        ];

    }

    public function delete(Request $request, $id)
    {
        $article = VideoData::findOrFail($id);
        $article->delete();

        return 204;
    }
}


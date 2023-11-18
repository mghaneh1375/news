<?php

namespace App\Http\Controllers;

use App\Models\NewsTags;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class NewsTagController extends Controller
{
    
    public function list() {
        return view('admin.tags', ['tags' => NewsTags::all()]);
    }
    
    public function new() {
        return view('admin.newTag');
    }
    
    public function edit(NewsTags $newsTags) {
        return view('admin.newTag', compact('newsTags'));
    }

    public function store(Request $request) {

        $request->validate([
            'tag' => 'nullable|string|min:2',
            'tagEn' => 'nullable|string|min:2'
        ]);

        NewsTags::create([
            'tag' => $request['tag'],
            'tagEn' => $request['tagEn']
        ]);

        return Redirect::route('tags.list');
    }

    
    public function update(Request $request, NewsTags $newsTags) {

        $request->validate([
            'tag' => 'nullable|string|min:2',
            'tagEn' => 'nullable|string|min:2'
        ]);

        $newsTags->tag = $request['tag'];

        if($request->has('tagEn'))
            $newsTags->tagEn = $request['tagEn'];

        $newsTags->save();

        return Redirect::route('tags.list');
    }

}

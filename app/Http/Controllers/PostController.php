<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends BaseController
{
    public function __construct()
    {
        $this->middleware('permission:post-list|post-create|post-edit|post-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:post-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:post-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:post-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $posts = Post::all();

        $data['posts'] = $posts;
        return $this->sendResponse($data, 'Post information');
    }

    public function create()
    {
        $data = array();
        return $this->sendResponse($data, 'Post information');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'body' => 'required',
        ]);

        try {
            $post = new Post();
            $post->title = $request->title;
            $post->body = $request->body;

            if ($post->save()) {
                $data['post'] = $post;
                return $this->sendResponse($data, 'The post has been saved successfully');
            }
        } catch (\Exception $e) {
            return $this->sendError('Process error', $e->getMessage());
        }
    }

    public function show($id)
    {
        $post = Post::find($id);

        $data['post'] = $post;
        return $this->sendResponse($data, 'Post information');
    }

    public function edit($id)
    {
        $post = Post::find($id);

        $data['post'] = $post;
        return $this->sendResponse($data, 'Post information');
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required',
            'body' => 'required',
        ]);

        try {
            $post = Post::findOrFail($id);
            $post->title = $request->title;
            $post->body = $request->body;

            if ($post->save()) {
                $data['post'] = $post;
                return $this->sendResponse($data, 'The post has been updated successfully');
            }
        } catch (\Exception $e) {
            return $this->sendError('Process error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $post = Post::findOrFail($id);

            if ($post->delete()) {
                $data['post'] = $post;
                return $this->sendResponse($data, 'The post has been deleted successfully');
            }
        } catch (\Exception $e) {
            return $this->sendError('Process error', $e->getMessage());
        }
    }
}

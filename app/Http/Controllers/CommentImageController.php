<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\CommentImage;
use Illuminate\Http\Request;

class CommentImageController extends Controller
{
    public function createImage(Request $request, $id)
    {

        $comment = Comment::find($id);

        if (!$comment) {
            return response()->json([
                'message' => 'Comment not found',
            ], 404);
        }

        if ($comment->user_id != auth()->user()->id) {
            return response()->json([
                'message' => 'You do not have a permission',
            ], 403);
        }

        $request->validate([
            'image' => 'required|string',
        ]);

        $image = $this->saveImage($request->image, 'comments');

        $commentImage = CommentImage::create([
            'image' => $image,
            'comment_id' => $id,
            'user_id' => auth()->user()->id,
        ]);

        return response()->json([
            'commentImage' => $commentImage,
        ], 200);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    // create
    public function createComment(Request $request, $id)
    {

        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'message' => 'Product could be deleted',
            ], 404);
        }

        $attrs = $request->validate([
            'comment' => 'required|string',
            'rating' => 'required|numeric|max:5|min:0',
        ]);

        $comment = Comment::create([
            'user_id' => auth()->user()->id,
            'product_id' => $id,
            'comment' => $attrs['comment'],
            'rating' => $attrs['rating'],
        ]);

        return response()->json([
            'message' => 'Comment created successfully',
            'comment' => $comment,
        ], 200);
    }

    // delete
    public function deleteComment($id)
    {


        $comment = Comment::find($id);

        if ($comment->user_id != auth()->user()->id) {
            return response()->json([
                'message' => 'You are not owner of this comment',
            ], 403);
        }

        if (!$comment) {
            return response()->json([
                'message' => 'Comment not found',
            ], 404);
        }

        // check commentImages if they are also deleting with this command
        $comment->delete();

        return response()->json([
            'message' => 'Comment deleted successfully',
        ]);
    }

    // get my comments
    public function getMyComments()
    {
        $user = User::find(auth()->user()->id);

        $comments = $user->comments()->where('user_id', $user->id)
            ->with('comment_images', function ($commentImages) {
                return $commentImages->orderBy('created_at', 'desc')->get();
            })->get();

        if (!$comments) {
            return response()->json([
                'message' => 'There is no comment to show yet...',
            ], 404);
        }

        return response()->json([
            'comments' => $comments
        ], 200);
    }
}

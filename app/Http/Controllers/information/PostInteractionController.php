<?php

namespace App\Http\Controllers\Information;

use App\Http\Controllers\Controller;
use App\Models\Bookmark;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;

/**
 * いいね・コメント・お気に入り(保存)は Carinderia / Restaurant&Cafe / Travel / Other の
 * どのセクションの投稿でも共通の仕組み(postsテーブルに紐づくだけ)なので、
 * セクションごとのControllerに重複して書かず、ここに1つだけ用意している。
 */
class PostInteractionController extends Controller
{
    /**
     * いいねのトグル(押されていれば取り消し、押されていなければ追加)
     */
    public function toggleLike(Post $post)
    {
        $userId = auth()->id();

        $existing = Like::where('post_id', $post->id)
            ->where('user_id', $userId)
            ->first();

        if ($existing) {
            $existing->delete();
            $liked = false;
        } else {
            Like::create([
                'post_id' => $post->id,
                'user_id' => $userId,
            ]);
            $liked = true;
        }

        return response()->json([
            'liked' => $liked,
            'likes_count' => $post->likes()->count(),
        ]);
    }

    /**
     * コメント投稿
     */
    public function storeComment(Request $request, Post $post)
    {
        $validated = $request->validate([
            'body' => ['required', 'string', 'max:1000'],
        ]);

        $comment = $post->comments()->create([
            'user_id' => auth()->id(),
            'body' => $validated['body'],
        ]);
        $comment->load('user:id,name');

        return response()->json([
            'comment' => [
                'id' => $comment->id,
                'body' => $comment->body,
                'user_name' => $comment->user->name ?? 'ゲスト',
                'created_at' => $comment->created_at,
            ],
            'comments_count' => $post->comments()->count(),
        ]);
    }

    /**
     * 自分のコメントを削除
     */
    public function destroyComment(Comment $comment)
    {
        abort_if($comment->user_id !== auth()->id(), 403, 'このコメントを削除する権限がありません。');

        $post = $comment->post;
        $comment->delete();

        return response()->json([
            'comments_count' => $post->comments()->count(),
        ]);
    }

    /**
     * お気に入り(保存)のトグル
     */
    public function toggleBookmark(Post $post)
    {
        $userId = auth()->id();

        $existing = Bookmark::where('post_id', $post->id)
            ->where('user_id', $userId)
            ->first();

        if ($existing) {
            $existing->delete();
            $bookmarked = false;
        } else {
            Bookmark::create([
                'post_id' => $post->id,
                'user_id' => $userId,
            ]);
            $bookmarked = true;
        }

        return response()->json([
            'bookmarked' => $bookmarked,
        ]);
    }
}

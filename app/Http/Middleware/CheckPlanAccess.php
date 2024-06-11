<?php

namespace App\Http\Middleware;

use App\Models\Post;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPlanAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  $postId
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $postId)
    {
        $user = Auth::user();
        $post = Post::findOrFail($postId);

        // Check if the user has any active subscription that includes the categories of the post
        $userHasAccess = $user->subscriptions()
            ->whereHas('plan.categories', function ($query) use ($post) {
                $query->whereIn('categories.id', $post->categories->pluck('id')->toArray());
            })
            ->active() // Assuming there's a scope to check if the subscription is active
            ->exists();

        if (!$userHasAccess) {
            // User does not have access to this post
            return response()->json(['message' => 'Unauthorized access to this post'], 403);
        }

        return $next($request);
    }
}

<?php

namespace App\Console\Commands;

use App\Models\Post;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AssignPost extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posts:assign';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will run weekly on monday';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today();
        $weekStart = $today->startOfWeek();
        // get active subscription users
        $users = User::userRole()->has('activeSubscription')->with(['activeSubscription.plan.categories'])->get();
      
        foreach ($users as $user) {
            $categoryIds = $user->activeSubscription->plan->categories()->pluck('categories.id')->toArray();
            // Get the most recent post assignment
            $lastAssignedPost = Post::query()->premiumPost()->with(['userPosts'])->whereHas('users', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->whereHas('categories', function ($query) use ($categoryIds) {
                $query->whereIn('categories.id', $categoryIds);
            })->orderBy('id', 'desc')->first();
             
            // Check if there's post already assigned for this week 
            if ($lastAssignedPost && new Carbon($lastAssignedPost->userPosts()->first()->created_at) >= $weekStart) {
                continue; // Skip this user since it has already post assigned
            }

            // Assign the next post
            $lastPostId = $lastAssignedPost ? $lastAssignedPost->id : 0;
            $nextPost = Post::query()->premiumPost()->where('id', '>', $lastPostId)
            ->whereHas('categories', function ($query) use ($categoryIds) {
                $query->whereIn('categories.id', $categoryIds);
            })->first();
            if ($nextPost) {
                $user->posts()->attach($nextPost->id);
                $this->info("Assigned post {$nextPost->id} to user {$user->id}");
            }
        }
    }
}

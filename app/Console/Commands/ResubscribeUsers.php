<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use App\Models\WalletTransaction;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ResubscribeUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'resubscribe:users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Resubscribe users whose plans are expiring';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //Get expiring subscriptions
        $subscriptions = Subscription::whereDate('expires_at', '<=', now())
            ->where('is_active', 1)->with(['user'])
            ->get();
        $cancelledSubscription = 0;
        $reSubscribed = 0;
        foreach ($subscriptions as $subscription) {

            DB::transaction(function () use ($subscription, &$cancelledSubscription, &$reSubscribed) {  // Use & to pass by reference

                $today = Carbon::now();
                $expiresAt = $today->copy()->addDays(30);
                $user = $subscription->user;
                $wallet = $user->wallet;
                $plan = $subscription->plan;
                // check if plan is active if not cancel subscription
                // check if user have enough balance if not cancel subscription
                if(!isset($plan->id) || $plan->status != 1 || 
                (isset($plan->id) && $plan->price >= $wallet->balance)){
                    $subscription->is_active = 0;
                    $subscription->save();
                    $cancelledSubscription ++;
                     
                }else{
                    // deduct plan price form wallet
                    $user->wallet->decrement('balance', $plan->price);
                    // update dates
                    $subscription->started_at  = $today;
                    $subscription->expires_at = $expiresAt;
                    $subscription->save();
                    $currentTimestamp = Carbon::now()->timestamp;
                    // generate transaction id and payment id
                    $transactionId = 'sub_' . $currentTimestamp;
                    $paymentId = 'wal_' . $currentTimestamp + 1;
                    //add transactions
                    $transaction = $user->wallet->transactions()->create([
                        'type' => WalletTransaction::TYPE_DEBIT,
                        'amount' => $plan->price,
                        'status' => WalletTransaction::STATUS_SUCCESS,
                        'description' => "Subscribed " . $plan->name . " Plan",
                        'subscription_id' => $subscription->id,
                        'transaction_id' => $transactionId,
                        'payment_id' => $paymentId,
                    ]);
                    $reSubscribed ++;
                }
            });
        }

        $this->info('Users resubscribed successfully.');
        $this->info('Subscription Statistics:');
        $this->info('------------------------');
        $this->info('Total Renewed Subscription: ' . $reSubscribed);
        $this->info('Total Cancelled Subscribers: ' . $cancelledSubscription);
        Log::info('Subscription Statistics:');
        Log::info('------------------------');
        Log::info('Total Renewed Subscription: ' . $reSubscribed);
        Log::info('Total Cancelled Subscribers: ' . $cancelledSubscription);
    }
}

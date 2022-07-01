<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\Api\Store\EmailController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RemindForReward extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reward:remind';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->email = new EmailController();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $data = DB::select(
                    DB::raw(
                        'SELECT COUNT(promotion_reward.promotion_reward_id) as available_rewards, promotion_reward.promotion_id, promotion_type.name as promotion_type_name, customer.email, product.name as reward_product_name
                        FROM promotion_reward
                            JOIN customer ON promotion_reward.customer_id = customer.customer_id
                            JOIN promotion ON promotion.promotion_id = promotion_reward.promotion_id
                            JOIN promotion_type ON promotion_type.promotion_type_id = promotion.promotion_type_id
                            JOIN product ON promotion_type.reward_product_id = product.product_id
                        WHERE promotion_reward.is_used = 0 AND promotion_reward.created_at < DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 2 WEEK)
                        GROUP BY promotion_reward.promotion_id, customer.email'
                    )
                );

            if ($data) {
                foreach ($data as $reward) {
                    $this->email->remindForReward($reward->email, $reward->promotion_type_name, $reward->available_rewards, $reward->reward_product_name, 0);
                }
            }
        } catch (\Exception $e) {
            Log::channel('cronjob')->error('[reward:remind] '.json_encode($e->getMessage(), true));
        }
    }
}

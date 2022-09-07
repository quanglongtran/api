<?php

namespace App\Jobs;

use App\Services\Notification\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PushNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $serviceMethod;

    protected $methodParams;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($serviceMethod, $methodParams = [[]])
    {
        $this->serviceMethod = $serviceMethod;
        $this->methodParams = $methodParams;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(NotificationService $notificationService)
    {
        call_user_func_array(
            [
                $notificationService,
                $this->serviceMethod,
            ],
            $this->methodParams
        );
    }
}

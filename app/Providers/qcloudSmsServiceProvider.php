<?php

namespace App\Providers;

use Qcloud\Sms\SmsSingleSender;
use Illuminate\Support\ServiceProvider;


class qcloudSmsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->singleton(SmsSingleSender::class, function ($app) {
            return new SmsSingleSender(config('qcloudSms.sdk_app_id'),
                                        config('qcloudSms.app_key'));
            //return  new SmsSingleSender(env('QCLOUD_SDK_APP_ID'), env('QCLOUD_API_KEY'));

        });

        $this->app->alias(SmsSingleSender::class, 'qcloudSms');
    }
}

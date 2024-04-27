<?php

namespace App\Providers;

use App\Interfaces\IMessageQuery;
use App\Interfaces\IAdvancedGroups;
use App\Interfaces\IGroupManagement;
use App\Interfaces\IMessage;
use App\Interfaces\IMqtt;
use App\Interfaces\IRecipient;
use App\Services\AdvancedGroupsServices;
use App\Services\GroupManagementServices;
use App\Services\MessageServices;
use App\Services\Models\MessageQueryServices;
use App\Services\MqttServices;
use App\Services\RecipientServices;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this -> app -> bind(IGroupManagement::class, GroupManagementServices::class);
        $this -> app -> bind(IAdvancedGroups::class, AdvancedGroupsServices::class);
        $this -> app -> bind(IMessage::class,MessageServices::class);
        $this -> app -> bind(IRecipient::class,RecipientServices::class);
        $this -> app -> bind(IMqtt::class,MqttServices::class);
        $this -> app -> bind(IMessageQuery::class,MessageQueryServices::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

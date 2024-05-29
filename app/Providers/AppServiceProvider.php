<?php

namespace App\Providers;

use App\Interfaces\ArchiveGroups\IArchiveGroup;
use App\Interfaces\ArchiveGroups\IGetArchivedGroups;
use App\Interfaces\ArchiveGroups\IUnarchiveGroup;
use App\Interfaces\Exports\IExportParticipants;
use App\Interfaces\Exports\IGroupActivityExport;
use App\Interfaces\Exports\IReportGeneralExport;
use App\Interfaces\Exports\IUserActivity;
use App\Interfaces\IAdvancedGroups;
use App\Interfaces\IGroupManagement;
use App\Interfaces\IGroupRepository;
use App\Interfaces\IMessageReaders;
use App\Interfaces\IMiddlewareUserManagement;
use App\Interfaces\IMqtt;
use App\Interfaces\IRecipient;
use App\Interfaces\ITranformResponses;
use App\Interfaces\IUserManagement;
use App\Interfaces\IUserRepository;
use App\Interfaces\MessagesInterfaces\IMessageQuery;
use App\Interfaces\MessagesInterfaces\IMessageQueryForGroups;
use App\Interfaces\MessagesInterfaces\IMessageQueryForUsers;
use App\Interfaces\MessagesInterfaces\IMessageSender;
use App\Interfaces\Report\IActiveGroupReport;
use App\Interfaces\Report\IGroupReport;
use App\Interfaces\Report\IGroupUserReport;
use App\Interfaces\Report\IUserReport;
use App\Services\AdvancedGroupsServices;
use App\Services\ArchiveGroups\ArchiveGroupService;
use App\Services\ArchiveGroups\GetArchivedGroupsService;
use App\Services\ArchiveGroups\UnarchiveGroupService;
use App\Services\Exports\ExportParticipantsServices;
use App\Services\Exports\GroupActivityExportServices;
use App\Services\Exports\ReportGeneralExportServices;
use App\Services\Exports\UserActivityServices;
use App\Services\GroupManagementServices;
use App\Services\GroupRepositoryService;
use App\Services\MessageReadersService;
use App\Services\MessageServices\MessageQueryForGroupsService;
use App\Services\MessageServices\MessageQueryForUserService;
use App\Services\MessageServices\MessageQueryService;
use App\Services\MessageServices\MessageSenderService;
use App\Services\MiddlewareUserManagementService;
use App\Services\MqttServices;
use App\Services\RecipientServices;
use App\Services\Report\ActiveGroupReportServices;
use App\Services\Report\GroupReportServices;
use App\Services\Report\GroupUserReportService;
use App\Services\Report\UserReportService;
use App\Services\TranformResponsesService;
use App\Services\UserManagementService;
use App\Services\UserRepositoryServices;
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
        $this -> app -> bind(IRecipient::class,RecipientServices::class);
        $this -> app -> bind(IMqtt::class,MqttServices::class);
        $this -> app -> bind(IUserRepository::class,UserRepositoryServices::class);
        $this -> app -> bind(IMessageQueryForGroups::class,MessageQueryForGroupsService::class);
        $this -> app -> bind(IMessageQueryForUsers::class,MessageQueryForUserService::class);
        $this -> app -> bind(IMessageSender::class,MessageSenderService::class);
        $this -> app -> bind(IMessageReaders::class,MessageReadersService::class);
        $this -> app -> bind(ITranformResponses::class,TranformResponsesService::class);
        $this -> app -> bind(IUserManagement::class,UserManagementService::class);
        $this -> app -> bind(IMessageQuery::class,MessageQueryService::class);
        $this-> app -> bind(IGroupRepository::class,GroupRepositoryService::class);
        $this-> app -> bind(IMiddlewareUserManagement::class,MiddlewareUserManagementService::class);
        $this -> app -> bind(IUserReport::class,UserReportService::class);
        $this -> app -> bind(IGroupReport::class,GroupReportServices::class);
        $this -> app -> bind(IGroupUserReport::class,GroupUserReportService::class);
        $this -> app -> bind(IArchiveGroup::class,ArchiveGroupService::class);
        $this -> app -> bind(IGetArchivedGroups::class,GetArchivedGroupsService::class);
        $this -> app -> bind(IUnarchiveGroup::class,UnarchiveGroupService::class);
        $this -> app -> bind(IUserActivity::class,UserActivityServices::class);
        $this -> app -> bind(IActiveGroupReport::class,ActiveGroupReportServices::class);
        $this -> app -> bind(IExportParticipants::class,ExportParticipantsServices::class);
        $this -> app -> bind(IGroupActivityExport::class,GroupActivityExportServices::class);
        $this -> app -> bind(IReportGeneralExport::class,ReportGeneralExportServices::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

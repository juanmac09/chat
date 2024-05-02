<?php

namespace App\Services\MessageServices;

use App\Interfaces\MessagesInterfaces\IMessageQuery;
use App\Interfaces\MessagesInterfaces\IMessageQueryForGroups;
use App\Interfaces\MessagesInterfaces\IMessageQueryForUsers;
use Illuminate\Database\Eloquent\Collection;

class MessageQueryService implements IMessageQuery
{
    public $messageQueryForUsersService;
    public $messageQueryForGroupService;
    public function __construct(IMessageQueryForUsers $messageQueryForUsersService, IMessageQueryForGroups $messageQueryForGroupService)
    {
        $this->messageQueryForUsersService = $messageQueryForUsersService;
        $this->messageQueryForGroupService = $messageQueryForGroupService;
    }
    /**
     * Get the last message between two users.
     *
     * @param Collection $users A collection of users.
     * @param int $user_id The ID of the first user.
     *
     * @return Collection A collection of users with their last messages.
     */
    public function getLastMessageBetweenUsers(Collection $users, int $user_id)
    {
        $usersWithLastMessage = collect();

        foreach ($users as $user) {
            $messages = $this->messageQueryForUsersService->getMessagesBetweenUsers($user_id, $user->id);
            $user->lastMessage = $messages->sortByDesc('created_at')->first();
            $usersWithLastMessage->push($user);
        }

        $sortedUsers = $usersWithLastMessage->sortByDesc(function ($user) {
            return $user->lastMessage ? $user->lastMessage->created_at : '0000-01-01 00:00:00';
        });

        return $sortedUsers;
    }
    /**
     * Get the last message from a group.
     *
     * @param Collection $groups A collection of groups.
     *
     * @return Collection A collection of groups with their last messages.
     */
    public function getLastMessageFromGroup(Collection $groups)
    {
        $groupsWithLastMessage = collect();

        foreach ($groups as $group) {
            $messages = $this->messageQueryForGroupService->getMessagesFromAGroup($group->id);
            $group->lastMessage = $messages->sortByDesc('created_at')->first();
            $groupsWithLastMessage->push($group);
        }
        $sortedGroups = $groupsWithLastMessage->sortByDesc(function ($group) {
            return $group->lastMessage ? $group->lastMessage->created_at : '0000-01-01 00:00:00';
        });

        return $sortedGroups;
    }
}

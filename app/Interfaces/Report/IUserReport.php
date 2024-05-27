<?php

namespace App\Interfaces\Report;


interface IUserReport
{
    public function getGeneralInactiveUsers(int $limitTime);
    public function getSpecificInactiveUsers(int $limitTime, int $recipient_type);
    public function getGeneralActiveUsers(int $limitTime);
    public function getSpecificActiveUsers(int $limitTime, int $recipient_type);
}

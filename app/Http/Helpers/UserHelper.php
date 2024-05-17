<?php

namespace App\Http\Helpers;

use App\Domain\User\User;
use Carbon\Carbon;

class UserHelper
{
    public static function isEligible(User $user, Carbon $date): bool
    {
        $createdAt = Carbon::parse($user->getCreatedAt());
        $months = $createdAt->diffInMonths($date);
    
        return $months > 6 || ($months == 6 && $createdAt->addMonths(6)->lt($date));
    }
}
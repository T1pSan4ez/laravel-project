<?php

namespace App\Interfaces;

use Laravel\Socialite\Contracts\User as SocialiteUser;

interface GoogleAuthRepositoryInterface
{
    public function getRedirectUrl(): string;
    public function authenticateUser(SocialiteUser $googleUser): string;
}

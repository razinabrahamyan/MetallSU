<?php

namespace App\Services\Contracts;

interface ProfileContract
{
    public function getProfile();
    public function getPublicProfile($id);

}

<?php

namespace App\Factory\Profile;

use App\Entity\Profile\Profile;

class ProfileBuilder
{
    public function buildProfile(array $data, Profile $profile = null): Profile
    {
        if (!$profile) {
            $profile = new Profile();
        }
        foreach ($data as $key => $item) {
            if ($key === 'firstName' && isset($item)) {
                $profile->setFirstName($item);
                continue;
            };
            if ($key === 'middleName' && isset($item)) {
                $profile->setMiddleName($item);
                continue;
            };
            if ($key === 'lastName' && isset($item)) {
                $profile->setLastName($item);
                continue;
            };
            if ($key === 'position' && isset($item)) {
                $profile->setPosition($item);
                continue;
            };
            if ($key === 'department' && isset($item)) {
                $profile->setDepartment($item);
                continue;
            };
            if ($key === 'snils' && isset($item)) {
                $profile->setSnils($item);
                continue;
            };
            if ($key === 'diploma' && isset($item)) {
                $profile->setDiploma($item);
                continue;
            };
            if ($key === 'citizenship' && isset($item)) {
                $profile->setCitizenship($item);
                continue;
            };
            if ($key === 'educationLevel' && isset($item)) {
                $profile->setEducationLevel($item);
                continue;
            };
            if ($key === 'phone' && isset($item)) {
                $profile->setPhone($item);
                continue;
            };
            if ($key === 'email' && isset($item)) {
                $profile->setEmail($item);
                continue;
            };
        }

        return $profile;
    }
}
<?php

namespace App\Traits\Validations;

use Propaganistas\LaravelPhone\PhoneNumber;

trait HasUsername
{
    /**
     * Get Phone Number Instance
     * @return PhoneNumber|string
     */
    public function getUsername(): PhoneNumber|string
    {
        return $this->getEmail() ?? $this->getPhone();
    }

    /**
     * Get Email
     * @return null|string
     */
    public function getEmail(): null|string
    {
        if (!filled($this->validated('email'))) {
            return null;
        }

        return $this->validated('email');
    }

    /**
     * Get Phone Number Instance
     * @return PhoneNumber|string|null
     */
    public function getPhone(): PhoneNumber|string|null
    {
        if (!filled($this->validated('phone')) || !filled($this->validated('phone_country'))) {
            return null;
        }

        return phone($this->validated('phone'), $this->validated('phone_country'));
    }
}

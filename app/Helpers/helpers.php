<?php

if (!function_exists('is_email')) {
    function is_email(?string $email): bool
    {
        return (bool)filter_var($email, FILTER_VALIDATE_EMAIL);
    }
}

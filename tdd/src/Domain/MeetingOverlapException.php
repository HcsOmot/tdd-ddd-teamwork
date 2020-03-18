<?php

declare(strict_types=1);

namespace App\Domain;

use Exception;

class MeetingOverlapException extends Exception
{
    /**
     * MeetingOverlapException constructor.
     */
    public function __construct()
    {
    }
}

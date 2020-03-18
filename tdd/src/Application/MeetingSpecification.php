<?php

declare(strict_types=1);

namespace App\Application;
//if (class_exists('Procurios\\Meeting\\Application\\MeetingSpecification')) {
//    var_dump(debug_backtrace());exit;
//}
use App\Domain\Meeting;

//global $counter;
//
//if ($counter === 1) {
//    var_dump(debug_backtrace()[2]);exit;
//} else {
//    $counter = 1;
//}

interface MeetingSpecification
{
    public function isSatisfiedBy(Meeting $meeting): bool;
}

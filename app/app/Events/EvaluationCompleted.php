<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EvaluationCompleted
{
    use Dispatchable, SerializesModels;

    public $user_id;

    public function __construct($user_id)
    {
        $this->user_id = $user_id;
    }
}


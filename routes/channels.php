<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

Broadcast::channel('chat.{id}', function (User $user, $id) {
    return (int) $user->id === (int) $id;
});

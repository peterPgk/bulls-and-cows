<?php

namespace App\Game\Statistics\Contracts;


use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Collection;

interface GameStatisticsInterface
{
    public function general(int $take): Collection;

    public function forUser(Authenticatable $user): Collection;
}

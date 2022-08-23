<?php


namespace App\Game\Statistics;


use App\Models\Game;
use App\Game\Statistics\Contracts\GameStatisticsInterface;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Collection;

class TimeSpentStatistic implements GameStatisticsInterface
{

    public function general(int $take = 10): Collection
    {
        //TODO
        return Collection::make([]);
    }

    public function forUser(Authenticatable $user, int $take = 10): Collection
    {
        //TODO
        return Collection::make([]);
    }
}

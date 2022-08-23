<?php


namespace App\Game\Statistics;


use App\Models\Game;
use App\Game\Statistics\Contracts\GameStatisticsInterface;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class AttemptsStatistic implements GameStatisticsInterface
{

    public static function make(): self
    {
        return new static();
    }

    /**
     * @param int $take
     * @param bool $distinct
     * @return Collection
     */
    public function general(int $take = 10, bool $distinct = false): Collection
    {
        return Game::query()
            ->with('user:id,email')
            ->when(
                $distinct,
                fn($q) => $q->select(DB::raw('*, min(attempts) as attempt'))
                    ->groupBy('user_id')
                    ->orderBy('attempt'),
                fn($q) => $q->orderBy('attempts')
            )
            ->take($take)
            ->get();
    }

    public function forUser(Authenticatable $user, int $take = 10): Collection
    {
        return Game::query()->byUser($user)->orderBy('attempts')->take($take)->get();
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: pgk
 * Date: 23.8.2022 г.
 * Time: 11:53
 */

namespace App\Http\Services;


use App\Game\Generators\Contracts\NumbersGeneratorInterface;
use App\Game\Statistics\Contracts\GameStatisticsInterface;
use Illuminate\Contracts\Auth\Authenticatable;

class GameService
{
    private Authenticatable $forUser;
    private GameStatisticsInterface $statsGenerator;

    public function __construct(Authenticatable $user, GameStatisticsInterface $statsGenerator)
    {
        $this->forUser = $user;
        $this->statsGenerator = $statsGenerator;
    }

    public function generateData()
    {
        $number = app(NumbersGeneratorInterface::class)->generate(config('game.digits'));
//        $statsGenerator = app(GameStatisticsInterface::class);

        $number = '1214';

        $mainStat = $this->statsGenerator->general();
        $userStat = $this->statsGenerator->forUser($this->forUser);

        return [
            'user' => $this->forUser,
            'number' => $number,
            'mainStat' => $mainStat,
            'userStat' => $userStat
        ];
    }
}

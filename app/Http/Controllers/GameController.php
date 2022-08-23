<?php

namespace App\Http\Controllers;

use App\Http\Requests\FinishGameRequest;
use App\Http\Requests\StartGameRequest;
use App\Http\Services\GameService;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class GameController extends Controller
{

    /**
     * @return Application|Factory|View
     */
    public function index()
    {
        $game = Auth::check()
            ? app(GameService::class, ['user' => Auth::user()])->generateData()
            : [];

        return view('main', compact('game'));
    }

    /**
     * @param StartGameRequest $request
     * @return JsonResource
     */
    public function start(StartGameRequest $request)
    {
        $user = User::query()->firstOrCreate(
            $request->validated()
        );

        Auth::login($user);

        //TODO: Extract number creation
        $game = app(GameService::class, ['user' => $user])->generateData();

        return JsonResource::make($game);
    }

    /**
     * @param FinishGameRequest $request
     * @return JsonResource
     */
    public function finish(FinishGameRequest $request)
    {
        $user = tap(
            Auth::user(),
            fn($user) =>  $user->games()->create([
                'attempts' => count($request->get('attempts'))
            ])
        );

        $game = app(GameService::class, ['user' => $user])->generateData();

        return JsonResource::make($game);
    }

    /**
     * @return Application|Factory|View
     */
    public function logout()
    {
        Auth::logout();

        return view('main', ['game' => []]);
    }
}

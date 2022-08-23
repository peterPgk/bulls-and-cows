<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Nuvei') }}</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script>
        let startedGameData = {{ Js::from($game) }};
    </script>
</head>
<body>
<div class="font-sans text-gray-900 antialiased" x-data="game">
    <x-card>

        <x-slot name="logo">
            <div class="relative">
                <a href="/">
                    <x-application-logo class="w-20 h-20 fill-current text-gray-500"/>
                </a>
                <h4 class="absolute left-0 right-0 top-3/4 w-fill text-center text-2xl text-gray-500 -rotate-3">Bulls and cows GAME</h4>
            </div>
        </x-slot>

        <x-slot name="login">

            <x-label for="email" :value="__('Please, fill your email to start playing the game!')" />
            <div class="relative">
                <input
                    class="block p-3 w-full text-xl text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:outline-none focus:ring-gray-700 focus:ring-gray-700 focus:border-gray-700"
                    type="text"
                    id="email"
                    x-model="userEmail"
                    @keyup.enter="startGame()"
                    required
                    autofocus
                >
                <x-button class="absolute right-2.5 bottom-2.5" @click="startGame">
                    {{ __('Start') }}
                </x-button>
            </div>
        </x-slot>

        <div>
            <main class="relative grid grid-cols-9 gap-3 pb-4 pt-3 mb-4 border-b">
                <h2 class="col-span-3 text-xl"><span x-text="user.email" class="font-bold"></span>'s GAME</h2>
                <div class="col-span-5" x-show="state==='complete'">
                    <h1 class="font-bold text-xl text-green-600">Congratulations, you won! </h1>
                    <x-button class="absolute right-20 bottom-2.5" @click="startGame" x-bind:disabled="state!=='complete'">
                        {{ __('Start new game') }}
                    </x-button>
                </div>
                <x-button class="absolute right-2.5 bottom-2.5" @click="logout()">
                    {{ __('Exit') }}
                </x-button>
            </main>

            <section class="grid grid-cols-5 gap-8">
                <div class="col-span-2">

                    <x-label for="guess" :value="__('Hit your best guess!')" />

                    <div class="relative">
                        <input
                            class="block p-3 w-full text-xl text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:outline-none focus:ring-gray-700 focus:ring-gray-700 focus:border-gray-700"
                            type="text"
                            id="guess"
                            x-model="currentGuess"
                            @keypress.prevent="validateInput($event.key)"
                            required
                            autofocus
                        >
                        <x-button class="absolute right-2.5 bottom-2.5" @click="guess()" x-bind:disabled="state==='complete'">
                            {{ __('Try It...') }}
                        </x-button>
                    </div>

                    <div class="p-4 mt-5 border rounded-lg">
                        <h3 class="p-1 mb-2 text-xs text-green-600 border border-green-500 rounded-lg">
                            <span class="font-bold">Bull</span> ( Catch!!! Right on place!!! )
                        </h3>
                        <h3 class="p-1 text-xs text-red-600 border border-red-500 rounded-lg">
                            <span class="font-bold">Cow</span> ( Almost did it! Just think for a place... )
                        </h3>
                    </div>

                    <section id="hits" class="pl-4">
                        <h3 class="font-bold pt-6">Hits</h3>
                        <template x-for="attempt in attempts">
                            <div class="row">
                                <template x-for="sign in attempt"
                                          class="mt-2 flex gap-4 text-white text-sm font-bold font-mono leading-6 bg-stripes-pink rounded-lg">
                                <span
                                    class="text-xl mr-2 shadow-xl text-center"
                                    :class="sign.status" x-text="sign.char"
                                ></span>
                                </template>
                            </div>
                        </template>
                    </section>
                </div>

                <div class="col-span-3">
                    <h3 class="font-bold py-6">Best players for all time !!</h3>
                    <section>
                        <template x-if="hasMainStat">
                            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 uppercase dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="py-2 px-3">Who</th>
                                    <th scope="col" class="py-2 px-3 bg-gray-50 dark:bg-gray-800">Attempts</th>
                                    <th scope="col" class="py-2 px-3">When</th>
                                </tr>
                                </thead>
                                <tbody>
                                <template x-for="gamer in data.mainStat">
                                    <tr
                                        class="border-b border-gray-200 dark:border-gray-700"
                                        :class="{'bg-gray-50 dark:bg-gray-800 font-bold' : (gamer.user.email === user.email)}"
                                    >
                                        <td class="py-2 px-3" x-text="gamer.user.email"></td>
                                        <td class="py-2 px-3 bg-gray-50 dark:bg-gray-800" x-text="gamer.attempts"></td>
                                        <td class="py-2 px-3" x-text="gamer.created_at"></td>
                                    </tr>
                                </template>
                                </tbody>
                            </table>
                        </template>
                        <template x-if="!hasMainStat">
                            <div>There is no attempts yet :(</div>
                        </template>
                    </section>

                    <h3 class="font-bold py-6">... and my best ones !!</h3>
                    <section>
                        <template x-if="hasUserStat">
                            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 uppercase dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="py-2 px-3">Attempts</th>
                                    <th scope="col" class="py-2 px-3 bg-gray-50 dark:bg-gray-800">When</th>
                                </tr>
                                </thead>
                                <tbody>
                                <template x-for="me in data.userStat">
                                    <tr class="border-b border-gray-200 dark:border-gray-700">
                                        <td class="py-2 px-3" x-text="me.attempts"></td>
                                        <td class="py-2 px-3 bg-gray-50 dark:bg-gray-800" x-text="me.created_at"></td>
                                    </tr>
                                </template>
                                </tbody>
                            </table>
                        </template>
                        <template x-if="! hasUserStat">
                            <h2>It's your first try! Good luck :)</h2>
                        </template>
                    </section>
                </div>
            </section>
        </div>

    </x-card>

</div>
</body>
</html>

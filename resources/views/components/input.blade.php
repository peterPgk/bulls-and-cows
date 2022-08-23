@props(['disabled' => false])

{{--<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50']) !!}>--}}

<label for="guess"
       class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-gray-300">Try It...</label>
<div class="relative">
    <input
        class="block p-4 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:outline-none focus:ring-gray-700 focus:ring-gray-700 focus:border-gray-700"
        type="text"
        id="guess"
        x-model="currentGuess"
        @keypress.prevent="validateInput($event.key)"
        required
        autofocus
    >
    <x-button class="absolute right-2.5 bottom-2.5" @click="guess()">
        {{ __('Try It...') }}
    </x-button>
    {{--                        <button--}}
    {{--                            class="text-white absolute right-2.5 bottom-2.5 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"--}}

    {{--                         focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500--}}

    {{--                        >Try It...</button>--}}
</div>

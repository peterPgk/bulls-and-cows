<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
    <div>
        {{ $logo }}
    </div>

    <!-- Validation Errors -->
    {{--        <x-auth-validation-errors class="mb-4" :errors="$errors" />--}}

    <div class="w-full sm:max-w-4xl mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
        <div x-show="! hasUser">
            {{ $login }}
        </div>

        <div x-show="hasUser">
            {{ $slot }}
        </div>
    </div>
</div>

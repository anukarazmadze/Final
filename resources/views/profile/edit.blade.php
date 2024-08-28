<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

            @if(Auth::check() && Auth::user()->email === 'company@company.com')
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <form method="POST" action="{{ route('partner.generate-token') }}">
                        @csrf
                        <input type="hidden" name="email" value="{{ Auth::user()->email }}">
                        <input type="password" name="password" placeholder="Enter your password" required>
                        <button type="submit" class="x-secondary-button">Generate API Token</button>
                    </form>

                    @if(session('token'))
                    <div class="mt-4">
                        <strong>Your API Token:</strong>
                        <p>{{ session('token') }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

        </div>




    </div>
    </div>
</x-app-layout>

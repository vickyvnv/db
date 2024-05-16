<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- First Name -->
        <div>
            <x-input-label for="user_firstname" :value="__('First Name')" />
            <x-text-input id="user_firstname" class="block mt-1 w-full" type="text" name="user_firstname" :value="old('user_firstname')" required autofocus />
            <x-input-error :messages="$errors->get('user_firstname')" class="mt-2" />
        </div>

        <!-- Last Name -->
        <div class="mt-4">
            <x-input-label for="user_lastname" :value="__('Last Name')" />
            <x-text-input id="user_lastname" class="block mt-1 w-full" type="text" name="user_lastname" :value="old('user_lastname')" required />
            <x-input-error :messages="$errors->get('user_lastname')" class="mt-2" />
        </div>

        <!-- Telephone -->
        <div class="mt-4">
            <x-input-label for="tel" :value="__('Telephone')" />
            <x-text-input id="tel" class="block mt-1 w-full" type="tel" name="tel" :value="old('tel')" required />
            <x-input-error :messages="$errors->get('tel')" class="mt-2" />
        </div>

        <!-- User Function -->
        <div class="mt-4">
            <x-input-label for="user_function" :value="__('User Function')" />
            <x-text-input id="user_function" class="block mt-1 w-full" type="text" name="user_function" :value="old('user_function')" required />
            <x-input-error :messages="$errors->get('user_function')" class="mt-2" />
        </div>

        <!-- User Contractor -->
        <div class="mt-4">
            <x-input-label for="user_contractor" :value="__('User Contractor')" />
            <x-text-input id="user_contractor" class="block mt-1 w-full" type="text" name="user_contractor" :value="old('user_contractor')" required />
            <x-input-error :messages="$errors->get('user_contractor')" class="mt-2" />
        </div>

        <!-- Team ID -->
        <div class="mt-4">
            <x-input-label for="team_id" :value="__('Team ID')" />
            <x-text-input id="team_id" class="block mt-1 w-full" type="text" name="team_id" :value="old('team_id')" required />
            <x-input-error :messages="$errors->get('team_id')" class="mt-2" />
        </div>

        <!-- Team Name -->
        <div class="mt-4">
            <x-input-label for="team_name" :value="__('Team Name')" />
            <x-text-input id="team_name" class="block mt-1 w-full" type="text" name="team_name" :value="old('team_name')" required />
            <x-input-error :messages="$errors->get('team_name')" class="mt-2" />
        </div>

        <!-- Team Group -->
        <div class="mt-4">
            <x-input-label for="team_group" :value="__('Team Group')" />
            <x-text-input id="team_group" class="block mt-1 w-full" type="text" name="team_group" :value="old('team_group')" required />
            <x-input-error :messages="$errors->get('team_group')" class="mt-2" />
        </div>

        <!-- User Department -->
        <div class="mt-4">
            <x-input-label for="user_department" :value="__('User Department')" />
            <x-text-input id="user_department" class="block mt-1 w-full" type="text" name="user_department" :value="old('user_department')" required />
            <x-input-error :messages="$errors->get('user_department')" class="mt-2" />
        </div>

        <!-- TL -->
        <div class="mt-4">
            <x-input-label for="tl" :value="__('TL')" />
            <x-text-input id="tl" class="block mt-1 w-full" type="text" name="tl" :value="old('tl')" required />
            <x-input-error :messages="$errors->get('tl')" class="mt-2" />
        </div>

        <!-- GL -->
        <div class="mt-4">
            <x-input-label for="gl" :value="__('GL')" />
            <x-text-input id="gl" class="block mt-1 w-full" type="text" name="gl" :value="old('gl')" required />
            <x-input-error :messages="$errors->get('gl')" class="mt-2" />
        </div>

        <!-- AL -->
        <div class="mt-4">
            <x-input-label for="al" :value="__('AL')" />
            <x-text-input id="al" class="block mt-1 w-full" type="text" name="al" :value="old('al')" required />
            <x-input-error :messages="$errors->get('al')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ml-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>

<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Company Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your company's profile information and address.") }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.company.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="company_name" :value="__('Company Name')" />
            <x-text-input id="company_name" name="company_name" type="text" class="mt-1 block w-full" :value="old('company_name', $user->company->name ?? '')" required autofocus autocomplete="organization" />
            <x-input-error class="mt-2" :messages="$errors->get('company_name')" />
        </div>

        <div>
            <x-input-label for="company_email" :value="__('Company Email')" />
            <x-text-input id="company_email" name="company_email" type="email" class="mt-1 block w-full" :value="old('company_email', $user->company->email ?? '')" autocomplete="email" />
            <x-input-error class="mt-2" :messages="$errors->get('company_email')" />
        </div>

        <div>
            <x-input-label for="company_phone" :value="__('Phone Number')" />
            <x-text-input id="company_phone" name="company_phone" type="text" class="mt-1 block w-full" :value="old('company_phone', $user->company->phone ?? '')" autocomplete="tel" />
            <x-input-error class="mt-2" :messages="$errors->get('company_phone')" />
        </div>

        <div>
            <x-input-label for="company_address" :value="__('Address')" />
            <textarea id="company_address" name="company_address" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full" rows="3">{{ old('company_address', $user->company->address ?? '') }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('company_address')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'company-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>

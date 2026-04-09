<?php

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use App\Models\User;
use Illuminate\Auth\Events\Registered;

new class extends Component
{
    public bool $isRegistering = false;

    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function toggleMode()
    {
        $this->isRegistering = !$this->isRegistering;
        $this->resetValidation();
        $this->password = '';
        $this->password_confirmation = '';
    }

    public function submit()
    {
        if ($this->isRegistering) {
            $this->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
            ]);

            event(new Registered($user));
            Auth::login($user);
            return redirect()->intended(route('posts.index', absolute: false));
        } else {
            $this->validate([
                'email' => ['required', 'email'],
                'password' => ['required'],
            ]);

            if (Auth::attempt(['email' => $this->email, 'password' => $this->password], true)) {
                session()->regenerate();
                return redirect()->intended(route('posts.index', absolute: false));
            }

            $this->addError('email', trans('auth.failed'));
        }
    }
};
?>

<div class="max-w-md mx-auto bg-white p-8 rounded-lg shadow-md mt-10">
    <h2 class="text-2xl font-bold text-center mb-6">
        {{ $isRegistering ? 'Utwórz konto' : 'Zaloguj się' }}
    </h2>

    <form wire:submit="submit" class="space-y-4">
        @if($isRegistering)
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Imię</label>
                <input wire:model="name" id="name" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                @error('name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>
        @endif

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input wire:model="email" id="email" type="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
            @error('email') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">Hasło</label>
            <input wire:model="password" id="password" type="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
            @error('password') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        @if($isRegistering)
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Potwierdź hasło</label>
                <input wire:model="password_confirmation" id="password_confirmation" type="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                @error('password_confirmation') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>
        @endif

        <div class="flex items-center justify-between pt-4">
            <button type="submit" class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 font-medium">
                {{ $isRegistering ? 'Zarejestruj się' : 'Zaloguj się' }}
            </button>
        </div>

        <div class="text-center mt-4 text-sm text-gray-600">
            @if($isRegistering)
                Masz już konto? 
                <button type="button" wire:click="toggleMode" class="text-indigo-600 font-medium hover:text-indigo-500">Zaloguj się</button>
            @else
                Nie masz konta? 
                <button type="button" wire:click="toggleMode" class="text-indigo-600 font-medium hover:text-indigo-500">Zarejestruj się</button>
            @endif
        </div>
    </form>
</div>

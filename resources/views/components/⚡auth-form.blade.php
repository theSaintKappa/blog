<?php

use App\Enums\Role;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Component;

new class extends Component
{
    public bool $isRegistering = false;

    public string $name = '';

    public string $username = '';

    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    public function toggleMode()
    {
        $this->isRegistering = ! $this->isRegistering;
        $this->resetValidation();
        $this->password = '';
        $this->password_confirmation = '';
    }

    public function submit()
    {
        if ($this->isRegistering) {
            $this->validate([
                'name' => ['required', 'string', 'max:255'],
                'username' => ['required', 'string', 'max:255', 'unique:'.User::class],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            $user = User::create([
                'name' => $this->name,
                'username' => $this->username,
                'role' => Role::User,
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

<div class="max-w-md mx-auto relative p-8 md:p-10">
    <div class="absolute inset-0 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-3xl shadow-2xl -z-10"></div>
    
    <h2 class="text-3xl font-extrabold text-center mb-8 text-zinc-900 dark:text-zinc-50 tracking-tight">
        {{ $isRegistering ? 'Utwórz konto' : 'Witamy ponownie' }}
    </h2>

    <form wire:submit="submit" class="space-y-5">
        @if($isRegistering)
            <div>
                <label for="name" class="block text-xs font-bold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider mb-2">Imię</label>
                <input wire:model="name" id="name" type="text" class="w-full px-5 py-4 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 text-zinc-900 dark:text-zinc-50 rounded-2xl focus:ring-2 focus:ring-zinc-900 dark:focus:ring-zinc-100 outline-none transition-all placeholder:text-zinc-400" />
                @error('name') <span class="text-red-500 text-sm mt-2 font-medium block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="username" class="block text-xs font-bold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider mb-2">Nazwa użytkownika</label>
                <input wire:model="username" id="username" type="text" class="w-full px-5 py-4 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 text-zinc-900 dark:text-zinc-50 rounded-2xl focus:ring-2 focus:ring-zinc-900 dark:focus:ring-zinc-100 outline-none transition-all placeholder:text-zinc-400" />
                @error('username') <span class="text-red-500 text-sm mt-2 font-medium block">{{ $message }}</span> @enderror
            </div>
        @endif

        <div>
            <label for="email" class="block text-xs font-bold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider mb-2">Email</label>
            <input wire:model="email" id="email" type="email" class="w-full px-5 py-4 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 text-zinc-900 dark:text-zinc-50 rounded-2xl focus:ring-2 focus:ring-zinc-900 dark:focus:ring-zinc-100 outline-none transition-all placeholder:text-zinc-400" />
            @error('email') <span class="text-red-500 text-sm mt-2 font-medium block">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="password" class="block text-xs font-bold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider mb-2">Hasło</label>
            <input wire:model="password" id="password" type="password" class="w-full px-5 py-4 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 text-zinc-900 dark:text-zinc-50 rounded-2xl focus:ring-2 focus:ring-zinc-900 dark:focus:ring-zinc-100 outline-none transition-all placeholder:text-zinc-400" />
            @error('password') <span class="text-red-500 text-sm mt-2 font-medium block">{{ $message }}</span> @enderror
        </div>

        @if($isRegistering)
            <div>
                <label for="password_confirmation" class="block text-xs font-bold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider mb-2">Potwierdź hasło</label>
                <input wire:model="password_confirmation" id="password_confirmation" type="password" class="w-full px-5 py-4 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 text-zinc-900 dark:text-zinc-50 rounded-2xl focus:ring-2 focus:ring-zinc-900 dark:focus:ring-zinc-100 outline-none transition-all placeholder:text-zinc-400" />
                @error('password_confirmation') <span class="text-red-500 text-sm mt-2 font-medium block">{{ $message }}</span> @enderror
            </div>
        @endif

        <div class="pt-6">
            <button type="submit" class="w-full relative group overflow-hidden bg-zinc-900 dark:bg-zinc-50 text-white dark:text-zinc-900 py-4 px-6 rounded-2xl font-bold hover:bg-zinc-800 dark:hover:bg-zinc-200 transition-colors shadow-md">
                <span class="relative z-10 w-full flex items-center justify-center gap-2">
                    {{ $isRegistering ? 'Zarejestruj się' : 'Zaloguj się' }}
                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </span>
            </button>
        </div>

        <div class="text-center pt-6 mt-6 border-t border-zinc-100 dark:border-zinc-800 flex flex-col gap-3">
            <div class="text-sm text-zinc-500 dark:text-zinc-400 font-medium">
                @if($isRegistering)
                    Masz już konto? 
                    <button type="button" wire:click="toggleMode" class="text-zinc-900 dark:text-zinc-50 font-bold hover:underline decoration-2 underline-offset-4 ml-1">
                        Zaloguj się
                    </button>
                @else
                    Nie masz konta? 
                    <button type="button" wire:click="toggleMode" class="text-zinc-900 dark:text-zinc-50 font-bold hover:underline decoration-2 underline-offset-4 ml-1">
                        Utwórz je teraz
                    </button>
                @endif
            </div>
        </div>
    </form>
</div>
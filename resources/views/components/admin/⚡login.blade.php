<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Livewire\Component;

new class extends Component {
    public string $email = '';
    public string $password = '';
    public bool $remember = false;
    public string $errorMessage = '';

    public function login(): void
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $throttleKey = Str::lower($this->email).'|'.request()->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $this->errorMessage = "Trop de tentatives. Reessayez dans {$seconds} secondes.";
            return;
        }

        if (! Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            RateLimiter::hit($throttleKey, 60);
            $this->errorMessage = 'Identifiants incorrects.';
            $this->password = '';
            return;
        }

        RateLimiter::clear($throttleKey);
        request()->session()->regenerate();

        $this->redirect(route('admin.landmarks'), navigate: true);
    }
}; ?>

<div class="min-h-screen flex items-center justify-center px-4" style="background: var(--ink-950);">
    <form wire:submit="login" class="w-full max-w-sm rounded-2xl border border-[var(--ink-800)] bg-[var(--ink-900)] p-8 flex flex-col gap-4">
        <div class="text-center mb-2">
            <span class="font-mono-label text-xs text-[var(--gold)]">CFLS — ADMINISTRATION</span>
            <h1 class="font-display text-2xl text-[var(--paper)] mt-1">Connexion</h1>
        </div>

        @if ($errorMessage)
            <div class="text-sm px-3 py-2 rounded-lg" style="color:#C2477E; background:#C2477E14; border:1px solid #C2477E;">
                {{ $errorMessage }}
            </div>
        @endif

        <label class="flex flex-col gap-1">
            <span class="font-mono-label text-[10px] text-[var(--gold)] uppercase">Email</span>
            <input type="email" wire:model="email" autofocus class="admin-input" autocomplete="username">
            @error('email') <span class="text-xs" style="color:#C2477E">{{ $message }}</span> @enderror
        </label>

        <label class="flex flex-col gap-1">
            <span class="font-mono-label text-[10px] text-[var(--gold)] uppercase">Mot de passe</span>
            <input type="password" wire:model="password" class="admin-input" autocomplete="current-password">
            @error('password') <span class="text-xs" style="color:#C2477E">{{ $message }}</span> @enderror
        </label>

        <label class="flex items-center gap-2 text-sm text-[var(--paper-muted)]">
            <input type="checkbox" wire:model="remember">
            Se souvenir de moi
        </label>

        <button
                type="submit"
                class="mt-2 font-mono-label text-[11px] px-4 py-2.5 rounded-full bg-[var(--gold)] text-white hover:opacity-90 transition-opacity"
                wire:loading.attr="disabled" wire:target="login"
        >
            <span wire:loading.remove wire:target="login">SE CONNECTER</span>
            <span wire:loading wire:target="login">CONNEXION…</span>
        </button>
    </form>
</div>
<?php

namespace App\Filament\Employee\Pages;

use App\Models\Employee;
use App\Models\User;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Filament\Actions\Action;
use Filament\Auth\Http\Responses\Contracts\LoginResponse;
use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Pages\SimplePage;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Locked;

class Login extends SimplePage
{
    use WithRateLimiting;

    public ?array $data = [];

    #[Locked]
    public bool $showPasswordForm = false;

    #[Locked]
    public ?string $employeeId = null;

    public function mount(): void
    {
        if (Filament::auth()->check()) {
            redirect()->intended(Filament::getUrl());
        }

        $this->form->fill();
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                Form::make([EmbeddedSchema::make('form')])
                    ->id('form')
                    ->livewireSubmitHandler('authenticate')
                    ->footer([
                        Actions::make([
                            Action::make('authenticate')
                                ->label($this->showPasswordForm ? 'Set Password' : 'Sign in')
                                ->submit('authenticate'),
                        ])
                            ->fullWidth(),
                    ]),
            ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->schema([
                $this->getFormComponent(),
            ]);
    }

    private function getFormComponent(): Component
    {
        if ($this->showPasswordForm) {
            return $this->getPasswordFormComponent();
        }

        return $this->getCredentialsFormComponent();
    }

    private function getCredentialsFormComponent(): Component
    {
        return Section::make()
            ->schema([
                TextInput::make('personalId')
                    ->label('Personal ID')
                    ->required()
                    ->autofocus()
                    ->autocomplete(false),
                TextInput::make('internalId')
                    ->label('Internal Employee ID')
                    ->required()
                    ->autocomplete(false),
                TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->autocomplete('current-password')
                    ->visible(fn (): bool => ! $this->showPasswordForm),
            ])
            ->columns(1);
    }

    private function getPasswordFormComponent(): Component
    {
        return Section::make('Set Your Password')
            ->description('Please create a password for your account.')
            ->schema([
                TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->required()
                    ->autofocus()
                    ->minLength(8)
                    ->same('password_confirmation'),
                TextInput::make('password_confirmation')
                    ->label('Confirm Password')
                    ->password()
                    ->required(),
            ])
            ->columns(1);
    }

    public function authenticate(): ?LoginResponse
    {
        try {
            $this->rateLimit(5);
        } catch (TooManyRequestsException $exception) {
            throw ValidationException::withMessages([
                'data.personalId' => __('filament-panels::pages/auth/login.messages.throttled', [
                    'seconds' => $exception->secondsUntilAvailable,
                    'minutes' => ceil($exception->secondsUntilAvailable / 60),
                ]),
            ]);
        }

        if ($this->showPasswordForm) {
            return $this->createPassword();
        }

        return $this->validateEmployeeCredentials();
    }

    private function validateEmployeeCredentials(): ?LoginResponse
    {
        $data = $this->form->getState();

        $employee = Employee::authenticatable()
            ->where('personal_id', $data['personalId'] ?? null)
            ->where('internal_id', $data['internalId'] ?? null)
            ->first();

        if (! $employee) {
            throw ValidationException::withMessages([
                'data.personalId' => __('filament-panels::pages/auth/login.messages.failed'),
            ]);
        }

        $user = $employee->user;

        // User exists and has set password - verify it
        if ($user && $user->password_set_at) {
            if (! Hash::check($data['password'] ?? '', $user->password)) {
                throw ValidationException::withMessages([
                    'data.password' => __('filament-panels::pages/auth/login.messages.failed'),
                ]);
            }

            return $this->loginUser($user);
        }

        // First time login or password reset - show password form
        $this->employeeId = $employee->id;
        $this->showPasswordForm = true;

        return null;
    }

    private function createPassword(): ?LoginResponse
    {
        $data = $this->form->getState();

        if (! $this->employeeId) {
            throw ValidationException::withMessages([
                'data.password' => 'Invalid state. Please start over.',
            ]);
        }

        $employee = Employee::with('user')->find($this->employeeId);

        if (! $employee) {
            throw ValidationException::withMessages([
                'data.password' => 'Employee not found.',
            ]);
        }

        // Find or create user - check by employee relationship first, then by email, then create new
        $user = $employee->user
            ?? User::where('email', $employee->email)->first()
            ?? new User;

        $user->fill([
            'email' => $employee->email ?? "employee_{$employee->id}@system.local",
            'name' => $employee->full_name,
            'password' => Hash::make($data['password']),
            'password_set_at' => now(),
            'force_password_change' => false,
            'is_active' => true,
            'employee_id' => $employee->id,
        ])->save();

        return $this->loginUser($user);
    }

    private function loginUser(Authenticatable $user): LoginResponse
    {
        Filament::auth()->login($user, remember: false);

        session()->regenerate();

        return app(LoginResponse::class);
    }
}

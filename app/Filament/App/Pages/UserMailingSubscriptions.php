<?php

namespace App\Filament\App\Pages;

use Filament\Forms\Form;
use Filament\Pages\Page;
use App\Services\MailingService;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Actions\Concerns\InteractsWithActions;

class UserMailingSubscriptions extends Page implements HasForms, HasActions
{
    use InteractsWithForms;
    use InteractsWithActions;

    public ?array $data = [];

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static bool $shouldRegisterNavigation = false;

    protected static string $view = 'filament.app.pages.user-mailing-subscriptions';

    protected ?string $subheading = 'Here you can opt in to receive specific emails when certain events hapens.';
    /**
     * @param  array<mixed>  $parameters
     */

    public function mount()
    {
        $this->form->fill(['mailables' => auth()->user()->mailingSubscriptions->pluck('mailable')->toArray()]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('')
                    ->schema([
                        CheckboxList::make('mailables')
                            ->options(MailingService::toArray())
                            ->searchable()
                            ->columns(2)
                            ->bulkToggleable(),
                        // ...
                    ])
            ])
            ->statePath('data');
    }

    public function syncMailables(): void
    {
        auth()->user()
            ->mailingSubscriptions()
            ->forceDelete();

        $inserts = array_map(function ($element) {
            return ['mailable' => $element];
        }, array_unique($this->form->getState()['mailables']));

        auth()->user()
            ->mailingSubscriptions()
            ->createMany($inserts);

        Notification::make()
            ->title('Saved successfully')
            ->success()
            ->send();
    }

    public function getFormActions()
    {
        return [
            Action::make('save')
                ->requiresConfirmation()
                ->submit('syncMailables'),
        ];
    }
}

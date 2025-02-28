<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;
use Filament\Tables\Table;
use Filament\Support\Colors\Color;
use App\Models\MailingSubscription;
use Filament\Tables\Actions\Action;
use Filament\Forms\Contracts\HasForms;
use Filament\Support\Enums\ActionSize;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Actions\Concerns\InteractsWithActions;

class UserMailingSubscriptions extends Page implements HasForms, HasTable, HasActions
{
    use InteractsWithForms;
    use InteractsWithTable;
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

    public function table(Table $table): Table
    {
        return $table
            ->query(MailingSubscription::query()->where('user_id', auth()->user()->id))
            ->columns([
                TextColumn::make('mailable')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(function ($state) {
                        return str($state)->after('App\\Mail\\')
                            ->headline();
                    })
            ])
            ->actions([
                Action::make(__('Unsusbscribe'))
                    ->button()
                    ->requiresConfirmation()
                    ->action(function (Model $record) {
                        $record->delete();
                    })
                    ->icon('heroicon-o-trash')
                    ->size(ActionSize::Small)
                    ->color(Color::Red),

            ]);
    }
}

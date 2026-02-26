<?php

namespace App\Livewire;

use App\Models\MailableUser;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.landing-page')]
class MyMailingSubscriptions extends Component implements HasActions, HasForms, HasTable
{
    use InteractsWithActions;
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                MailableUser::query()
                    ->where('user_id', auth()->id())
                    ->with('mailable')
            )
            ->columns([
                TextColumn::make('mailable.name')
                    ->label(__('Name'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('mailable.description')
                    ->label(__('Description'))
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                DeleteAction::make('unsubscribe')
                    ->modalHeading(fn (MailableUser $record): string => "Unsubscribe from {$record->mailable->name}")
                    ->label('Unsubscribe'),
            ])
            ->toolbarActions([
                DeleteBulkAction::make()
                    ->requiresConfirmation()
                    ->color('danger')
                    ->icon(Heroicon::OutlinedTrash)
                    ->label('Unsubscribe Selected'),
            ]);
    }

    public function render(): View
    {
        return view('livewire.my-mailing-subscriptions');
    }
}

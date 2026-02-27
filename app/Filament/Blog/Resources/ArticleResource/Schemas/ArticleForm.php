<?php

namespace App\Filament\Blog\Resources\ArticleResource\Schemas;

use App\Enums\ArticleStatus;
use App\Models\Article;
use App\Models\User;
use App\Services\ModelListService;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ArticleForm
{
    public static function configure(Schema $schema): Schema
    {
        $user = Auth::user();
        $canAssignCategories = Gate::check('assignCategories', Article::class);

        return $schema
            ->components([
                Section::make('Article Details')
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        // slug is auto-generated from title
                        Textarea::make('excerpt')
                            ->rows(2)
                            ->columnSpanFull(),
                        RichEditor::make('content')
                            ->required()
                            ->columnSpanFull(),
                        Select::make('author_id')
                            ->label('Author')
                            ->options(ModelListService::make(User::query()))
                            ->searchable()
                            ->default(fn () => $user?->id)
                            ->required()
                            ->visibleOn('create'),
                        TextInput::make('author_id')
                            ->label('Author')
                            ->disabled()
                            ->helperText(fn (?Article $record) => $record?->author?->name)
                            ->visibleOn('edit'),
                    ]),

                Section::make('Publishing')
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        Select::make('status')
                            ->options(ArticleStatus::class)
                            ->required()
                            ->default(ArticleStatus::Draft),
                        // DateTimePicker::make('published_at')
                        //     ->visible(fn ($get) => $get('status') === ArticleStatus::Published),
                    ]),

                Section::make('Categories')
                    ->columnSpanFull()
                    ->visible($canAssignCategories)
                    ->schema([
                        CheckboxList::make('categories')
                            ->relationship('categories', 'name')
                            ->options(ModelListService::make(\App\Models\Category::query()))
                            ->columns(2)
                            ->searchable(),
                    ]),

                Section::make('SEO')
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        Textarea::make('meta_description')
                            ->rows(2)
                            ->maxLength(160),
                        TextInput::make('meta_keywords')
                            ->maxLength(255),
                    ]),

                Section::make('Featured Image')
                    ->columnSpanFull()
                    ->schema([
                        FileUpload::make('featured_image_path')
                            ->image()
                            ->disk('public')
                            ->maxSize(5120)
                            ->directory('featured-images')
                            ->preserveFilenames(),
                    ]),
            ]);
    }
}

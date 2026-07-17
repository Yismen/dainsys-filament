<?php

namespace App\Filament\Blog\Resources\ArticleResource\Schemas;

use App\Enums\ArticleStatus;
use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use App\Services\ModelListService;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
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
                Section::make(__('filament.article_details'))
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')
                            ->label(__('filament.title'))
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Textarea::make('excerpt')
                            ->label(__('filament.excerpt'))
                            ->rows(2)
                            ->columnSpanFull(),
                        RichEditor::make('content')
                            ->label(__('filament.content'))
                            ->required()
                            ->columnSpanFull(),
                        Select::make('author_id')
                            ->label(__('filament.author'))
                            ->options(ModelListService::make(User::query()))
                            ->searchable()
                            ->default(fn () => $user?->id)
                            ->required()
                            ->visibleOn('create'),
                        TextInput::make('author_id')
                            ->label(__('filament.author'))
                            ->disabled()
                            ->helperText(fn (?Article $record) => $record?->author?->name)
                            ->visibleOn('edit'),
                    ]),

                Section::make(__('filament.publishing'))
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        Select::make('status')
                            ->label(__('filament.status'))
                            ->options(ArticleStatus::class)
                            ->required()
                            ->default(ArticleStatus::Draft),
                        Toggle::make('is_public')
                            ->label(__('filament.is_public'))
                            ->helperText('Public articles are visible to all users without category restrictions.')
                            ->default(false),
                    ]),

                Section::make(__('filament.categories'))
                    ->columnSpanFull()
                    ->visible($canAssignCategories)
                    ->schema([
                        CheckboxList::make('categories')
                            ->label(__('filament.categories'))
                            ->relationship('categories', 'name')
                            ->options(ModelListService::make(Category::query()))
                            ->columns(2)
                            ->searchable(),
                    ]),

                Section::make(__('filament.seo'))
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        Textarea::make('meta_description')
                            ->label(__('filament.meta_description'))
                            ->rows(2)
                            ->maxLength(160),
                        TextInput::make('meta_keywords')
                            ->label(__('filament.meta_keywords'))
                            ->maxLength(255),
                    ]),

                Section::make(__('filament.featured_image'))
                    ->columnSpanFull()
                    ->schema([
                        FileUpload::make('featured_image_path')
                            ->label(__('filament.featured_image_path'))
                            ->image()
                            ->disk('public')
                            ->imageEditor()
                            ->maxSize(5120)
                            ->directory('featured-images')
                            ->preserveFilenames(),
                    ]),
            ]);
    }
}

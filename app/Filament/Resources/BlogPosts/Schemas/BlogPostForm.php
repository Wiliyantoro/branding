<?php

namespace App\Filament\Resources\BlogPosts\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BlogPostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Post Details')
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, $set) => $set('slug', \Illuminate\Support\Str::slug($state))),
                        TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        TextInput::make('category')
                            ->placeholder('e.g., Tutorial, Tips, News'),
                        FileUpload::make('featured_image')
                            ->label('Featured Image')
                            ->image()
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/gif'])
                            ->disk('public')
                            ->directory('blog')
                            ->visibility('public')
                            ->maxSize(2048)
                            ->helperText('Maks 2 MB. Boleh juga tempel URL lama lewat impor data.'),
                    ])->columns(2),

                Section::make('Content')
                    ->schema([
                        Textarea::make('excerpt')
                            ->rows(2)
                            ->maxLength(500)
                            ->placeholder('Short summary of the post...'),
                        RichEditor::make('content')
                            ->required()
                            ->columnSpanFull(),
                    ]),

                Section::make('Publishing')
                    ->schema([
                        Toggle::make('is_published')
                            ->label('Published'),
                    ]),
            ]);
    }
}

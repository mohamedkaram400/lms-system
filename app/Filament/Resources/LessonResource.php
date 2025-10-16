<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LessonResource\Pages;
use App\Models\Lesson;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class LessonResource extends Resource
{
    protected static ?string $model = Lesson::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Courses Management';
    protected static ?string $navigationLabel = 'Lessons';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('course_id')
                    ->label('Course')
                    ->relationship('course', 'title') 
                    ->required()
                    ->searchable()
                    ->preload(),

                Forms\Components\TextInput::make('title')
                    ->label('Lesson Title')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('order')
                    ->label('Order')
                    ->numeric()
                    ->required()
                    ->helperText('Used to sort lessons inside a course.'),

                Forms\Components\TextInput::make('video_url')
                    ->label('Video URL')
                    ->url()
                    ->maxLength(500)
                    ->required(),

                Forms\Components\TextInput::make('duration_seconds')
                    ->label('Duration (seconds)')
                    ->numeric()
                    ->required(),

                Forms\Components\Toggle::make('is_free_preview')
                    ->label('Free Preview')
                    ->default(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('course.title')
                    ->label('Course')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('title')
                    ->label('Lesson Title')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('order')
                    ->label('Order')
                    ->sortable(),

                Tables\Columns\TextColumn::make('video_url')
                    ->label('Video URL')
                    ->limit(30),

                Tables\Columns\TextColumn::make('duration_seconds')
                    ->label('Duration (sec)')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_free_preview')
                    ->label('Free Preview')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLessons::route('/'),
            'create' => Pages\CreateLesson::route('/create'),
            'edit' => Pages\EditLesson::route('/{record}/edit'),
        ];
    }
}

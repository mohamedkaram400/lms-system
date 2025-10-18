<?php

namespace App\Filament\Resources;

use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\LessonProgress;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use App\Filament\Resources\LessonProgressResource\Pages;

class LessonProgressResource extends Resource
{
    protected static ?string $model = LessonProgress::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Lesson Progress';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                 Select::make('user_id')
                    ->label('User')
                    ->relationship('user', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),

                Select::make('lesson_id')
                    ->label('Lesson')
                    ->relationship('lesson', 'title')
                    ->required()
                    ->searchable()
                    ->preload(),

                DateTimePicker::make('started_at')
                    ->disabled(),

                DateTimePicker::make('completed_at')
                    ->disabled(),

                TextInput::make('watch_seconds')
                    ->disabled()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')->label('User')->sortable(),
                Tables\Columns\TextColumn::make('lesson.title')->label('Lesson')->sortable(),
                Tables\Columns\TextColumn::make('started_at')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('completed_at')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('watch_seconds')->label('Watch (s)'),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\CreateAction::make()->disabled(),
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

    protected function getHeaderActions(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLessonProgress::route('/'),
            'create' => Pages\CreateLessonProgress::route('/create'),
            'edit' => Pages\EditLessonProgress::route('/{record}/edit'),
        ];
    }
}

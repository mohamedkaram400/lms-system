<?php

namespace App\Filament\Resources;

use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Enrollment;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use App\Filament\Resources\EnrollmentResource\Pages\EditEnrollment;
use App\Filament\Resources\EnrollmentResource\Pages\ListEnrollment;
use App\Filament\Resources\EnrollmentResource\Pages\CreateEnrollment;

class EnrollmentResource extends Resource
{
    protected static ?string $model = Enrollment::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Enrollments';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('course_id')
                    ->label('Course')
                    ->relationship('course', 'title') 
                    ->required()
                    ->searchable()
                    ->preload(),

                Select::make('user_id')
                    ->label('User')
                    ->relationship('user', 'name') 
                    ->required()
                    ->searchable()
                    ->preload(),
                    
                DateTimePicker::make('enrolled_at')
                    ->label('Enrollment Time')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')->label('User')->sortable(),
                Tables\Columns\TextColumn::make('course.title')->label('Course')->sortable(),
                Tables\Columns\TextColumn::make('enrolled_at')->label('Enrolled At')->dateTime()->sortable(),            
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
            'index' => ListEnrollment::route('/'),
            'create' => CreateEnrollment::route('/create'),
            'edit' => EditEnrollment::route('/{record}/edit'),
        ];
    }
}


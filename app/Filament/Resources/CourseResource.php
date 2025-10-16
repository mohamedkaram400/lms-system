<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Course;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use App\Filament\Resources\CourseResource\Pages;

class CourseResource extends Resource
{
    protected static ?string $model = Course::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Courses Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // 📝 Title
                Forms\Components\TextInput::make('title')
                    ->label('Course Title')
                    ->required()
                    ->maxLength(255)
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        $set('slug', Str::slug($state));
                    }),

                // 📝 Description
                Forms\Components\Textarea::make('description')
                    ->label('Full Description')
                    ->maxLength(1000)
                    ->rows(4),

                // 📝 Short Description
                Forms\Components\Textarea::make('short_description')
                    ->label('Short Description')
                    ->maxLength(300)
                    ->rows(2),

                // 📊 Level
                Forms\Components\Select::make('level')
                    ->options([
                        'beginner' => 'Beginner',
                        'intermediate' => 'Intermediate',
                        'advanced' => 'Advanced',
                    ])
                    ->required(),

                // 🏷️ Category
                Forms\Components\Select::make('category_id')
                    ->relationship('category', 'name')
                    ->required(),

                // 💰 Price
                Forms\Components\TextInput::make('price')
                    ->numeric()
                    ->minValue(0)
                    ->required(),

                // 🌍 Language
                Forms\Components\Select::make('language')
                    ->options([
                        'en' => 'English',
                        'ar' => 'Arabic',
                        'fr' => 'French',
                    ])
                    ->required(),

                // ⏳ Duration
                Forms\Components\TextInput::make('duration')
                    ->label('Duration (e.g. 5h 30m)')
                    ->maxLength(50),

                // 📸 Thumbnail
                Forms\Components\FileUpload::make('thumbnail')
                    ->directory('thumbnails/courses')
                    ->image()
                    ->maxSize(2048)
                    ->label('Course Thumbnail'),

                // ✅ Published
                Forms\Components\Toggle::make('is_published')
                    ->label('Published')
                    ->default(false),
            ]);                                              
        }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('thumbnail')->label('Thumbnail')->circular(),
                Tables\Columns\TextColumn::make('title')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('category.name')->label('Category')->sortable(),
                Tables\Columns\TextColumn::make('level')->sortable(),
                Tables\Columns\TextColumn::make('price')->money('USD', true),
                Tables\Columns\IconColumn::make('is_published')->boolean()->label('Published'),
                Tables\Columns\TextColumn::make('created_at')->date()->label('Created At'),
                Tables\Columns\TextColumn::make('creator.name')->label('Created By')->sortable()
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_published')->label('Published'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListCourses::route('/'),
            'create' => Pages\CreateCourse::route('/create'),
            'edit' => Pages\EditCourse::route('/{record}/edit'),
        ];
    }
}

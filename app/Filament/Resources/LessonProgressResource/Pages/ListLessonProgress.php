<?php

namespace App\Filament\Resources\LessonProgressResource\Pages;

use App\Filament\Resources\LessonProgressResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLessonProgress extends ListRecords
{
    protected static string $resource = LessonProgressResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

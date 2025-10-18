<?php

namespace App\Filament\Resources\CourseCompletionResource\Pages;

use App\Filament\Resources\CourseCompletionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCourseCompletion extends EditRecord
{
    protected static string $resource = CourseCompletionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

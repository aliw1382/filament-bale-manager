<?php

namespace Aliw1382\FilamentBaleManager\Resources\BaleResource\Pages;

use Aliw1382\FilamentBaleManager\Resources\BaleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBales extends ListRecords
{
    protected static string $resource = BaleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

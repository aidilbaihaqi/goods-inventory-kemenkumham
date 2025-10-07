<?php

namespace App\Filament\Widgets;

use Filament\Widgets\AccountWidget as BaseAccountWidget;

class CustomAccountWidget extends BaseAccountWidget
{
    protected int | string | array $columnSpan = 'full';
    
    protected static ?int $sort = 0;
    
    protected function getViewData(): array
    {
        return [
            'user' => auth()->user(),
        ];
    }
}
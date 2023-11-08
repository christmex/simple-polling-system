<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class CandidateOverview extends Widget
{
    protected static string $view = 'filament.widgets.candidate-overview';

    protected int | string | array $columnSpan = 'full';

    protected static ?string $pollingInterval = '1s';

    protected static ?string $maxHeight = '100px';
}

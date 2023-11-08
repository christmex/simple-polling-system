<?php

namespace App\Filament\Widgets;

use App\Models\Candidate;
use Filament\Widgets\ChartWidget;

class RealtimePollingChart extends ChartWidget
{
    protected static ?string $heading = 'Pemilihan Ketua OSIS SMA BASIC BATAM CENTER';

    protected int | string | array $columnSpan = 'full';

    protected static ?string $pollingInterval = '1s';

    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    // 'label' => 'Realtime Polling System - Pemilihan Ketua OSIS SMA BASIC BATAM CENTER',
                    'label' => 'Total Suara',
                    'data' => Candidate::getAllCandidateVotes(),
                    'backgroundColor' => '#36A2EB',
                    'borderColor' => '#9BD0F5',
                ],
            ],
            'labels' => Candidate::getAllCandidate(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}

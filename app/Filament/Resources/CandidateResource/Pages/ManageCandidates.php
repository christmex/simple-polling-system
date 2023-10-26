<?php

namespace App\Filament\Resources\CandidateResource\Pages;

use Filament\Actions;
use App\Models\Candidate;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;
use App\Filament\Resources\CandidateResource;

class ManageCandidates extends ManageRecords
{
    protected static string $resource = CandidateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('Reset Voters')
                ->color('danger')
                ->action(function(){
                    Candidate::where('id','>',0)->update(['votes' => 0]);
                    Notification::make()
                        ->success()
                        ->title('Successfully reset voters')
                        ->send();
                }),
        ];
    }
}

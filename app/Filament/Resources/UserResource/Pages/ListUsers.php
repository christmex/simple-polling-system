<?php

namespace App\Filament\Resources\UserResource\Pages;

use Filament\Actions;
use App\Imports\UsersImport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;
use Maatwebsite\Excel\Facades\Excel;
use App\Filament\Resources\UserResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('importUser')->color('success')
                ->form([
                    \Filament\Forms\Components\FileUpload::make('import_user')
                        ->storeFiles(false)
                        ->helperText(new HtmlString('Please download the excel file to use our format before you upload the file, or ask your admin level'))
                        ->columnSpanFull(),
                ])
                ->action(function(array $data){
                    DB::beginTransaction();
                    try {
                        Excel::import(new UsersImport, $data['import_user']);
                        DB::commit();
                        Notification::make()
                            ->success()
                            ->title('User imported')
                            ->send();
                    } catch (\Throwable $th) {
                        DB::rollback();
                        Notification::make()
                            ->danger()
                            ->title($th->getMessage())
                            ->send();
                    }
                })
        ];
    }

    protected function getHeaderWidgets() :array
    {
        return [
            UserResource\Widgets\UserOverview::class,
        ];
    }
}

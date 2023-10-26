<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Tabs;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TernaryFilter;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use STS\FilamentImpersonate\Tables\Actions\Impersonate;
use App\Filament\Resources\UserResource\RelationManagers;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Label')
                ->tabs([
                    Tabs\Tab::make('User Login')
                        ->schema([
                            TextInput::make('name')
                                ->required()
                                ->maxLength(255),
                            TextInput::make('email')
                                ->email()
                                ->required()
                                ->unique(ignoreRecord:true)
                                ->maxLength(255),
                            TextInput::make('password')
                                ->password()
                                ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
                                ->dehydrated(fn (?string $state): bool => filled($state))
                                ->required(fn (string $operation): bool => $operation === 'create'),
                            Select::make('roles')
                                ->relationship('roles', 'name')
                                ->multiple()
                                ->preload()
                                ->searchable(),
                    ]),
                    Tabs\Tab::make('User Details')
                        ->schema([
                            TextInput::make('citizenship_number')
                                ->unique(ignoreRecord:true)
                                ->maxLength(255),
                            TextInput::make('born_place'),
                            DatePicker::make('born_date'),
                    ]),
                    Tabs\Tab::make('Employee Details')
                        ->schema([
                            DatePicker::make('join_date'),
                            DatePicker::make('finish_contract'),
                            DatePicker::make('permanent_date'),
                            DatePicker::make('bpjs_join_date'),
                            DatePicker::make('jht_join_date'),
                            DatePicker::make('kemnaker_join_date'),
                            DatePicker::make('read_employee_terms_date'),
                            Textarea::make('notes')
                                ->columnSpanFull(),
                        ]),
                ])
                ->columnSpanFull()
                ->columns([
                    'sm' => 1,
                    'xl' => 3,
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('born_place')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextInputColumn::make('born_date')
                ->type('date')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('citizenship_number')
                    ->searchable(),
                Tables\Columns\TextInputColumn::make('permanent_date')
                    ->type('date')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextInputColumn::make('join_date')
                    ->type('date')
                    ->sortable(),
                Tables\Columns\TextInputColumn::make('finish_contract')
                    ->type('date')
                    ->sortable(),
                Tables\Columns\TextInputColumn::make('bpjs_join_date')
                    ->type('date')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextInputColumn::make('jht_join_date')
                    ->type('date')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextInputColumn::make('kemnaker_join_date')
                    ->type('date')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextInputColumn::make('read_employee_terms_date')
                    ->type('date')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('notes')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                DateRangeFilter::make('finish_contract')
                    ->ranges([
                        __('filament-daterangepicker-filter::message.today') => [now(), now()],
                        __('filament-daterangepicker-filter::message.yesterday') => [now()->subDay(), now()->subDay()],
                        __('filament-daterangepicker-filter::message.last_7_days') => [now()->subDays(6), now()],
                        __('filament-daterangepicker-filter::message.last_30_days') => [now()->subDays(29), now()],
                        __('filament-daterangepicker-filter::message.this_month') => [now()->startOfMonth(), now()->endOfMonth()],
                        'Next Month' => [now()->startOfMonth()->addMonth(), now()->endOfMonth()->addMonth()],
                        __('filament-daterangepicker-filter::message.last_month') => [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()],
                        __('filament-daterangepicker-filter::message.this_year') => [now()->startOfYear(), now()->endOfYear()],
                        __('filament-daterangepicker-filter::message.last_year') => [now()->subYear()->startOfYear(), now()->subYear()->endOfYear()],
                    ])
                    ->withIndicator(),
                Tables\Filters\TrashedFilter::make(),
                
                TernaryFilter::make('employee_status')
                ->placeholder('All')
                ->trueLabel('Only Permanent')
                ->falseLabel('Only Cotract')
                ->queries(
                    true: fn (Builder $query) => $query->where('finish_contract',NULL),
                    false: fn (Builder $query) => $query->where('finish_contract','!=',NULL),
                    blank: fn (Builder $query) => $query,
                )
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Impersonate::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }    

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getWidgets(): array
    {
        return [
            UserResource\Widgets\UserOverview::class,
        ];
    }
}

<?php

namespace App\Filament\Resources\Transactions\Pages;

use App\Filament\Resources\Transactions\Schemas\TransactionForm;
use App\Filament\Resources\Transactions\TransactionResource;
use App\Filament\Widgets\AccountingStatsOverview;
use App\Models\Transaction;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\EmbeddedTable;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class ListTransactions extends ListRecords
{
    use HasFiltersForm;

    protected static string $resource = TransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make('cashIn')
                ->label('Cash In')
                ->icon(Heroicon::Plus)
                ->color('success')
                ->model(Transaction::class)
                ->schema(fn (Schema $schema): Schema => TransactionForm::configure($schema))
                ->fillForm(['type' => 'income', 'transaction_date' => now()]),

            CreateAction::make('cashOut')
                ->label('Cash Out')
                ->icon(Heroicon::Minus)
                ->color('danger')
                ->model(Transaction::class)
                ->schema(fn (Schema $schema): Schema => TransactionForm::configure($schema))
                ->fillForm(['type' => 'expense', 'transaction_date' => now()]),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            AccountingStatsOverview::class,
        ];
    }

    public function filtersForm(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('range')
                    ->label('Period')
                    ->options([
                        'today'      => 'Today',
                        'this_week'  => 'This week',
                        'this_month' => 'This month',
                        'this_year'  => 'This year',
                        'custom'     => 'Custom range',
                    ])
                    ->default('this_month')
                    ->selectablePlaceholder(false)
                    ->live(),
                DatePicker::make('startDate')
                    ->label('From')
                    ->visible(fn (Get $get): bool => $get('range') === 'custom'),
                DatePicker::make('endDate')
                    ->label('To')
                    ->visible(fn (Get $get): bool => $get('range') === 'custom'),
            ]);
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                EmbeddedSchema::make('filtersForm'),
                EmbeddedTable::make(),
            ]);
    }
}

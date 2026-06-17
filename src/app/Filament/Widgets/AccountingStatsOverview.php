<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Transactions\TransactionResource;
use App\Models\Transaction;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AccountingStatsOverview extends StatsOverviewWidget
{
    use InteractsWithPageFilters;

    protected function getStats(): array
    {
        // All-time balance (cash on hand) — independent of the selected range.
        $allIncome = (float) Transaction::where('type', 'income')->sum('amount');
        $allExpense = (float) Transaction::where('type', 'expense')->sum('amount');
        $balance = $allIncome - $allExpense;

        // Period figures driven by the shared date-range filter.
        [$start, $end] = TransactionResource::resolveDateRange($this->pageFilters);

        $rangeQuery = Transaction::whereBetween('transaction_date', [$start, $end]);
        $cashIn = (float) (clone $rangeQuery)->where('type', 'income')->sum('amount');
        $cashOut = (float) (clone $rangeQuery)->where('type', 'expense')->sum('amount');
        $net = $cashIn - $cashOut;

        return [
            Stat::make('Balance', $this->money($balance))
                ->description('Cash on hand (all time)')
                ->descriptionIcon(Heroicon::Banknotes)
                ->color($balance >= 0 ? 'success' : 'danger'),

            Stat::make('Cash In', $this->money($cashIn))
                ->description('Money in for period')
                ->descriptionIcon(Heroicon::ArrowTrendingUp)
                ->color('success'),

            Stat::make('Cash Out', $this->money($cashOut))
                ->description('Money out for period')
                ->descriptionIcon(Heroicon::ArrowTrendingDown)
                ->color('danger'),

            Stat::make('Net Cashflow', $this->money($net))
                ->description('Cash In − Cash Out for period')
                ->descriptionIcon($net >= 0 ? Heroicon::ArrowTrendingUp : Heroicon::ArrowTrendingDown)
                ->color($net >= 0 ? 'success' : 'danger'),
        ];
    }

    protected function money(float $amount): string
    {
        return '৳ ' . number_format($amount, 2);
    }
}

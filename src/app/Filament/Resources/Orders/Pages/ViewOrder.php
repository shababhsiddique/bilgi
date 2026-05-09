<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\ViewRecord;

class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            Action::make('changePaymentStatus')
                ->label('Change Payment Status')
                ->icon('heroicon-o-credit-card')
                ->schema([
                    Select::make('payment_status')
                        ->label('Payment Status')
                        ->options([
                            'unpaid' => 'Unpaid',
                            'pending' => 'Pending',
                            'paid' => 'Paid',
                            'refunded' => 'Refunded',
                            'failed' => 'Failed',
                        ])
                        ->required()
                        ->default(fn() => $this->record->payment_status),
                ])
                ->action(function (array $data) {
                    $this->record->update([
                        'payment_status' => $data['payment_status'],
                    ]);
                }),
            Action::make('changeStatus')
                ->label('Change Status')
                ->icon('heroicon-o-arrow-path')
                ->schema([
                    Select::make('status')
                        ->label('Status')
                        ->options([
                            'pending' => 'Pending',
                            'processing' => 'Processing',
                            'on_hold' => 'On hold',
                            'completed' => 'Completed',
                            'cancelled' => 'Cancelled',
                            'refunded' => 'Refunded',
                            'failed' => 'Failed',
                        ])
                        ->required()
                        ->default(fn() => $this->record->status),
                ])
                ->action(function (array $data) {
                    $this->record->update([
                        'status' => $data['status'],
                    ]);
                }),

        ];
    }
}

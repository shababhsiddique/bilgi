<?php

namespace App\Filament\Pages;

use App\Models\Order;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class OrderCenter extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static ?string $navigationLabel = 'Order Center';

    protected static ?int $navigationSort = -1;

    protected static ?string $title = 'Order Center';

    protected ?string $subheading = 'Orders that need your attention.';

    protected string $view = 'filament.pages.order-center';

    /**
     * Statuses an order can move through.
     */
    public const STATUS_OPTIONS = [
        'unverified' => 'Unverified',
        'pending'    => 'Pending',
        'processing' => 'Processing',
        'on_hold'    => 'On hold',
        'completed'  => 'Completed',
        'cancelled'  => 'Cancelled',
        'refunded'   => 'Refunded',
        'failed'     => 'Failed',
    ];

    /**
     * Payment statuses an order can move through.
     */
    public const PAYMENT_STATUS_OPTIONS = [
        'unpaid'   => 'Unpaid',
        'pending'  => 'Pending',
        'paid'     => 'Paid',
        'refunded' => 'Refunded',
        'failed'   => 'Failed',
    ];

    /**
     * Statuses that mean the order still needs attention.
     */
    public const ACTIVE_STATUSES = ['unverified', 'pending', 'processing', 'on_hold'];

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Order::query()
                    ->with(['customer', 'shippingAddress', 'paymentMethod'])
                    ->where(function (Builder $query) {
                        $query->whereIn('status', self::ACTIVE_STATUSES)
                            ->orWhereIn('payment_status', ['unpaid', 'pending']);
                    })
            )
            ->defaultSort('placed_at', 'desc')
            ->columns([
                TextColumn::make('order_number')
                    ->label('Order')
                    ->searchable()
                    ->weight('bold')
                    ->copyable(),
                TextColumn::make('shippingAddress.name')
                    ->label('Customer')
                    ->placeholder('-')
                    ->searchable(),
                TextColumn::make('shippingAddress.phone')
                    ->label('Phone')
                    ->placeholder('-')
                    ->searchable(),
                TextColumn::make('total_amount')
                    ->label('Total')
                    ->money('BDT')
                    ->sortable(),
                TextColumn::make('payment_method_id')
                    ->label('Method')
                    ->formatStateUsing(fn (Order $record) => $record->paymentMethod?->name ?? '-'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'completed' => 'success',
                        'processing', 'on_hold' => 'warning',
                        'cancelled', 'failed' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('payment_status')
                    ->label('Payment')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'paid' => 'success',
                        'pending' => 'warning',
                        'failed', 'unpaid' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('placed_at')
                    ->label('Placed')
                    ->since()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(self::STATUS_OPTIONS),
                SelectFilter::make('payment_status')
                    ->label('Payment status')
                    ->options(self::PAYMENT_STATUS_OPTIONS),
            ])
            ->recordActions([
                Action::make('changeStatus')
                    ->label('Status')
                    ->icon('heroicon-o-arrow-path')
                    ->color('primary')
                    ->schema([
                        Select::make('status')
                            ->label('Order Status')
                            ->options(self::STATUS_OPTIONS)
                            ->required()
                            ->default(fn (Order $record) => $record->status),
                    ])
                    ->action(function (Order $record, array $data) {
                        $record->update(['status' => $data['status']]);

                        Notification::make()
                            ->title('Order status updated')
                            ->body("Order {$record->order_number} is now {$data['status']}.")
                            ->success()
                            ->send();
                    }),
                Action::make('changePaymentStatus')
                    ->label('Payment')
                    ->icon('heroicon-o-credit-card')
                    ->color('warning')
                    ->schema([
                        Select::make('payment_status')
                            ->label('Payment Status')
                            ->options(self::PAYMENT_STATUS_OPTIONS)
                            ->required()
                            ->default(fn (Order $record) => $record->payment_status),
                    ])
                    ->action(function (Order $record, array $data) {
                        $record->update(['payment_status' => $data['payment_status']]);

                        Notification::make()
                            ->title('Payment status updated')
                            ->body("Order {$record->order_number} payment is now {$data['payment_status']}.")
                            ->success()
                            ->send();
                    }),
                Action::make('invoice')
                    ->label('Invoice')
                    ->icon('heroicon-o-printer')
                    ->color('gray')
                    ->url(fn (Order $record) => route('admin.orders.invoice', $record), shouldOpenInNewTab: true),
            ]);
    }
}

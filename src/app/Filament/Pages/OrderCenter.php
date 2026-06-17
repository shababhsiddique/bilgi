<?php

namespace App\Filament\Pages;

use App\Filament\Resources\Orders\OrderResource;
use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\ProductVariant;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

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
        //'unverified' => 'Unverified',
        'pending'    => 'Pending',
        'processing' => 'Processing',
        //'on_hold'    => 'On hold',
        'completed'  => 'Completed',
        'cancelled'  => 'Cancelled',
        'refunded'   => 'Refunded',
        //'failed'     => 'Failed',
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

    /**
     * Header actions for the page.
     */
    protected function getHeaderActions(): array
    {
        return [
            $this->createOrderAction(),
        ];
    }

    /**
     * Manual order creation wizard. Used to log orders that arrive over the
     * phone, Messenger or WhatsApp so every order lives in one inventory.
     */
    protected function createOrderAction(): Action
    {
        return Action::make('createManualOrder')
            ->label('Place Order')
            ->icon('heroicon-o-plus-circle')
            ->color('primary')
            ->modalHeading('Place a new order')
            ->modalSubmitActionLabel('Place order')
            ->modalWidth('5xl')
            ->steps([
                $this->customerStep(),
                $this->itemsStep(),
                $this->shippingStep(),
                $this->paymentStep(),
            ])
            ->action(fn (array $data) => $this->placeOrder($data));
    }

    protected function customerStep(): Step
    {
        return Step::make('Customer')
            ->description('Find by phone, or create a new customer')
            ->icon('heroicon-o-user')
            ->schema([
                Select::make('channel')
                    ->label('Channel')
                    ->options([
                        'website'  => 'Website',
                        'facebook' => 'Facebook',
                        'whatsapp' => 'Whatsapp',
                        'daraz'    => 'Daraz',
                    ])
                    ->default('website')
                    ->selectablePlaceholder(false)
                    ->required(),
                Select::make('customer_id')
                    ->label('Customer')
                    ->placeholder('Search by phone or name…')
                    ->searchable()
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($state, Set $set): void {
                        $customer = Customer::find($state);

                        if ($customer) {
                            $set('ship_name', $customer->full_name);
                            $set('ship_phone', $customer->phone);
                        }
                    })
                    ->getSearchResultsUsing(fn (string $search): array => Customer::query()
                        ->where('phone', 'like', "%{$search}%")
                        ->orWhere('full_name', 'like', "%{$search}%")
                        ->limit(25)
                        ->get()
                        ->mapWithKeys(fn (Customer $c): array => [$c->id => "{$c->full_name} — {$c->phone}"])
                        ->all())
                    ->getOptionLabelUsing(function ($value): ?string {
                        $c = Customer::find($value);

                        return $c ? "{$c->full_name} — {$c->phone}" : null;
                    })
                    ->createOptionForm([
                        TextInput::make('full_name')
                            ->label('Full name')
                            ->required(),
                        TextInput::make('phone')
                            ->label('Phone')
                            ->tel()
                            ->required(),
                        TextInput::make('email')
                            ->label('Email (optional)')
                            ->email(),
                    ])
                    ->createOptionUsing(function (array $data): int {
                        $existing = Customer::where('phone', $data['phone'])->first();

                        if ($existing) {
                            return $existing->id;
                        }

                        return Customer::create([
                            'full_name'           => $data['full_name'],
                            'phone'               => $data['phone'],
                            'email'               => $data['email'] ?? null,
                            'password'            => Hash::make(Str::random(12)),
                            'is_guest_registered' => true,
                        ])->id;
                    })
                    ->createOptionModalHeading('New customer'),
            ]);
    }

    protected function itemsStep(): Step
    {
        return Step::make('Items')
            ->description('Add products and variations')
            ->icon('heroicon-o-shopping-bag')
            ->schema([
                Repeater::make('items')
                    ->label('Order items')
                    ->addActionLabel('Add item')
                    ->minItems(1)
                    ->defaultItems(1)
                    ->live()
                    ->columns(12)
                    ->schema([
                        Select::make('product_id')
                            ->label('Product')
                            ->columnSpan(5)
                            ->searchable()
                            ->required()
                            ->live()
                            ->getSearchResultsUsing(fn (string $search): array => Product::query()
                                ->where('name', 'like', "%{$search}%")
                                ->limit(25)
                                ->pluck('name', 'id')
                                ->all())
                            ->getOptionLabelUsing(fn ($value): ?string => Product::find($value)?->name)
                            ->afterStateUpdated(function ($state, Set $set): void {
                                $variant = ProductVariant::where('product_id', $state)
                                    ->orderByDesc('is_default')
                                    ->orderBy('id')
                                    ->first();

                                $set('product_variant_id', $variant?->id);
                                $set('unit_price', $variant?->sales_price ?? 0);
                            }),
                        Select::make('product_variant_id')
                            ->label('Variation')
                            ->columnSpan(3)
                            ->required()
                            ->live()
                            ->options(fn (Get $get): array => $get('product_id')
                                ? ProductVariant::where('product_id', $get('product_id'))
                                    ->pluck('name', 'id')
                                    ->all()
                                : [])
                            ->afterStateUpdated(function ($state, Set $set): void {
                                $variant = ProductVariant::find($state);

                                if ($variant) {
                                    $set('unit_price', $variant->sales_price);
                                }
                            }),
                        TextInput::make('quantity')
                            ->label('Qty')
                            ->columnSpan(2)
                            ->numeric()
                            ->minValue(1)
                            ->default(1)
                            ->required()
                            ->live(onBlur: true),
                        TextInput::make('unit_price')
                            ->label('Unit price')
                            ->columnSpan(2)
                            ->numeric()
                            ->prefix('৳')
                            ->minValue(0)
                            ->default(0)
                            ->required()
                            ->live(onBlur: true),
                    ]),
                Placeholder::make('items_subtotal')
                    ->label('Items subtotal')
                    ->content(fn (Get $get): HtmlString => new HtmlString(
                        '<span class="text-base font-semibold">৳ '
                        . number_format($this->subtotalFrom($get('items')), 2)
                        . '</span>'
                    )),
            ]);
    }

    protected function shippingStep(): Step
    {
        $divisions = config('address.divisions', []);

        return Step::make('Shipping')
            ->description('Delivery address')
            ->icon('heroicon-o-truck')
            ->schema([
                Select::make('shipping_address_id')
                    ->label('Saved address')
                    ->placeholder('Pick a saved address, or fill in a new one below')
                    ->live()
                    ->options(fn (Get $get): array => $get('customer_id')
                        ? CustomerAddress::where('customer_id', $get('customer_id'))
                            ->get()
                            ->mapWithKeys(fn (CustomerAddress $a): array => [
                                $a->id => trim("{$a->name} — {$a->address}, {$a->city}, {$a->state}"),
                            ])
                            ->all()
                        : [])
                    ->afterStateUpdated(function ($state, Set $set): void {
                        $address = CustomerAddress::find($state);

                        if ($address) {
                            $set('shipping_amount', $this->shippingCostFor($address->state));
                        }
                    })
                    ->helperText('Leave empty to enter a new address below.'),
                Section::make('New address')
                    ->description('Only needed when no saved address is selected.')
                    ->visible(fn (Get $get): bool => blank($get('shipping_address_id')))
                    ->columns(2)
                    ->schema([
                        TextInput::make('ship_name')
                            ->label('Recipient name')
                            ->required(fn (Get $get): bool => blank($get('shipping_address_id'))),
                        TextInput::make('ship_phone')
                            ->label('Phone')
                            ->tel()
                            ->required(fn (Get $get): bool => blank($get('shipping_address_id'))),
                        TextInput::make('ship_address')
                            ->label('Address')
                            ->columnSpanFull()
                            ->required(fn (Get $get): bool => blank($get('shipping_address_id'))),
                        Select::make('ship_state')
                            ->label('Division')
                            ->options($divisions)
                            ->live()
                            ->required(fn (Get $get): bool => blank($get('shipping_address_id')))
                            ->afterStateUpdated(function ($state, Set $set): void {
                                $set('ship_city', null);
                                $set('shipping_amount', $this->shippingCostFor($state));
                            }),
                        Select::make('ship_city')
                            ->label('City / District')
                            ->options(fn (Get $get): array => config('address.districts')[$get('ship_state')] ?? [])
                            ->disabled(fn (Get $get): bool => blank($get('ship_state')))
                            ->required(fn (Get $get): bool => blank($get('shipping_address_id'))),
                        TextInput::make('ship_postcode')
                            ->label('Postcode'),
                    ]),
            ]);
    }

    protected function paymentStep(): Step
    {
        return Step::make('Payment')
            ->description('Payment, status & review')
            ->icon('heroicon-o-credit-card')
            ->schema([
                Grid::make(2)->schema([
                    Select::make('payment_method_id')
                        ->label('Payment method')
                        ->options(fn (): array => PaymentMethod::query()
                            ->where('is_active', true)
                            ->orderBy('sort_order')
                            ->pluck('name', 'id')
                            ->all())
                        ->required(),
                    Select::make('payment_status')
                        ->label('Payment status')
                        ->options(self::PAYMENT_STATUS_OPTIONS)
                        ->default('unpaid')
                        ->required(),
                    Select::make('status')
                        ->label('Order status')
                        ->options(self::STATUS_OPTIONS)
                        ->default('pending')
                        ->required(),
                    TextInput::make('discount_amount')
                        ->label('Discount')
                        ->numeric()
                        ->prefix('৳')
                        ->minValue(0)
                        ->default(0)
                        ->live(onBlur: true),
                    TextInput::make('shipping_amount')
                        ->label('Shipping')
                        ->numeric()
                        ->prefix('৳')
                        ->minValue(0)
                        ->default(0)
                        ->live(onBlur: true)
                        ->helperText('Auto-filled from the delivery division; override if needed.'),
                ]),
                Grid::make(2)->schema([
                    TextInput::make('consignment_id')
                        ->label('Steadfast consignment ID')
                        ->maxLength(255)
                        ->helperText('Optional — add now if already handed over to Steadfast.'),
                    TextInput::make('tracking_token')
                        ->label('Steadfast tracking code')
                        ->maxLength(255)
                        ->helperText('Public tracking code for the customer tracking link.'),
                ]),
                Textarea::make('seller_note')
                    ->label('Seller note (internal)')
                    ->rows(2),
                Textarea::make('notes')
                    ->label('Order notes')
                    ->rows(2),
                Placeholder::make('order_summary')
                    ->label('Summary')
                    ->content(fn (Get $get): HtmlString => $this->summaryHtml($get)),
            ]);
    }

    /**
     * Persist a manually placed order and its items.
     */
    protected function placeOrder(array $data): void
    {
        DB::transaction(function () use ($data): void {
            $customer = Customer::findOrFail($data['customer_id']);

            if (! empty($data['shipping_address_id'])) {
                $address = CustomerAddress::findOrFail($data['shipping_address_id']);
            } else {
                $address = CustomerAddress::create([
                    'customer_id'  => $customer->id,
                    'is_default'   => false,
                    'address_name' => 'Manual '.uniqid(),
                    'name'         => $data['ship_name'],
                    'phone'        => $data['ship_phone'],
                    'address'      => $data['ship_address'],
                    'city'         => $data['ship_city'],
                    'state'        => $data['ship_state'],
                    'postcode'     => $data['ship_postcode'] ?? null,
                    'country'      => 'BD',
                ]);
            }

            $items    = collect($data['items'] ?? []);
            $subtotal = $this->subtotalFrom($items->all());
            $shipping = (float) ($data['shipping_amount'] ?? $this->shippingCostFor($address->state));
            $discount = (float) ($data['discount_amount'] ?? 0);
            $total    = $subtotal + $shipping - $discount;

            $order = Order::create([
                'customer_id'         => $customer->id,
                'order_number'        => $this->generateOrderNumber(),
                'channel'             => $data['channel'] ?? 'website',
                'status'              => $data['status'] ?? 'pending',
                'subtotal'            => $subtotal,
                'discount_amount'     => $discount,
                'shipping_amount'     => $shipping,
                'total_amount'        => $total,
                'shipping_address_id' => $address->id,
                'billing_address_id'  => $address->id,
                'payment_method_id'   => $data['payment_method_id'],
                'payment_status'      => $data['payment_status'] ?? 'unpaid',
                'notes'               => $data['notes'] ?? null,
                'seller_note'         => $data['seller_note'] ?? null,
                'consignment_id'      => $data['consignment_id'] ?? null,
                'tracking_token'      => $data['tracking_token'] ?? null,
                'placed_at'           => now(),
            ]);

            foreach ($items as $item) {
                $product = Product::find($item['product_id']);
                $variant = ProductVariant::find($item['product_variant_id'] ?? null);
                $qty     = (int) ($item['quantity'] ?? 1);
                $price   = (float) ($item['unit_price'] ?? 0);

                OrderItem::create([
                    'order_id'           => $order->id,
                    'product_id'         => $item['product_id'],
                    'product_variant_id' => $item['product_variant_id'] ?? null,
                    'product_name'       => $product?->name,
                    'variant_name'       => $variant?->name,
                    'sku'                => $variant?->sku,
                    'barcode'            => $variant?->barcode,
                    'quantity'           => $qty,
                    'unit_price'         => $price,
                    'line_total'         => $price * $qty,
                ]);
            }

            Notification::make()
                ->title('Order placed')
                ->body("Order {$order->order_number} created for {$customer->full_name}.")
                ->success()
                ->send();
        });
    }

    /**
     * Sum of every line in the items repeater.
     */
    protected function subtotalFrom(?array $items): float
    {
        return collect($items ?? [])->reduce(
            fn (float $carry, $item): float => $carry
                + ((float) ($item['unit_price'] ?? 0) * (int) ($item['quantity'] ?? 0)),
            0.0
        );
    }

    protected function shippingCostFor(?string $state): float
    {
        return (float) (config('address.shipping_costs')[$state] ?? 0);
    }

    protected function summaryHtml(Get $get): HtmlString
    {
        $subtotal = $this->subtotalFrom($get('items'));
        $shipping = (float) ($get('shipping_amount') ?? 0);
        $discount = (float) ($get('discount_amount') ?? 0);
        $total    = $subtotal + $shipping - $discount;

        $row = fn (string $label, float $value, bool $bold = false): string => sprintf(
            '<div class="flex justify-between %s"><span>%s</span><span>৳ %s</span></div>',
            $bold ? 'text-base font-semibold pt-1 mt-1 border-t' : 'text-sm',
            $label,
            number_format($value, 2)
        );

        return new HtmlString(
            '<div class="space-y-1 max-w-xs">'
            . $row('Subtotal', $subtotal)
            . $row('Shipping', $shipping)
            . $row('Discount', -$discount)
            . $row('Total', $total, true)
            . '</div>'
        );
    }

    protected function generateOrderNumber(): string
    {
        $lastOrder = Order::orderBy('id', 'desc')->first();
        $nextId    = $lastOrder ? $lastOrder->id + 1 : 1;

        return 'ORD'.str_pad((string) $nextId, 6, '0', STR_PAD_LEFT).now()->format('His');
    }

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
                    ->url(fn (Order $record) => OrderResource::getUrl('view', ['record' => $record]))
                    ->color('primary'),
                TextColumn::make('channel')
                    ->label('Ch')
                    ->badge()
                    ->grow(false)
                    ->alignCenter()
                    ->formatStateUsing(fn (?string $state): string => $state ? strtoupper(mb_substr($state, 0, 1)) : '-')
                    ->tooltip(fn (?string $state): ?string => $state),
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
                Action::make('consignment')
                    ->label('Delivery')
                    ->icon('heroicon-o-truck')
                    ->color('info')
                    ->schema([
                        TextInput::make('consignment_id')
                            ->label('Steadfast consignment ID')
                            ->helperText('Add this once the order is handed over to Steadfast for delivery.')
                            ->maxLength(255)
                            ->default(fn (Order $record) => $record->consignment_id),
                        TextInput::make('tracking_token')
                            ->label('Steadfast tracking code')
                            ->helperText('Public tracking code for the customer tracking link.')
                            ->maxLength(255)
                            ->default(fn (Order $record) => $record->tracking_token),
                    ])
                    ->action(function (Order $record, array $data) {
                        $record->update([
                            'consignment_id' => $data['consignment_id'] ?: null,
                            'tracking_token' => $data['tracking_token'] ?: null,
                        ]);

                        Notification::make()
                            ->title('Delivery details saved')
                            ->body("Order {$record->order_number} tracking updated.")
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

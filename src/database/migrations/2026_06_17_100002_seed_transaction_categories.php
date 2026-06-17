<?php

use App\Models\TransactionCategory;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * @var array<int, string>
     */
    private array $categories = [
        'Website Order',
        'Facebook Order',
        'Daraz Order',
        'Whatsapp Order',
        'Material Purchase',
        'Packaging Material Purchase',
        'Gateway Conversion',
        'Website Operational Costs',
        'Employee Salary',
        'Freelancer Charges',
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach ($this->categories as $name) {
            TransactionCategory::firstOrCreate(['name' => $name]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        TransactionCategory::whereIn('name', $this->categories)->forceDelete();
    }
};

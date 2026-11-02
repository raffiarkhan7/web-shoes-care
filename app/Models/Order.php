<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'invoice_number', 'user_id', 'penjual_id', 'service_id', 'quantity', 'total_price',
    'pickup_address', 'pickup_date', 'pickup_time', 'pickup_method', 'notes',
    'payment_method', 'payment_status', 'status',
])]
class Order extends Model
{
    public const STATUS_PENDING = 'pending';

    public const STATUS_CONFIRMED = 'confirmed';

    public const STATUS_PICKED_UP = 'picked_up';

    public const STATUS_PROCESSING = 'processing';

    public const STATUS_READY = 'ready';

    public const STATUS_DELIVERING = 'delivering';

    public const STATUS_COMPLETED = 'completed';

    public const STATUS_CANCELLED = 'cancelled';

    public const STATUSES = [
        self::STATUS_PENDING => 'Menunggu Konfirmasi',
        self::STATUS_CONFIRMED => 'Dikonfirmasi',
        self::STATUS_PICKED_UP => 'Sepatu Dijemput',
        self::STATUS_PROCESSING => 'Sedang Dicuci',
        self::STATUS_READY => 'Siap Diantar',
        self::STATUS_DELIVERING => 'Dalam Pengantaran',
        self::STATUS_COMPLETED => 'Selesai',
        self::STATUS_CANCELLED => 'Dibatalkan',
    ];

    public const STATUS_BADGES = [
        self::STATUS_PENDING => 'bg-amber-100 text-amber-700 ring-amber-200',
        self::STATUS_CONFIRMED => 'bg-sky-100 text-sky-700 ring-sky-200',
        self::STATUS_PICKED_UP => 'bg-indigo-100 text-indigo-700 ring-indigo-200',
        self::STATUS_PROCESSING => 'bg-violet-100 text-violet-700 ring-violet-200',
        self::STATUS_READY => 'bg-emerald-100 text-emerald-700 ring-emerald-200',
        self::STATUS_DELIVERING => 'bg-cyan-100 text-cyan-700 ring-cyan-200',
        self::STATUS_COMPLETED => 'bg-green-100 text-green-700 ring-green-200',
        self::STATUS_CANCELLED => 'bg-rose-100 text-rose-700 ring-rose-200',
    ];

    public const PAYMENT_STATUSES = [
        'unpaid' => 'Belum Dibayar',
        'paid' => 'Lunas',
    ];

    public const PAYMENT_METHODS = [
        'cod' => 'Bayar di Tempat (COD)',
        'transfer' => 'Transfer Bank',
    ];

    public const PICKUP_METHODS = [
        'pickup' => 'Antar Jemput',
        'dropoff' => 'Antar Sendiri',
    ];

    protected function casts(): array
    {
        return [
            'pickup_date' => 'date',
            'quantity' => 'integer',
            'total_price' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function penjual(): BelongsTo
    {
        return $this->belongsTo(User::class, 'penjual_id');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? ucfirst($this->status);
    }

    public function statusBadge(): string
    {
        return self::STATUS_BADGES[$this->status] ?? 'bg-slate-100 text-slate-600 ring-slate-200';
    }

    public function paymentStatusLabel(): string
    {
        return self::PAYMENT_STATUSES[$this->payment_status] ?? ucfirst($this->payment_status);
    }

    public function paymentMethodLabel(): string
    {
        return self::PAYMENT_METHODS[$this->payment_method] ?? ucfirst($this->payment_method);
    }

    public function pickupMethodLabel(): string
    {
        return self::PICKUP_METHODS[$this->pickup_method] ?? ucfirst($this->pickup_method);
    }

    public function formattedTotal(): string
    {
        return 'Rp '.number_format($this->total_price, 0, ',', '.');
    }

    public function isCancellable(): bool
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_CONFIRMED], true);
    }
}

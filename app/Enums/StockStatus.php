<?php

namespace App\Enums;

enum StockStatus: string
{
    case NORMAL = 'normal';
    case LOW = 'low';
    case OUT_OF_STOCK = 'out_of_stock';

    public function label(): string
    {
        return match ($this) {
            self::NORMAL => 'Normal',
            self::LOW => 'Stok Menipis',
            self::OUT_OF_STOCK => 'Habis',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::NORMAL => 'emerald',
            self::LOW => 'amber',
            self::OUT_OF_STOCK => 'rose',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::NORMAL => 'check-circle',
            self::LOW => 'exclamation-triangle',
            self::OUT_OF_STOCK => 'x-circle',
        };
    }

    public static function fromQuantity(int $current, int $min): self
    {
        if ($current <= 0) {
            return self::OUT_OF_STOCK;
        }

        if ($current <= $min) {
            return self::LOW;
        }

        return self::NORMAL;
    }
}

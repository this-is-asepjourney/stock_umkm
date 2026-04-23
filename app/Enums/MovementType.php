<?php

namespace App\Enums;

enum MovementType: string
{
    case IN = 'IN';
    case OUT = 'OUT';
    case ADJUSTMENT = 'ADJUSTMENT';
    case OPNAME = 'OPNAME';

    public function label(): string
    {
        return match ($this) {
            self::IN => 'Masuk',
            self::OUT => 'Keluar',
            self::ADJUSTMENT => 'Penyesuaian',
            self::OPNAME => 'Stock Opname',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::IN => 'emerald',
            self::OUT => 'rose',
            self::ADJUSTMENT => 'amber',
            self::OPNAME => 'indigo',
        };
    }

    public static function options(): array
    {
        return array_map(
            fn (self $case) => ['value' => $case->value, 'label' => $case->label()],
            self::cases()
        );
    }
}

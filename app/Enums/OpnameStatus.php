<?php

namespace App\Enums;

enum OpnameStatus: string
{
    case DRAFT = 'draft';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::DRAFT => 'Draft',
            self::IN_PROGRESS => 'Sedang Dihitung',
            self::COMPLETED => 'Selesai',
            self::CANCELLED => 'Dibatalkan',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::DRAFT => 'slate',
            self::IN_PROGRESS => 'amber',
            self::COMPLETED => 'emerald',
            self::CANCELLED => 'rose',
        };
    }

    public function isEditable(): bool
    {
        return in_array($this, [self::DRAFT, self::IN_PROGRESS], true);
    }

    public static function options(): array
    {
        return array_map(
            fn (self $case) => ['value' => $case->value, 'label' => $case->label()],
            self::cases()
        );
    }
}

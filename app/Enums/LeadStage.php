<?php

namespace App\Enums;

enum LeadStage: string
{
    case New = 'new';
    case Contacted = 'contacted';
    case Qualified = 'qualified';
    case Proposal = 'proposal';
    case Closed = 'closed';

    public static function options(): array
    {
        return [
            self::New->value => 'New',
            self::Contacted->value => 'Contacted',
            self::Qualified->value => 'Qualified',
            self::Proposal->value => 'Proposal',
            self::Closed->value => 'Closed',
        ];
    }
}

<?php

namespace App\Enums;

enum SeoPageType: string
{
    case Static = 'static';
    case Service = 'service';
    case Project = 'project';
    case Blog = 'blog';

    public static function options(): array
    {
        return [
            self::Static->value => 'Static Page',
            self::Service->value => 'Service Page',
            self::Project->value => 'Project Page',
            self::Blog->value => 'Blog Page',
        ];
    }
}

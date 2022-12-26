<?php

declare(strict_types=1);

namespace App\Queries\Menu;

interface EloquentMenuQueryServiceInterface
{
    /**
     * @param integer $id
     * @return \App\Models\Menu
     */
    public function findById(int $id): \App\Models\Menu;
}

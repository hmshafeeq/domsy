<?php

declare(strict_types=1);

namespace App\Infrastructures\Repositories\Domain;

use App\Infrastructures\Models\Eloquent\Domain;

final class DomainRepository implements DomainRepositoryInterface
{
    public function save(Domain $domain): Domain
    {
        $domain->save();

        return $domain;
    }

    public function store(array $attributes): Domain
    {
        $domain = Domain::create($attributes);

        return $domain;
    }

    public function delete(Domain $domain): void
    {
        $domain->delete();
    }
}
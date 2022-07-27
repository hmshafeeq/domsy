<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\DNS;

use App\Http\Requests\Request;
use App\Rules\DomainOwner;

final class UpdateRequest extends Request
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'prefix' => 'nullable|string',
            'domain_id' => new DomainOwner(),
            'type_id' => 'required|integer', // TODO:: バリデーション
            'value' => 'nullable|string',
            'ttl' => 'nullable|integer',
            'priority' => 'nullable|integer',
        ];
    }

    /**
     * @return array
     */
    public function makeInput(): array
    {
        return [
            'prefix' => $this->prefix,
            'domain_id' => $this->domain_id,
            'type_id' => $this->type_id,
            'value' => $this->value,
            'ttl' => $this->ttl,
            'priority' => $this->priority,
        ];
    }
}
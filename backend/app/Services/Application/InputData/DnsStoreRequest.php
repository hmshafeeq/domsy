<?php

declare(strict_types=1);

namespace App\Services\Application\InputData;

use App\Models\Subdomain;

final class DnsStoreRequest
{
    private $subdomain;

    /**
     * @param \App\Http\Requests\Frontend\Dns\StoreRequest $storeRequest
     */
    public function __construct(
        \App\Http\Requests\Api\Dns\StoreRequest $storeRequest
    ) {
        $validated = $storeRequest->validated();

        if (!isset($storeRequest->prefix)) {
            $validated = array_merge($validated, [
                'prefix' => '',
            ]);
        }

        $this->subdomain = new Subdomain($validated);
    }

    /**
     * @return \App\Models\Subdomain
     */
    public function getInput(): \App\Models\Subdomain
    {
        return $this->subdomain;
    }
}

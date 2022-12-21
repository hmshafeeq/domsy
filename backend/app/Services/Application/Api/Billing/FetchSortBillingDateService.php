<?php

declare(strict_types=1);

namespace App\Services\Application\Api\Billing;

use App\Http\Resources\BillingResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

final class FetchSortBillingDateService
{
    private $billings;

    private const DEFAULT_TAKE = 5;

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Queries\Domain\Billing\EloquentBillingQueryServiceInterface $eloquentBillingQueryService
     */
    public function __construct(
        \Illuminate\Http\Request $request,
        \App\Queries\Domain\Billing\EloquentBillingQueryServiceInterface $eloquentBillingQueryService
    ) {
        $take = $request->take ?? self::DEFAULT_TAKE;

        $user = User::find(Auth::id());
        if ($user->isCompany()) {
            $userIds = $user->getMemberIds();
        } else {
            $userIds = [$user->id];
        }

        $this->billings = $eloquentBillingQueryService->getBillingsByUserIdsGreaterThanBillingDateOrderByBillingDate(
            $userIds,
            now()->startOfDay(),
            (int) $take
        );
    }

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getResponse(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return BillingResource::collection($this->billings);
    }
}

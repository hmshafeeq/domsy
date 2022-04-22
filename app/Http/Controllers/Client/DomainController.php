<?php

declare(strict_types=1);

namespace App\Http\Controllers\Client;

use App\Exceptions\Client\DomainExistsException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Client\Domain\StoreRequest;
use App\Http\Requests\Client\Domain\UpdateRequest;
use App\Infrastructures\Models\Eloquent\Domain;
use App\Infrastructures\Repositories\Domain\DomainRepositoryInterface;
use App\Services\Application\DomainStoreService;
use App\Services\Application\DomainUpdateService;

use Exception;

use Illuminate\Support\Facades\Auth;

class DomainController extends Controller
{
    protected $domainRepository;

    protected const INDEX_ROUTE = 'domain.index';

    public function __construct(DomainRepositoryInterface $domainRepository)
    {
        parent::__construct();

        $this->middleware('can:owner,domain')->except(['index', 'new','store']);

        $this->middleware(function ($request, $next) {
            $registrars = Auth::user()->registrars;

            view()->share([
                'registrarIds' => $registrars->pluck('name', 'id')->toArray(),
            ]);

            return $next($request);
        });

        $this->domainRepository = $domainRepository;
    }

    public function index()
    {
        // Todo: Pagination
        $domains = Auth::user()->domains;

        return view('client.domain.index', compact('domains'));
    }

    public function new()
    {
        return view('client.domain.new');
    }

    public function edit(Domain $domain)
    {
        return view('client.domain.edit', compact('domain'));
    }

    public function update(
        UpdateRequest $request,
        Domain $domain,
        DomainUpdateService $domainUpdateService
    ) {
        try {
            $domainUpdateService->handle(
                $domain,
                $request->name,
                $request->price,
                $request->registrar_id,
                $request->is_active,
                $request->is_transferred,
                $request->is_management_only,
                $request->purchased_at,
                $request->expired_at,
                $request->canceled_at,
            );
        } catch (Exception $e) {
            return $this->redirectWithFailingMessageByRoute(self::INDEX_ROUTE, 'Failing Update');
        }

        return $this->redirectWithGreetingMessageByRoute(self::INDEX_ROUTE, 'Update Success!!');
    }

    public function store(
        StoreRequest $request,
        DomainStoreService $domainStoreService
    ) {
        try {
            $domainStoreService->handle(
                $request->name,
                $request->price,
                $request->user_id,
                $request->registrar_id,
                $request->is_active,
                $request->is_transferred,
                $request->is_management_only,
                $request->purchased_at,
                $request->expired_at,
                $request->canceled_at,
            );
        } catch (DomainExistsException $e) {
            return $this->redirectWithFailingMessageByRoute(self::INDEX_ROUTE, $e->getMessage());
        } catch (Exception $e) {
            return $this->redirectWithFailingMessageByRoute(self::INDEX_ROUTE, 'Failing Create');
        }

        return $this->redirectWithGreetingMessageByRoute(self::INDEX_ROUTE, 'Create Success!!');
    }

    public function delete(Domain $domain)
    {
        $this->domainRepository->delete($domain);

        return $this->redirectWithGreetingMessageByRoute(self::INDEX_ROUTE, 'Delete Success!!');
    }
}

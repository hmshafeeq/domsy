<?php

declare(strict_types=1);

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\Domain\StoreRequest;
use App\Http\Requests\Client\Domain\UpdateRequest;
use App\Infrastructures\Models\Eloquent\Domain;
use App\Infrastructures\Repositories\Domain\DomainRepositoryInterface;
use App\Services\Application\DomainStoreService;

use Exception;

use Illuminate\Support\Facades\Auth;

class DomainController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:owner,domain')->except(['index', 'new','store']);

        $this->middleware(function ($request, $next) {
            view()->share([
                'greeting'=> session('greeting'),
                'failing'=> session('failing')
            ]);

            return $next($request);
        });
    }

    protected function redirectIndexWithGreeting(string $greetingMessage): \Illuminate\Http\RedirectResponse
    {
        return redirect()->route('domain.index')
        ->with('greeting', $greetingMessage);
    }

    protected function redirectIndexWithFailing(string $failingMessage): \Illuminate\Http\RedirectResponse
    {
        return redirect()->route('domain.index')
        ->with('failing', $failingMessage);
    }

    public function index()
    {
        return view('client.domain.index', [
            'domains' => Auth::user()->domains
        ]);
    }

    public function edit(Domain $domain)
    {
        return view('client.domain.edit', [
            'domain' => $domain
        ]);
    }

    public function update(
        UpdateRequest $request,
        Domain $domain,
        DomainRepositoryInterface $domainRepository
    ) {
        try {
            $attributes = $request->only([
                'name',
                'price',
                'is_active',
                'is_transferred',
                'is_management_only',
                'purchased_at',
                'expired_at',
                'canceled_at',
            ]);

            $domain->fill($attributes);

            $domainRepository->save($domain);
        } catch (Exception $e) {
            return $this->redirectIndexWithFailing('Update Failed!!');
        }

        return $this->redirectIndexWithGreeting('Update Success!!');
    }

    public function new()
    {
        return view('client.domain.new');
    }

    public function store(
        StoreRequest $request,
        DomainStoreService $domainStoreService
    ) {
        try {
            $attributes = $request->only([
                'name',
                'price',
                'user_id',
                'is_active',
                'is_transferred',
                'is_management_only',
                'purchased_at',
                'expired_at',
                'canceled_at',
            ]);

            $domainStoreService->handle($attributes);
        } catch (Exception $e) {
            return $this->redirectIndexWithFailing('Create Failed!!');
        }

        return $this->redirectIndexWithGreeting('Create Success!!');
    }

    public function delete(Domain $domain)
    {
        try {
            $this->domainRepository->delete($domain);
        } catch (Exception $e) {
            return $this->redirectIndexWithFailing('Delete Failed!!');
        }

        return $this->redirectIndexWithGreeting('Delete Success!!');
    }
}
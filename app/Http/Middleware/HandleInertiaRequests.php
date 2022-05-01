<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Spatie\Permission\Models\Role;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'auth' => function () use ($request): ?array {
                $user = $request->user();
                $user?->load(['roles']);

                return [
                    'user' => $user,
                    'photo' => asset('images/user-no-photo.jpg'),
                ];
            },
            'flash' => fn (Request $request): ?array => $request->session()->get('flash'),
            'report' => fn (Request $request): array|object => $request->session()->get('report') ?? (object) [],
            'breadcrumbs' => fn (Request $request): array => $request->route()->breadcrumbs()->jsonSerialize(),
        ]);
    }
}

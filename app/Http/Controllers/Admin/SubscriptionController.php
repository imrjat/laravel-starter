<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function subscribe(Request $request)
    {
        $type = match ($request->type) {
            'monthly' => config('services.ls.monthly'),
            'annually' => config('services.ls.annually'),
            default => null
        };

        if ($type === null) {
            return redirect(route('admin.billing.subscription'));
        }

        return $request->user()->tenant->subscribe($type);
    }
}

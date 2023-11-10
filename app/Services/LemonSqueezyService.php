<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class LemonSqueezyService
{
    protected $baseUrl = 'https://api.lemonsqueezy.com/v1/';
    protected $apiKey;
    protected $productId;

    public function __construct()
    {
        $this->apiKey = config('services.lemonsqueezy.api_key');
        $this->productId = config('services.lemonsqueezy.product_id');
    }

    public function createSubscription($data)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Accept' => 'application/json',
        ])->post($this->baseUrl . 'checkouts', $data);

        return $response->json();
    }
}
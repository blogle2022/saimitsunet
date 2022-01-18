<?php

namespace App\Services;

use App\Services\Model;

require_once __DIR__ . '/../../bootstrap/app.php';

/**
 * Stripe上の商品を処理するクラス
 */
class Product
{
    private $stripe;

    public $allProducts;
    public $subscriptions;
    public $productsWithStatus;

    /**
     * コンストラクタ
     *
     * @param string $productId 商品ID
     */
    public function __construct(string $apikey)
    {
        $this->stripe = new \Stripe\StripeClient($apikey);
    }

    /**
     * プライスリストをプロダクトごとに分けて配列化
     *
     * @return array $productPrices Porduct IDをキーにした配列
     */
    public function fetchAllProducts()
    {
        $products = $this->stripe->products->all(['active' => true])->data;
        $productsIncPlans = array_map(function ($product) {
            $plans = $this->stripe->plans->all([
                'product' => $product->id
            ])->data;

            $plans = json_decode(json_encode($plans, JSON_UNESCAPED_UNICODE), true);
            $productIncPlans = [
                'id' => $product->id,
                'name' => $product->name,
                'object' => $product->object,
                'active' => $product->active,
                'plans' => $plans,
            ];
            return $productIncPlans;
        }, $products);

        $this->allProducts = $productsIncPlans;
        return $productsIncPlans;
    }

    /**
     * カスタマーごとのサブスクリプションを取得しリスト化する
     *
     * @param string $customer カスタマーID
     *
     * @return array $subscriptions カスタマーのサブスクリプションリスト
     */
    public function fetchCustomerSubscriptions(?string $customer)
    {
        $this->subscriptions = [];

        if (!$customer) {

            return $this->subscriptions;
        }

        try {
            $subscriptionList = $this->stripe->subscriptions->all([
                'customer' => $customer,
                'status' => 'active',
            ])->data;
        } catch (\Throwable $th) {
            return $this->subscriptions;
        }

        $subscriptions = array_map(function ($subs) {
            $lastItem = pop($subs->items->data);
            $subscription = [
                'id' => $subs['id'],
                'period_end' => $subs['current_period_end'],
                'product' => $lastItem->price->product,
            ];

            return $subscription;
        }, $subscriptionList);

        $this->subscriptions = $subscriptions;
        return $subscriptions;
    }

    public function setProductStatus()
    {
        if ($this->allProducts) {
            $subscriptions = $this->subscriptions;
            $productsWithStatus = array_map(function ($product) use ($subscriptions) {
                $product['subscribed'] = false;
                foreach ($subscriptions as $subs) {
                    $product['subscribed'] = ($subs['product'] === $product['id']) || $product['subscribed'];
                }


                $productsConfig = config('stripe.products');
                $product['uri'] = $productsConfig[$product['id']]['uri'];

                return $product;
            }, $this->allProducts);

            $this->productsWithStatus = $productsWithStatus;
            return $productsWithStatus;
        } else {
            return false;
        }
    }

    public function getProducts(?string $customer = '')
    {
        $this->fetchAllProducts();
        $this->fetchCustomerSubscriptions($customer);
        return $this->setProductStatus();
    }
}

<?php

namespace Printful;

use Printful\Structures\Catalog\Product;
use Printful\Structures\Catalog\ProductList;
use Printful\Structures\Catalog\ProductVariant;
use Printful\Structures\Catalog\ProductVariantList;

class PrintfulCatalog
{
    /**
     * @var PrintfulApiClient
     */
    private $printfulClient;

    /**
     * @param PrintfulApiClient $printfulClient
     */
    public function __construct(PrintfulApiClient $printfulClient)
    {
        $this->printfulClient = $printfulClient;
    }

    /**
     * Retrieve product list
     * @return Product[]
     */
    public function getProducts()
    {
        $raw = $this->printfulClient->get('products');
        $re = [];

        foreach ($raw as $v) {
            $country = Product::fromArray($v);

            $re[] = $country;
        }

        return $re;
    }

	/**
	 * @param int $offset
	 * @param int $limit - Number of items per page (max 100)
	 * @return ProductList
	 */
	public function getList($offset = 0, $limit = 10)
	{
		$params = [
			'offset' => $offset,
			'limit' => $limit,
		];
		$rawProducts = $this->printfulClient->get('products', $params);
		$total = $this->printfulClient->getItemCount();

		return new ProductList($rawProducts, $total, $offset);
	}

	/**
	 * @return ProductVariant
	 */
	public function getProductVariant($id = 4025)
	{
		$response = $this->printfulClient->get('products/variant' . '/' . $id);
//		$result = SyncProductRequestResponse::fromArray($response);
		$result = ProductVariant::fromArray($response['variant']);

		return $result;
	}

	/**
	 * @param int $id
	 * @return ProductVariantList
	 * @throws Exceptions\PrintfulApiException
	 * @throws Exceptions\PrintfulException
	 */
	public function getProductVariantList($id = 71)
	{
		$response = $this->printfulClient->get('products' . '/' . $id);
		return new ProductVariantList($response['product'], $response['variants'], count($response['variants']));
	}
}

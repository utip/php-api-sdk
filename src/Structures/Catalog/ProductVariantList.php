<?php

namespace Printful\Structures\Catalog;

class ProductVariantList
{
    /**
     * @var int
     */
    public $total;

	/**
	 * @var Product
	 */
    public $product;

    /**
     * @var array
     */
    public $variants = [];

	/**
	 * @param array $rawProduct
	 * @param array $rawVariants
	 * @param int $total
	 */
    public function __construct(array $rawProduct, array $rawVariants, $total)
    {
        $this->total = $total;

        $this->product = Product::fromArray($rawProduct);

        foreach ($rawVariants as $v) {
            $this->variants[] = ProductVariant::fromArray($v);
        }
    }
}
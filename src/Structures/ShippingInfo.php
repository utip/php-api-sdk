<?php

namespace Printful\Structures;

class ShippingInfo extends BaseItem
{
    /**
     * @var string
     */
    public $id;

	/**
	 * @var string
	 */
	public $name;

    /**
     * @var string
     */
    public $rate;

	/**
	 * @var string
	 */
	public $currency;

    /**
     * @var int
     */
    public $minDeliveryDays;

	/**
	 * @var int
	 */
	public $maxDeliveryDays;

    /**
     * @param array $raw
     * @return ShippingInfo
     */
    public static function fromArray(array $raw)
    {
        $item = new ShippingInfo();

		$item->id = (string)$raw['id'];
		$item->name = (string)$raw['name'];
        $item->rate = (string)$raw['rate'];
		$item->currency = (string)$raw['currency'];
        $item->minDeliveryDays = (int)$raw['minDeliveryDays'];
		$item->maxDeliveryDays = (int)$raw['maxDeliveryDays'];

        return $item;
    }
}

<?php

namespace Printful;

use Printful\Structures\CountryItem;
use Printful\Structures\ShippingInfo;
use Printful\Structures\StateItem;
use Printful\Structures\TaxRateItem;

class PrintfulShippingRates
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
	 * Get tax rate for given address
	 * @param string $address1
	 * @param string $countryCode ISO country code
	 * @param string $stateCode
	 * @param string $city
	 * @param string $zipCode
	 * @param array $items
	 * @return ShippingInfo[]
	 * @throws Exceptions\PrintfulApiException
	 * @throws Exceptions\PrintfulException
	 */
    public function getShippingRate($address1, $countryCode, $stateCode, $city, $zipCode, $items)
    {
        $recipient = [
        	'address1' => $address1,
            'country_code' => $countryCode,
            'state_code' => $stateCode,
            'city' => $city,
            'zip' => (string)$zipCode,
        ];


        $requestData = [
        	'recipient' => $recipient,
			'items' => $items,
		];

        $raw = $this->printfulClient->post('shipping/rates', $requestData);

        /** @var ShippingInfo[] $shippingInfoArray */
        $shippingInfoArray = [];

        foreach ($raw as $shippingInfo) {
			$shippingInfoArray[] = ShippingInfo::fromArray($shippingInfo);
		}

        return $shippingInfoArray;
    }

}

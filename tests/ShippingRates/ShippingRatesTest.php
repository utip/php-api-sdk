<?php

use Printful\Structures\CountryItem;
use Printful\Tests\TestCase;
use Printful\PrintfulShippingRates;
use Printful\Structures\ShippingInfo;

class ShippingRatesTest extends TestCase
{
	/**
	 * @param array $recipient
	 * @param array $items
	 * @throws \Printful\Exceptions\PrintfulApiException
	 * @throws \Printful\Exceptions\PrintfulException
	 * @dataProvider addressDataProvider
	 */
    public function testShippingRatesCalculation(array $recipient, array $items)
    {
        $rates = new PrintfulShippingRates($this->api);
		$address1 = $recipient['address1'];
        $countryCode = $recipient['country_code'];
        $stateCode = $recipient['state_code'];
        $city = $recipient['city'];
        $zipCode = $recipient['zip'];

        $shippingRates = $rates->getShippingRate($address1, $countryCode, $stateCode, $city, $zipCode, $items);
        foreach ($shippingRates as $shippingRate) {
			self::assertInstanceOf(ShippingInfo::class, $shippingRate);
		}
    }


    /**
     * Valid address data
     * First two addresses require tax calculation
     * @return array
     */
    public function addressDataProvider()
    {
        return [
            [
                [
                	'address1' => '1098 Belvedere Ln',
                    'country_code' => 'US',
                    'state_code' => 'CA',
                    'city' => 'San Jose',
                    'zip' => '95129',
                ],
                [
					[
						'variant_id' => '5522',
						'quantity' => 1,
					],
				],
            ],
        ];
    }
}

<?php

namespace tests\ProductsApi;

use Printful\Factories\Sync\ParameterFactory;
use Printful\Structures\Sync\Requests\SyncVariantRequestFile;
use Printful\Structures\Sync\SyncProductCreationParameters;
use Printful\Tests\TestCase;

class BuildTest extends TestCase
{
    /**
     * Tests SyncProductCreationParameters build from array
     */
    public function testProductCreationParamsBuild()
    {
        $data = $this->getPostProductData();

        $params = SyncProductCreationParameters::fromArray($data);

        $product = $params->getProduct();
        $variants = $params->getVariants();

        // assert product fields
        $this->assertEquals($product->name, $data['sync_product']['name']);
        $this->assertEquals($product->thumbnail, $data['sync_product']['thumbnail']);

        // assert variants
        $this->assertEquals(count($variants), count($data['sync_variants']));
        foreach ($variants as $variant) {
            $found = false;

            foreach ($data['sync_variants'] as $dataVariant) {
                if ($variant->variantId == $dataVariant['variant_id']) {
                    $this->assertEquals($dataVariant['retail_price'], $variant->retailPrice);

                    $files = $variant->getFiles();
                    $this->assertEquals(count($files), count($dataVariant['files']));
                    $this->compareFiles($files, $dataVariant['files']);

                    $options = $variant->getOptions();
                    $this->assertEquals(count($options), count($dataVariant['options']));

                    $found = true;
                    break;
                }
            }
            $this->assertTrue($found);
        }
    }

    /**
     * @param SyncVariantRequestFile[] $files
     * @param array $dataFiles
     */
    private function compareFiles($files, $dataFiles)
    {
        foreach($files as $file){
            $found = false;

            foreach ($dataFiles as $dataFile){
                $type = isset($dataFile['type']) ? $dataFile['type'] : SyncVariantRequestFile::DEFAULT_TYPE;
                if($type == $file->type){
                    $this->assertEquals($dataFile['url'], $file->url);

                    $found = true;
                    break;
                }
            }
            $this->assertTrue($found);
        }
    }

    /**
     * Tests building Products POST params
     *
     * @throws \Printful\Exceptions\PrintfulSdkException
     */
    public function testBuildProductPostParams()
    {
       $data = $this->getPostProductData();
       $creationParams = SyncProductCreationParameters::fromArray($data);

       $postParams = ParameterFactory::buildSyncProductPostParams($creationParams);

       $this->assertEquals($postParams['sync_product'], $data['sync_product']);
       $this->assertEquals(count($postParams['sync_variants']), count($data['sync_variants']));

       foreach ($postParams['sync_variants'] as $postSyncVariant){
           $found = false;
           foreach ($data['sync_variants'] as $dataSyncVariant){
               if($postSyncVariant['variant_id'] == $dataSyncVariant['variant_id']){

                   $this->assertEquals($postSyncVariant['retail_price'], $dataSyncVariant['retail_price']);
                   $this->assertEquals(count($postSyncVariant['files']), count($dataSyncVariant['files']));

                   // actual file array compare we skip heres

                   $found = true;
                   break;
               }
           }
           $this->assertTrue($found);
       }

    }

    /**
     * Returns POST product data in array format
     *
     * @return array
     */
    private function getPostProductData()
    {
        return [
            'sync_product' => [
                'name' => 'Test name',
                'thumbnail' => 'https://picsum.photos/200/300',
            ],
            'sync_variants' => [
                [
                    'retail_price' => 21.00,
                    'variant_id' => 4011,
                    'files' => [
                        [
                            'url' => 'https://picsum.photos/200/300',
                        ],
                        [
                            'type' => 'back',
                            'url' => 'https://picsum.photos/200/300',
                        ],
                    ],
                    'options' => [
                        [
                            'id' => 'embroidery_type',
                            'value' => 'flat',
                        ],
                        [
                            'id' => 'thread_colors',
                            'value' => '',
                        ],
                    ],
                ],
                [
                    'retail_price' => 21,
                    'variant_id' => 4012,
                    'files' => [
                        [
                            'url' => 'https://picsum.photos/200/300',
                        ],
                        [
                            'type' => 'back',
                            'url' => 'https://picsum.photos/200/300',
                        ],
                    ],
                    'options' => [
                        [
                            'id' => 'embroidery_type',
                            'value' => 'flat',
                        ],
                        [
                            'id' => 'thread_colors',
                            'value' => '',
                        ],
                    ],
                ],
            ],
        ];
    }

}

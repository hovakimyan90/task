<?php

namespace app\jobs;

use app\models\Import;
use app\models\StoreProduct;
use Yii;
use yii\base\BaseObject;
use yii\queue\JobInterface;

class ImportJob extends BaseObject implements JobInterface
{
    public $id;

    public function execute($queue)
    {
        try {
            $import = Import::find()->where(['id' => $this->id, 'status' => Import::STATUS_NEW])->one();
            if ($import) {
                $import->status = Import::STATUS_PROCESSING;
                $import->save(false);
                $content = [];

                if (($handle = fopen(Yii::getAlias('@app/web/') . $import->filename, "r")) !== FALSE) {
                    while (($data = fgetcsv($handle, null, ",", '"', '\\')) !== false) {
                        $content[] = $data;
                    }
                    fclose($handle);
                }


                $keys = [];
                foreach ($content[0] as $key => $head) {
                    $current = strtolower(trim($head));
                    if (in_array($current, [Import::UPC, Import::PRICE, Import::TITLE])) {
                        $keys[$current] = $key;
                    }
                }

                if (!array_key_exists(Import::UPC, $keys)) {
                    $import->status = Import::STATUS_FAILED;
                    $import->save(false);
                } else {
                    foreach ($content as $key => $row) {
                        if ($key == 0) {
                            continue;
                        }
                        if (isset($row[$keys[Import::UPC]])) {
                            $upc = $row[$keys[Import::UPC]];
                            $storeProduct = StoreProduct::find()->where(['upc' => $upc, 'store_id' => $import->store_id])->one();
                            if (!$storeProduct) {
                                $storeProduct = new StoreProduct();
                            }
                            $storeProduct->store_id = $import->store_id;
                            $storeProduct->upc = $upc;
                            $storeProduct->title = isset($row[$keys[Import::TITLE]]) ? $row[$keys[Import::TITLE]] : '';
                            $storeProduct->price = isset($row[$keys[Import::PRICE]]) ? $row[$keys[Import::PRICE]] : '';
                            if ($storeProduct->save()) {
                                $import->success_count += 1;
                                $import->save(false);
                            } else {
                                $import->failed_count += 1;
                                $import->save(false);
                            }

                            $import->status = Import::STATUS_DONE;
                            $import->save(false);

                        } else {
                            $import->failed_count += 1;
                            $import->save(false);
                        }
                    }
                }
            }
        } catch (\Throwable $ex) {
            var_dump($ex->getMessage(), $ex->getFile(), $ex->getLine());
        }


    }
}
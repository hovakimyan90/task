<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

/**
 * This is the model class for table "import".
 *
 * @property int $id
 * @property int|null $store_id
 * @property string|null $filename
 * @property int|null $status
 * @property int|null $success_count
 * @property int|null $failed_count
 */
class Import extends \yii\db\ActiveRecord
{
    const STATUS_NEW = 0;
    const STATUS_PROCESSING = 1;
    const STATUS_DONE = 2;
    const STATUS_FAILED = 3;

    const UPC = 'upc';
    const TITLE = 'title';
    const PRICE = 'price';

    public static $statusMap = [
        self::STATUS_NEW => 'New',
        self::STATUS_PROCESSING => 'Processing',
        self::STATUS_DONE => 'Done',
        self::STATUS_FAILED => 'Failed',
    ];


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'import';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['store_id', 'status', 'success_count', 'failed_count'], 'integer'],
            [['success_count', 'failed_count'], 'default', 'value' => 0],
            [['status'], 'default', 'value' => self::STATUS_NEW],
            ['filename', 'file',
                'extensions' => Yii::$app->params['import_file_extensions'],
                'checkExtensionByMimeType' => false,
                'maxSize' => Yii::$app->params['max_file_upload_size']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'store_id' => 'Store ID',
            'filename' => 'Filename',
            'status' => 'Status',
            'success_count' => 'Success Count',
            'failed_count' => 'Failed Count',
        ];
    }

    public function beforeSave($insert)
    {
        if ($this->filename instanceof UploadedFile) {
            $random = Yii::$app->security->generateRandomString(16);
            $filename = "csv/{$random}_{$this->filename->baseName}.{$this->filename->extension}";
            if (!is_dir(Yii::getAlias("@webroot") . '/csv')) {

                FileHelper::createDirectory(Yii::getAlias("@webroot") . '/csv', $mode = 0775, $recursive = true);

            }

            $this->filename->saveAs(Yii::getAlias("@webroot") . "/" . $filename);
            $this->filename = $filename;
        }
        return parent::beforeSave($insert);
    }

    public static function getStatus ($status)
    {
        return ArrayHelper::getValue(self::$statusMap, $status);
    }

    public function getStore()
    {
        return $this->hasOne(Store::className(),['id' => 'store_id']);
    }
}

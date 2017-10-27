<?php
/**
 * Created by PhpStorm.
 * User: Yarmaliuk Mikhail
 * Date: 30.06.2017
 * Time: 15:41
 */

namespace MP\TranslateDB;

use Yii;
use yii\console\controllers\MessageController;
use yii\db\ActiveRecord;

/**
 * Class    MPMessageController
 * @package MP\TranslateDB
 * @author  Yarmaliuk Mikhail
 * @version 1.0
 */
class MPMessageController extends MessageController
{
    /**
     * Message config file
     *
     * @var string
     */
    public $configFile = '';

    /**
     * Stored DB messages
     *
     * @var string
     */
    public $storedDbMessages = '';

    /**
     * @var array
     */
    public $tablesMessages = [];

    /**
     * @inheritdoc
     */
    public function actionExtract($configFile = NULL)
    {
        $configFile = $configFile ? : $this->configFile;

        $this->grabDbMessages();

        parent::actionExtract($configFile);

        unlink(Yii::getAlias($this->storedDbMessages));
    }

    /**
     * Grab messages from db
     *
     * @return void
     */
    private function grabDbMessages()
    {
        $messages = [];

        $grab_in_table_column = function ($className, array $columns) use (&$messages) {
            if (method_exists($className, 'tableName')) {
                /** @var ActiveRecord $className */
                $messages[] = "\n // TABLE: " . $className::tableName();

                foreach ($className::find()->each() as $model) {
                    foreach ($columns as $column) {
                        if ($model->hasAttribute($column) && !empty($model->getAttribute($column))) {
                            $string     = str_replace(['"'], ['\"'], $model->getAttribute($column));
                            $messages[] = "Yii::t('app', \"" . $string . "\");";
                        }
                    }
                }
            }
        };

        if (!empty($this->tablesMessages)) {
            foreach ($this->tablesMessages as $className => $columns) {
                $grab_in_table_column($className, $columns);
            }
        }

        file_put_contents(Yii::getAlias($this->storedDbMessages), "<?php \n" . implode("\n", $messages));
    }
}

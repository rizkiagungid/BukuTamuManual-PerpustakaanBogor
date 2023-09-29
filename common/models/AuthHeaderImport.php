<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use inlislite\gii\behaviors\TerminalBehavior;

/**
 * This is the base-model class for table "auth_header".
 *
 * @property integer $ID
 * @property integer $Worksheet_id
 * @property string $Auth_ID
 * @property string $MARC_LOC
 * @property string $ISTILAH_DIGUNAKAN
 * @property string $ISTILAH_TDK_DIGUNAKAN
 * @property string $ISTILAH_TERKAIT
 * @property string $TANGGALPEMBAHASAN
 * @property integer $QUARANTINEDBY
 * @property string $QUARANTINEDDATE
 * @property string $QUARANTINEDTERMINAL
 * @property integer $CreateBy
 * @property string $CreateDate
 * @property string $CreateTerminal
 * @property integer $UpdateBy
 * @property string $UpdateDate
 * @property string $UpdateTerminal
 *
 * @property \common\models\AuthData[] $authDatas
 * @property \common\models\Users $createBy
 * @property \common\models\Users $updateBy
 * @property \common\models\Worksheets $worksheet
 */
class AuthHeaderImport extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'auth_header';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Worksheet_id', 'Auth_ID'], 'required'],
            [['Worksheet_id', 'QUARANTINEDBY', 'CreateBy', 'UpdateBy'], 'integer'],
            [['MARC_LOC', 'ISTILAH_DIGUNAKAN', 'ISTILAH_TDK_DIGUNAKAN', 'ISTILAH_TERKAIT'], 'string'],
            [['QUARANTINEDDATE', 'CreateDate', 'UpdateDate'], 'safe'],
            [['Auth_ID'], 'string', 'max' => 20],
            [['TANGGALPEMBAHASAN'], 'string', 'max' => 45],
            [['QUARANTINEDTERMINAL', 'CreateTerminal', 'UpdateTerminal'], 'string', 'max' => 100],
            [['Auth_ID'], 'unique'],
            [['CreateBy'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['CreateBy' => 'ID']],
            [['UpdateBy'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['UpdateBy' => 'ID']],
            [['Worksheet_id'], 'exist', 'skipOnError' => true, 'targetClass' => Worksheets::className(), 'targetAttribute' => ['Worksheet_id' => 'ID']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => Yii::t('app', 'ID'),
            'Worksheet_id' => Yii::t('app', 'Worksheet ID'),
            'Auth_ID' => Yii::t('app', 'Auth  ID'),
            'MARC_LOC' => Yii::t('app', 'Marc  Loc'),
            'ISTILAH_DIGUNAKAN' => Yii::t('app', 'Istilah  Digunakan'),
            'ISTILAH_TDK_DIGUNAKAN' => Yii::t('app', 'Istilah  Tdk  Digunakan'),
            'ISTILAH_TERKAIT' => Yii::t('app', 'Istilah  Terkait'),
            'TANGGALPEMBAHASAN' => Yii::t('app', 'Tanggalpembahasan'),
            'QUARANTINEDBY' => Yii::t('app', 'Quarantinedby'),
            'QUARANTINEDDATE' => Yii::t('app', 'Quarantineddate'),
            'QUARANTINEDTERMINAL' => Yii::t('app', 'Quarantinedterminal'),
            'CreateBy' => Yii::t('app', 'Create By'),
            'CreateDate' => Yii::t('app', 'Create Date'),
            'CreateTerminal' => Yii::t('app', 'Create Terminal'),
            'UpdateBy' => Yii::t('app', 'Update By'),
            'UpdateDate' => Yii::t('app', 'Update Date'),
            'UpdateTerminal' => Yii::t('app', 'Update Terminal'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthDatas()
    {
        return $this->hasMany(\common\models\AuthData::className(), ['Auth_Header_ID' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreateBy()
    {
        return $this->hasOne(\common\models\Users::className(), ['ID' => 'CreateBy']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdateBy()
    {
        return $this->hasOne(\common\models\Users::className(), ['ID' => 'UpdateBy']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWorksheet()
    {
        return $this->hasOne(\common\models\Worksheets::className(), ['ID' => 'Worksheet_id']);
    }


/**
     * @inheritdoc
     * @return type array
     */ 
    public function behaviors()
    {
        $getIP = getHostByName(getHostName());
        return [
        \common\widgets\nhkey\ActiveRecordHistoryBehavior::className(),
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'CreateDate',
                'updatedAtAttribute' => 'UpdateDate',
                'value' => new \yii\db\Expression('NOW()'),
            ],
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'CreateBy',
                'updatedByAttribute' => 'UpdateBy',
            ],
            [
                'class' => TerminalBehavior::className(),
                'createdTerminalAttribute' => 'CreateTerminal',
                'updatedTerminalAttribute' => 'UpdateTerminal',
                // 'value' => \Yii::$app->request->userIP,
                'value' => $getIP,
            ],
        ];
    }


    
}

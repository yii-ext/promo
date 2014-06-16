<?php
namespace yii_ext\promo\models;

/**
 * This is the model class for table "PromoCode".
 *
 * The followings are the available columns in table 'PromoCode':
 * @property integer $id
 * @property string  $title
 * @property string  $code
 * @property string  $discountType
 * @property string  $discountValue
 * @property string  $startDate
 * @property string  $endDate
 */
use yii_ext\promo\models\enums\PromoCodeType;

/**
 * Class PromoCodeModel
 * @package yii-ext\promo\models
 * @property integer $id
 * @property string  $title
 * @property string  $code
 * @property integer $discountType
 * @property double  $discountValue
 * @property string  $startDate
 * @property string  $endDate
 * @property integer $applyTo1Month
 * @property integer $applyTo3Month
 * @property integer $applyTo6Month
 * @method PromoCodeModel|CActiveRecord find()
 * @method PromoCodeModel|CActiveRecord findByPK()
 * @method PromoCodeModel|CActiveRecord findByAttributes()
 */
class PromoCodeModel extends \CActiveRecord
{

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'PromoCode';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('title, code, discountType, discountValue', 'required'),
            array('discountType', 'numerical', 'integerOnly' => true),
            array('discountValue, applyTo1Month, applyTo3Month, applyTo6Month, ', 'numerical'),
            array('title', 'length', 'max' => 256),
            array('code', 'unique'),
            array('applyTo1Month, applyTo3Month, applyTo6Month', 'packageValidator'),
            array('discountValue', 'compare', 'compareValue' => 0, 'operator' => '>'),
            array('discountValue', 'compare', 'compareValue' => 100, 'operator' => '<', 'on' => 'percentage', 'message' => 'Value must be less than "100" if Discount Type is "Percentage".'),
            array('endDate', 'compare', 'compareAttribute' => 'startDate', 'operator' => '>', 'message' => 'End Date must be after Start Date'),
            array('code, discountType, discountValue', 'length', 'max' => 100),
            array('startDate, endDate', 'safe'),
        );
    }

    /**
     * @param $attribute_name
     * @param $params
     *
     * @return bool
     */
    public function packageValidator($attribute_name, $params)
    {
        if (empty($this->applyTo1Month)
            && empty($this->applyTo3Month)
            && empty($this->applyTo6Month)
        ) {
            $this->addError('apply', \Yii::t('user', 'At least 1 of packages should be selected'));
            return false;
        }

        return true;
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array();
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'title' => 'Title',
            'code' => 'Code',
            'discountType' => 'Discount Type',
            'discountValue' => 'Discount Value',
            'startDate' => 'Start Date',
            'endDate' => 'End Date',
        );
    }


    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     *
     * @param string $className active record class name.
     *
     * @return PromoCodeModel the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * Additional validations
     *
     * @author Valeriy Zavolodko <vals2004@gmail.com>
     * @author Vadim Bulochnik <bulochnik@zfort.net>
     *
     * @return boolean
     */
    public function validate($attributes = null, $clearErrors = true)
    {
        $result = parent::validate($attributes, $clearErrors);

        if ($result) {
            if (strtotime($this->startDate) > strtotime($this->endDate)) {
                $this->addError('startDate', 'Start Date must be before End Date');
                $this->addError('endDate', 'End Date must be after Start Date');
                $result = false;
            }
        }

        return $result;
    }

    /**
     * @static
     *
     * @param $price
     * @param $code
     * @param $isAjax
     *
     * @return int
     */
    public static function calculateDiscount($price, $code, $isAjax = false)
    {
        $code = self::model()->findByAttributes(array('code' => $code));
        if (!$code) {
            return 0;
        }
    }

    /**
     * Calculate discount amount for Order
     *
     * @author Dmitry Semenov <disemx@gmail.com>
     *
     * @param float
     * @param array
     *
     * @return float
     */
    public function getDiscount($code, $plan = null)
    {
        $message = '';
        $discount = 0;
        if (empty($code)) {
            return array(
                'discount' => $discount,
                'message' => 'No promotional code provided.',
            );
        }
        $plan = \MembershipPlanModel::model()->findByPk($plan);
        $plan = \MembershipPlanModel::recalculateSalesPrices(array($plan));
        $plan = $plan[0];
        if (!$plan) {
            return array(
                'success' => false,
                'message' => 'Billing plan not found.'
            );
        }

        $criteria = new \CDbCriteria();
        $criteria->addColumnCondition(array('t.code' => $code));

        $coupon = self::model()->find($criteria);
        if (!$coupon) {
            return array(
                'success' => false,
                'message' => 'Coupon not found.'
            );
        }
        if (!$coupon->canUse($plan->id)) {
            return array(
                'success' => false,
                'message' => 'Can`t use this coupon with selected billing plan.'
            );
        }
        if (!$coupon->isInTime()) {
            return array(
                'success' => false,
                'message' => 'This coupon is out of date.'
            );
        }
        switch ($coupon->discountType) {
            case PromoCodeType::DISCOUNT_TYPE_DOLLAR_VALUE:
                return array(
                    'success' => true,
                    'message' => "Your discount is $" . $coupon->discountValue . " and total cost is $" . round((($coupon->discountValue < $plan->price) ? $plan->price - $coupon->discountValue : 0), 2),
                    'discountedPrice' => round((($coupon->discountValue < $plan->price) ? $plan->price - $coupon->discountValue : 0), 2)
                );
                break;
            case PromoCodeType::DISCOUNT_TYPE_PERCENTAGE:
                $discountedPrice = $plan->price - (($coupon->discountValue / 100) * $plan->price);
                if ($coupon->discountValue >= 100) {
                    $discountedPrice = 0;
                }
                return array(
                    'success' => true,
                    'message' => "Your discount is " . $coupon->discountValue . "% and total cost is $" . (round($discountedPrice, 2)),
                    'discountedPrice' => (round($discountedPrice, 2))
                );
                break;
        }
    }

    /**
     * Verify to Coupon usage for Order ???
     *
     * @author Valeriy Zavolodko <vals2004@gmail.com>
     * @author Dmitry Semenov <disemx@gmail.com>
     *
     * @param $plan
     *
     * @return boolean
     */
    public function canUse($plan)
    {
        $can = false;
        switch ($plan) {
            case 1:
                $can = ($this->applyTo1Month == 1);
                break;
            case 2:
                $can = ($this->applyTo3Month == 1);
                break;
            case 3:
                $can = ($this->applyTo6Month == 1);
                break;
            default:
                break;
        }
        return $can;
    }

    /**
     * Check if Coupon available by time limit
     *
     * @author Valeriy Zavolodko <vals2004@gmail.com>
     * @author Dmitry Semenov <disemx@gmail.com>
     *
     * @return boolean
     */
    public function isInTime()
    {
        $currentDateStamp = strtotime(date('Y-m-d H:m'));
        return strtotime($this->startDate) <= $currentDateStamp && $currentDateStamp <= strtotime($this->endDate);
    }

    /**
     * Find Coupon by Code provided
     *
     * @author Valeriy Zavolodko <vals2004@gmail.com>
     * @author Dmitry Semenov <disemx@gmail.com>
     *
     * @param $code
     *
     * @return Coupon or null
     */
    public static function findByCode($code)
    {
        return self::model()->findByAttributes(array('code' => $code));
    }

}

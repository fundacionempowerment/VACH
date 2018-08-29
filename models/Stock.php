<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\db\Query;

class Stock extends ActiveRecord {

    const STATUS_INVALID = 'invalid';
    const STATUS_VALID = 'valid';
    const STATUS_ERROR = 'error';
    const STATUS_CONSUMED = 'consumed';

    public function init() {
        parent::init();
    }

    /**
     * @return array the validation rules.
     */
    public function rules() {
        return [
            [['coach_id', 'product_id', 'price', 'status', 'created_stamp', 'creator_id'], 'required'],
        ];
    }

    public function attributeLabels() {
        return [
            'coach_id' => Yii::t('team', 'Coach'),
            'product_id' => Yii::t('stock', 'Product'),
            'quantity' => Yii::t('stock', 'Quantity'),
            'price' => Yii::t('stock', 'Price'),
            'total' => Yii::t('stock', 'Total'),
            'status' => Yii::t('app', 'Status'),
            'statusName' => Yii::t('app', 'Status'),
            'stamp' => Yii::t('app', 'Date and Time'),
            'payments' => Yii::t('payment', 'Payments'),
            'team' => Yii::t('team', 'Team'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
        ];
    }

    public function beforeValidate() {
        if (!isset($this->coach_id)) {
            $this->coach_id = Yii::$app->user->id;
        }

        return parent::beforeValidate();
    }

    public static function browseAvailable() {
        return static::browse()->andWhere(['stock.status' => self::STATUS_VALID]);
    }

    public static function browseOthers() {
        return static::browse()->andWhere(['not', ['stock.status' => self::STATUS_VALID]]);
    }

    public static function browse() {
        return static::adminBrowse()->andWhere([
            'stock.coach_id' => Yii::$app->user->id,
        ]);
    }

    public static function adminBrowseAvailable() {
        return static::adminBrowse()->andWhere(['stock.status' => self::STATUS_VALID]);
    }

    public static function adminBrowseOthers() {
        return static::adminBrowse()->andWhere(['<>', 'stock.status', self::STATUS_VALID])->orderBy('consumed_stamp DESC');
    }

    public static function adminBrowse($coachId = null) {
        $query = (new Query())
            ->select([
                'stock.coach_id',
                "CONCAT(coach.name, ' ', coach.surname) as coach_name",
                "CONCAT(creator.name, ' ', creator.surname) as creator_name",
                'product_id', 'price', 'stock.status as stock_status',
                'created_stamp',
                'stock.creator_id',
                'payment_id',
                'payment.status as payment_status',
                'payment.uuid as payment_uuid',
                '(SELECT MAX(`stamp`) FROM transaction WHERE transaction.payment_id = payment.id) as payment_date',
                'consumed_stamp', 'consumer_id', 'stock.team_id',
                'team.name as team_name',
                'company.name as company_name',
                new Expression('COUNT(stock.id) as quantity'),
                new Expression('SUM(stock.price) as amount'),
                new Expression('AVG(payment.rate) as rate'),
                new Expression('SUM(stock.price * payment.rate) as localAmount'),
            ])
            ->from('stock')
            ->innerJoin('user as coach', 'coach.id = stock.coach_id')
            ->innerJoin('user as creator', 'creator.id = stock.creator_id')
            ->leftJoin('team', 'team.id = stock.team_id')
            ->leftJoin('company', 'company.id = team.company_id')
            ->innerJoin('payment', 'payment.id = stock.payment_id')
            ->groupBy(['coach_id', 'product_id', 'price', 'stock.status', 'created_stamp', 'creator_id', 'payment_id', 'consumed_stamp', 'consumer_id', 'team_id'])
            ->orderBy('created_stamp DESC, consumed_stamp DESC');

        $query->filterWhere(['stock.coach_id' => $coachId]);

        return $query;
    }

    public function getCoach() {
        return $this->hasOne(User::className(), ['id' => 'coach_id']);
    }

    public function getCreator() {
        return $this->hasOne(User::className(), ['id' => 'creator_id']);
    }

    public function getPayments() {
        return $this->hasMany(Payment::className(), ['stock_id' => 'id']);
    }

    public function getProduct() {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

    public function getTeam() {
        return $this->hasOne(Team::className(), ['id' => 'team_id']);
    }

    public static function getStatusList() {
        $list = [
            self::STATUS_INVALID => Yii::t('app', self::STATUS_INVALID),
            self::STATUS_VALID => Yii::t('app', self::STATUS_VALID),
            self::STATUS_ERROR => Yii::t('app', self::STATUS_ERROR),
            self::STATUS_CONSUMED => Yii::t('stock', self::STATUS_CONSUMED),
        ];

        return $list;
    }

    public function getStatusName() {
        return self::getStatusList()[$this->status];
    }

    public static function getStock($product_id, $user_id = null) {
        $query = new Query();

        $balance = $query->select(new Expression('count(id) as balance'))
            ->from('stock')
            ->where([
                'coach_id' => ($user_id ? $user_id : Yii::$app->user->id),
                'product_id' => $product_id,
                'status' => self::STATUS_VALID,
            ])
            ->one();

        if ($balance && $balance['balance']) {
            return $balance['balance'];
        }
        return 0;
    }

    public static function saveBuyModel($model) {
        $success = true;
        $product = Product::findOne(['id' => $model->product_id]);
        $created_stamp = date('Y-m-d H:i:s');

        $payment = new Payment();
        $payment->coach_id = Yii::$app->user->id;
        $payment->concept = $model->quantity . ' ' . $product->name;
        $payment->currency = 'USD';
        $payment->amount = $model->quantity * $model->price;
        $payment->rate = 0;
        $payment->status = Payment::STATUS_PENDING;
        $payment->is_manual = false;
        $payment->part_distribution = 50;
        if (!$payment->save()) {
            \app\controllers\SiteController::FlashErrors($payment);
            $success = false;
        }

        for ($i = 1; $i <= $model->quantity; $i++) {
            $stock = new Stock();
            $stock->coach_id = Yii::$app->user->id;
            $stock->creator_id = Yii::$app->user->id;
            $stock->product_id = $model->product_id;
            $stock->payment_id = $payment->id;
            $stock->price = $product->price;
            $stock->status = Stock::STATUS_INVALID;
            $stock->created_stamp = $created_stamp;
            if (!$stock->save()) {
                \app\controllers\SiteController::FlashErrors($stock);
                $success = false;
            }
        }

        $model->description = 'VACH ' . $payment->concept;
        $model->amount = $payment->amount;
        $model->referenceCode = $payment->uuid;

        return $success ? $payment : null;
    }

    public static function saveAddModel($model) {
        $success = true;
        $product = Product::findOne(['id' => $model->product_id]);
        $created_stamp = date('Y-m-d H:i:s');

        $payment = new Payment();
        $payment->coach_id = $model->coach_id;
        $payment->creator_id = Yii::$app->user->id;
        $payment->concept = $model->quantity . ' ' . $product->name;
        $payment->currency = 'USD';
        $payment->amount = $model->quantity * $model->price;
        $payment->rate = \app\models\Currency::lastValue();
        $payment->commision_currency = 'ARS';
        $payment->commision = 0;
        $payment->status = Payment::STATUS_PENDING;
        $payment->is_manual = true;
        if ($model->part_distribution == 1) {
            $payment->part_distribution = 50;
        } else if ($model->part_distribution == 2) {
            $payment->part_distribution = 100;
        } else {
            $payment->part_distribution = 0;
        }
        if (!$payment->save()) {
            $success = false;
            \app\controllers\SiteController::FlashErrors($payment);
        }

        for ($i = 1; $i <= $model->quantity; $i++) {
            $stock = new Stock();
            $stock->coach_id = $model->coach_id;
            $stock->creator_id = Yii::$app->user->id;
            $stock->product_id = $model->product_id;
            $stock->payment_id = $payment->id;
            $stock->price = $model->price;
            $stock->status = Stock::STATUS_VALID;
            $stock->created_stamp = $created_stamp;
            if (!$stock->save()) {
                $success = false;
                \app\controllers\SiteController::FlashErrors($stock);
            }
        }

        return $success;
    }

    public static function consume($consumer_id, $quantity, $team_id) {
        $consumed_stamp = date('Y-m-d H:i:s');
        for ($i = 1; $i <= $quantity; $i++) {
            $available_stock_id = Yii::$app->db->createCommand('SELECT `id` FROM `stock` '
                . 'WHERE `consumed_stamp` is null '
                . 'AND `coach_id` = ' . $consumer_id
                . " AND `status` = 'valid'"
                . ' ORDER BY `id` ASC LIMIT 1')->queryScalar();

            Yii::$app->db->createCommand()
                ->update('stock', [
                    'status' => 'consumed',
                    'consumed_stamp' => $consumed_stamp,
                    'consumer_id' => $consumer_id,
                    'team_id' => $team_id,
                ], [
                    'id' => $available_stock_id,
                ])->execute();
        }

        return true;
    }

    public static function cancel($consumer_id, $quantity) {
        $consumed_stamp = date('Y-m-d H:i:s');
        for ($i = 1; $i <= $quantity; $i++) {
            $available_stock = Yii::$app->db->createCommand('SELECT `id`, `price`, `payment_id` FROM `stock` '
                . 'WHERE `consumed_stamp` is null '
                . 'AND `coach_id` = ' . $consumer_id
                . ' ORDER BY `id` ASC LIMIT 1')->queryOne();

            Yii::$app->db->createCommand()
                ->update('stock', [
                    'status' => 'error',
                    'consumed_stamp' => $consumed_stamp,
                    'consumer_id' => Yii::$app->user->identity->id,
                ], [
                    'id' => $available_stock['id'],
                ])->execute();

            Yii::$app->db->createCommand()
                ->update('payment', [
                    'amount' => new Expression('amount - ' . $available_stock['price'])
                ], [
                    'id' => $available_stock['payment_id'],
                    'status' => 'pending',
                ])->execute();
        }

        return true;
    }

}

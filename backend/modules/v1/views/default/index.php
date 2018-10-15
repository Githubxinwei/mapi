<div class="v1-default-index">
    <h1><?= $this->context->action->uniqueId ?></h1>
    <p>
        <img src="<?= \yii\helpers\Url::to(['/v1/api-qr-code/qr-code'])?>" style="width: 500px;height: 500px;"/>
    </p>
</div>

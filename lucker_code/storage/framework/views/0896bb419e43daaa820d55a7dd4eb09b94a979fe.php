<div class="tab-pane active" id="site" role="tabpanel">
    <div class="kt-section">
        <h3 class="kt-section__title">
            Общие настройки:
        </h3>
        <div class="form-group row">
            <div class="col-lg-4">
                <label>Заголовок сайта (титул):</label>
                <input type="text" class="form-control" placeholder="sitename.ru - краткое описание" value="<?php echo e($settings->title); ?>" name="title" />
            </div>
            <div class="col-lg-4">
                <label>Описание для поисковых систем:</label>
                <input type="text" class="form-control" placeholder="Описание для сайта..." value="<?php echo e($settings->description); ?>" name="description" />
            </div>
            <div class="col-lg-4">
                <label>Ключевые слова для поисковых систем:</label>
                <input type="text" class="form-control" placeholder="сайт, имя, домен и тд..." value="<?php echo e($settings->keywords); ?>" name="keywords" />
            </div>
        </div>
    </div>
    <div class="kt-section">
        <h3 class="kt-section__title">
            Настройки реферальной системы:
        </h3>
        <div class="form-group row">
            <div class="col-lg-4">
                <label>Награда за реферала 1-го уровня:</label>
                <input type="text" class="form-control" placeholder="Введите сумму" value="<?php echo e($settings->referral_reward); ?>" name="referral_reward" />
            </div>
        </div>
    </div>
    <div class="kt-section">
        <h3 class="kt-section__title">
            Остальные настройки:
        </h3>
        <div class="form-group row">
            <div class="col-lg-4">
                <label>Минимальная сумма пополнения:</label>
                <input type="text" class="form-control" placeholder="Введите сумму" value="<?php echo e($settings->min_payment_sum); ?>" name="min_payment_sum" />
            </div>
            <div class="col-lg-4">
                <label>Интервал ставок ботов. 1000 - 1 сек:</label>
                <input type="text" class="form-control" placeholder="Введите число" value="<?php echo e($settings->bot_timer); ?>" name="bot_timer" />
            </div>
            <div class="col-lg-4">
                <label>Сумма пополнений для совершения вывода:</label>
                <input type="text" class="form-control" placeholder="Введите сумму" value="<?php echo e($settings->min_dep_withdraw); ?>" name="min_dep_withdraw" />
            </div>
        </div>
        <div class="form-group row">
            <div class="col-lg-4">
                <label>Минимальная сумма для вывода:</label>
                <input type="text" class="form-control" placeholder="Введите сумму" value="<?php echo e($settings->min_withdraw_sum); ?>" name="min_withdraw_sum" />
            </div>
            <div class="col-lg-4">
                <label>Максимальное кол-во выплат в ожидании:</label>
                <input type="text" class="form-control" placeholder="Введите кол-во" value="<?php echo e($settings->withdraw_request_limit); ?>" name="withdraw_request_limit" />
            </div>
            <div class="col-lg-4">
                <label>Депозит каждые N дней:</label>
                <input type="text" class="form-control" placeholder="Введите кол-во" value="<?php echo e($settings->deposit_per_n); ?>" name="deposit_per_n" />
            </div>
        </div>
        <div class="form-group row">
            <div class="col-lg-4">
                <label>Сумма пополнений за N дней:</label>
                <input type="text" class="form-control" placeholder="Введите кол-во" value="<?php echo e($settings->deposit_sum_n); ?>" name="deposit_sum_n" />
            </div>
        </div>
    </div>
    <div class="kt-section">
        <h3 class="kt-section__title">
            Настройки группы VK:
        </h3>
        <div class="form-group row">
            <div class="col-lg-4">
                <label>ID Группы VK:</label>
                <input type="text" class="form-control" placeholder="192337402..." value="<?php echo e($settings->vk_id); ?>" name="vk_id" />
            </div>
            <div class="col-lg-4">
                <label>Ключ доступа:</label>
                <input type="text" class="form-control" placeholder="1f27230c1f27230c1f27230c841..." value="<?php echo e($settings->vk_token); ?>" name="vk_token" />
            </div>
            <div class="col-lg-4">
                <label>Сервисный ключ доступа:</label>
                <input type="text" class="form-control" placeholder="1f27230c1f27230c1f27230c841..." value="<?php echo e($settings->vk_service_token); ?>" name="vk_service_token" />
            </div>
        </div>
    </div>
    <div class="kt-section">
        <h3 class="kt-section__title">
            Настройка ссылок:
        </h3>
        <div class="form-group row">
            <div class="col-lg-4">
                <label>Ссылка на группу VK:</label>
                <input type="text" class="form-control" placeholder="https://vk.com/..." value="<?php echo e($settings->vk_url); ?>" name="vk_url" />
            </div>
            <div class="col-lg-4">
                <label>Ссылка на TG канал:</label>
                <input type="text" class="form-control" placeholder="https://t.me/..." value="<?php echo e($settings->tg_channel); ?>" name="tg_channel" />
            </div>
            <div class="col-lg-4">
                <label>Ссылка на TG бота:</label>
                <input type="text" class="form-control" placeholder="https://t.me/..." value="<?php echo e($settings->tg_bot); ?>" name="tg_bot" />
            </div>
        </div>
        <div class="form-group row">
            <div class="col-lg-4">
                <label>Реферальный домен:</label>
                <input type="text" class="form-control" placeholder="Введите ссылку" value="<?php echo e($settings->referral_domain); ?>" name="referral_domain" />
            </div>
        </div>
    </div>
</div>
<?php /**PATH /var/www/stimule/resources/views/admin/settings/sections/main.blade.php ENDPATH**/ ?>
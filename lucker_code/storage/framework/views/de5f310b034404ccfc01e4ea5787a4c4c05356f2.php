<?php $__env->startSection('content'); ?>
<script src="/dash/js/dtables.js?v=1" type="text/javascript"></script>
<div class="kt-subheader kt-grid__item" id="kt_subheader">
    <div class="kt-subheader__main">
        <h3 class="kt-subheader__title">Пользователи</h3>
    </div>
</div>

<div class="kt-content kt-grid__item kt-grid__item--fluid" id="kt_content">
    <div class="kt-portlet kt-portlet--tabs">
        <div class="kt-portlet__head">
            <div class="kt-portlet__head-toolbar">
                <ul class="nav nav-tabs nav-tabs-line nav-tabs-line-danger nav-tabs-line-2x nav-tabs-line-right" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#userSettings" role="tab" aria-selected="true">
                            Даннные пользователя <?php echo e($user->username); ?>

                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#userStatistics" role="tab" aria-selected="true">
                            Статистика
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#userRoles" role="tab" aria-selected="true">
                            Роли
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#userLogs" role="tab" aria-selected="true">
                            Логи
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <form class="kt-form" method="post" action="/admin/users/edit/<?php echo e($user->id); ?>">
            <div class="kt-portlet__body">
                <div class="tab-content">
                    <div class="tab-pane active" id="userSettings" role="tabpanel">
                        <div class="kt-section">
                            <div class="form-group row">
                                <div class="col-lg-4">
                                    <label>Логин:</label>
                                    <input type="text" autocomplete="off" class="form-control" placeholder="" value="<?php echo e($user->username); ?>" name="username" minlength="1" required />
                                </div>
                                <div class="col-lg-4">
                                    <label>Баланс (₽):</label>
                                    <input type="text" autocomplete="off" class="form-control" placeholder="" value="<?php echo e($user->balance); ?>" name="balance" required />
                                </div>
                                <div class="col-lg-4">
                                    <label>Заблокирован?</label>
                                    <select class="form-control" name="ban">
                                        <option value="1" <?php if($user->ban == 1): ?> selected <?php endif; ?>>Да</option>
                                        <option value="0" <?php if($user->ban == 0): ?> selected <?php endif; ?>>Нет</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-4">
                                    <label>Необходимо отыграть (₽):</label>
                                    <input type="text" autocomplete="off" class="form-control" placeholder="" value="<?php echo e($user->wager); ?>" name="wager" required />
                                </div>
                                <div class="col-lg-4">
                                    <label>Включена отыгровка?</label>
                                    <select class="form-control" name="wager_status">
                                        <option value="1" <?php if($user->wager_status == 1): ?> selected <?php endif; ?>>Да</option>
                                        <option value="0" <?php if($user->wager_status == 0): ?> selected <?php endif; ?>>Нет</option>
                                    </select>
                                </div>
                                <div class="col-lg-4">
                                <label>VK Профиль: <span style="cursor: pointer;" class="kt-font-primary" id="vkDate" onclick="getRegDate(<?php echo e($user->vk_id); ?>)">Показать дату</span></label>
                                    <input type="text" autocomplete="off" class="form-control" placeholder="" value="<?php if($user->vk_id > 0): ?> https://vk.com/id<?php echo e($user->vk_id); ?> <?php else: ?> Не привязан <?php endif; ?>" disabled />
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-4">
                                    <label>% за пополнение реф 1-lvl::</label>
                                    <input type="text" autocomplete="off" class="form-control" placeholder="" value="<?php echo e($user->ref_1_lvl); ?>" name="ref_1_lvl" />
                                </div>
                                <div class="col-lg-4">
                                    <label>% за пополнение реф 2-lvl::</label>
                                    <input type="text" autocomplete="off" class="form-control" placeholder="" value="<?php echo e($user->ref_2_lvl); ?>" name="ref_2_lvl" />
                                </div>
                                <div class="col-lg-4">
                                    <label>% за пополнение реф 3-lvl:</label>
                                    <input type="text" autocomplete="off" class="form-control" placeholder="" value="<?php echo e($user->ref_3_lvl); ?>" name="ref_3_lvl" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-3">
                                    <label>IP: <span style="cursor: pointer;" class="kt-font-primary" onclick="getCountry('<?php echo e($user->used_ip); ?>', this)">Узнать город</span></label>
                                    <input type="text" autocomplete="off" class="form-control" placeholder="" value="<?php echo e($user->used_ip); ?>" disabled />
                                </div>
                                <div class="col-lg-3">
                                    <label>IP при регистрации: <span style="cursor: pointer;" class="kt-font-primary" onclick="getCountry('<?php echo e($user->created_ip); ?>', this)">Узнать город</span></label>
                                    <input type="text" autocomplete="off" class="form-control" placeholder="" value="<?php echo e($user->created_ip); ?>" disabled />
                                </div>
                                <div class="col-lg-3">
                                    <label>Видеокарта:</label>
                                    <input type="text" autocomplete="off" class="form-control" placeholder="" value="<?php echo e($user->videocard); ?>" disabled />
                                </div>
                                <div class="col-lg-3">
                                    <label>Уникальный отпечаток:</label>
                                    <input type="text" autocomplete="off" class="form-control" placeholder="" value="<?php echo e($user->fingerprint); ?>" disabled />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="userStatistics" role="tabpanel">
                        <div class="kt-section">
                            <div class="form-group row">
                                <div class="col-lg-4">
                                    <label>Профит Dice:</label>
                                    <input
                                        type="text"
                                        autocomplete="off"
                                        class="form-control"
                                        placeholder=""
                                        value="<?php echo e(number_format($user->dice, 2, '.', '')); ?>"
                                        disabled
                                    />
                                </div>
                                <div class="col-lg-4">
                                    <label>Профит Mines:</label>
                                    <input
                                        type="text"
                                        autocomplete="off"
                                        class="form-control"
                                        placeholder=""
                                        value="<?php echo e(number_format($user->mines, 2, '.', '')); ?>"
                                        disabled
                                    />
                                </div>
                                <div class="col-lg-4">
                                    <label>Профит Slots:</label>
                                    <input
                                        type="text"
                                        autocomplete="off"
                                        class="form-control"
                                        placeholder=""
                                        value="<?php echo e(number_format($user->slots, 2, '.', '')); ?>"
                                        disabled
                                    />
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-4">
                                    <label>Профит Bubbles:</label>
                                    <input
                                        type="text"
                                        autocomplete="off"
                                        class="form-control"
                                        placeholder=""
                                        value="<?php echo e(number_format($user->bubbles, 2, '.', '')); ?>"
                                        disabled
                                    />
                                </div>
                                <div class="col-lg-4">
                                    <label>Профит Wheel:</label>
                                    <input
                                        type="text"
                                        autocomplete="off"
                                        class="form-control"
                                        placeholder=""
                                        value="<?php echo e(number_format($user->wheel, 2, '.', '')); ?>"
                                        disabled
                                    />
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-4">
                                    <label>Заработал на реф.системе:</label>
                                    <input
                                        type="text"
                                        autocomplete="off"
                                        class="form-control"
                                        placeholder=""
                                        value="<?php echo e(\App\ReferralProfit::query()->where('ref_id', $user->id)->sum('amount')); ?>"
                                        disabled
                                    />
                                </div>
                                <div class="col-lg-4">
                                    <label>Пополнил:</label>
                                    <input
                                        type="text"
                                        autocomplete="off"
                                        class="form-control"
                                        placeholder=""
                                        value="<?php echo e(round(App\Payment::query()->where([['user_id', $user->id], ['status', 1]])->sum('sum'), 2)); ?> р."
                                        disabled
                                    />
                                </div>
                                <div class="col-lg-4">
                                    <label>Вывел:</label>
                                    <input
                                        type="text"
                                        autocomplete="off"
                                        class="form-control"
                                        placeholder=""
                                        value="<?php echo e(round(App\Withdraw::query()->where([['user_id', $user->id], ['status', 1]])->sum('sum'), 2)); ?> р."
                                        disabled
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="userRoles" role="tabpanel">
                        <div class="kt-section">
                            <div class="form-group row">
                                <div class="col-lg-4">
                                    <label>Администратор?</label>
                                    <select class="form-control" name="is_admin">
                                        <option value="1" <?php if($user->is_admin == 1): ?> selected <?php endif; ?>>Да</option>
                                        <option value="0" <?php if($user->is_admin == 0): ?> selected <?php endif; ?>>Нет</option>
                                    </select>
                                </div>
                                <div class="col-lg-4">
                                    <label>Стример?</label>
                                    <select class="form-control" name="is_youtuber">
                                        <option value="1" <?php if($user->is_youtuber == 1): ?> selected <?php endif; ?>>Да</option>
                                        <option value="0" <?php if($user->is_youtuber == 0): ?> selected <?php endif; ?>>Нет</option>
                                    </select>
                                </div>
                                <div class="col-lg-4">
                                    <label>Сотрудник?</label>
                                    <select class="form-control" name="is_worker">
                                        <option value="1" <?php if($user->is_worker == 1): ?> selected <?php endif; ?>>Да</option>
                                        <option value="0" <?php if($user->is_worker == 0): ?> selected <?php endif; ?>>Нет</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="userLogs" role="tabpanel">
                        <div class="kt-section">
                            <table class="table table-striped- table-bordered table-hover table-checkable" id="logs">
                                <thead>
                                    <tr>
                                        <th scope="col">Дата</th>
                                        <th scope="col">Тип</th>
                                        <th scope="col">Баланс до</th>
                                        <th scope="col">Баланс после</th>
                                        <th scope="col">Изменение баланса</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $h): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e(date('d.m.y в H:i:s', strtotime($h->date))); ?></td>
                                        <td><?php echo e($h->type); ?></td>
                                        <td><?php echo e(number_format($h->balance_before, 2, ',', ' ')); ?></td>
                                        <td><?php echo e(number_format($h->balance_after, 2, ',', ' ')); ?></td>
                                        <td><?php echo e(number_format(($h->balance_after - $h->balance_before), 2, ',', ' ')); ?></td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="kt-portlet__foot">
                <div class="kt-form__actions">
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                    <button type="button" class="btn btn-info" onclick="multiChecker(<?php echo e($user->id); ?>)">Найти мультиаккаунты</button>
                    <button type="button" class="btn btn-secondary" onclick="window.location.href = '<?php echo e(route('admin.users')); ?>'">Назад</button>
                    <div class="sm-btn-control d-flex flex-row">
                        <button type="button" class="btn btn-danger ml-5 fake" onclick="location.href = '<?php echo e(route('admin.users.createFake', ['type' => 'Pay', 'id' => $user->id])); ?>'">Добавить пополнение</button>
                        <button type="button" class="btn btn-danger fake" onclick="location.href = '<?php echo e(route('admin.users.createFake', ['type' => 'Payout', 'id' => $user->id])); ?>'">Добавить выплату</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<style>
    @media(max-width: 570px) {
        .ml-5.fake {
            margin-left: 0!important;
        }
        .fake {
            margin: 30px 0 0 5px;
            min-width: 49.5%;
        }
    }
    @media(min-width: 570px) {
        .sm-btn-control {
            float: right;
            display: inline-block!important;
        }
    }
</style>
<script>
    $("#logs").DataTable();
</script>
<script>
    function getRegDate(id) {
        $('#vkDate').html('...');
        $.post('/admin/getVKinfo', {
            vk_id: id
        })
        .then(res => {
            $('#vkDate').html(res).css('pointerEvents', 'none');
        })
    }

    function getCountry(ip, elem) {
        $(elem).html('...');
        $.post('/admin/getCountry', {
            user_ip: ip
        })
        .then(res => {
            $(elem).html(res).css('pointerEvents', 'none');
        })
    }
</script>
<style>
    @media (max-width:1100px) {
        .col-lg-4 {
            margin-top: 20px;
        }
    }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin/layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/admin/users/edit.blade.php ENDPATH**/ ?>
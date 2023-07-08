<main>
    <?= $header; ?>
    <section class="lot-item container">
        <h2><?= $lot['lot_name']; ?></h2>
        <div class="lot-item__content">
            <div class="lot-item__left">
                <div class="lot-item__image">
                    <img src="<?= $lot["lot_image"]; ?>" width=" 730" height="548" alt="Сноуборд">
                </div>
                <p class="lot-item__category">Категория: <span><?= $lot["name_category"]; ?></span></p>
                <p class="lot-item__description"><?= $lot["lot_description"]; ?></p>
            </div>
            <div class="lot-item__right">
                <?php
                $time = get_remaining_time($lot["date_finish"]) ?>
                <?php
                if ($is_auth && array_sum(
                        $time
                    ) && $lot["user_id"] !== $_SESSION["id"] && ($history[0]["user_name"] ?? "") !== $_SESSION["name"]): ?>
                    <div class="lot-item__state">
                        <div class="lot-item__timer timer <?php
                        if ($time[0] < 1): ?> timer--finishing <?php
                        endif; ?>">
                            <?= "$time[0]:$time[1]"; ?>
                        </div>
                        <div class="lot-item__cost-state">
                            <div class="lot-item__rate">
                                <span class="lot-item__amount">Текущая цена</span>
                                <span class="lot-item__cost"><?= format_sum($current_price); ?></span>
                            </div>
                            <div class="lot-item__min-cost">
                                Мин. ставка <span><?= format_sum($min_bet); ?></span>
                            </div>
                        </div>
                        <?php
                        if (!isset($_SESSION['id'])) {
                            echo '<style>.lot-item__form {display: none;}</style>';
                        } ?>
                        <form class="lot-item__form" action="lot.php?id=<?= $id; ?>" method="post" autocomplete="off">
                            <p class="lot-item__form-item form__item <?php
                            if ($error): ?> form__item--invalid<?php
                            endif; ?>">
                                <label for="cost">Ваша ставка</label>
                                <input id="cost" type="text" name="cost" placeholder="12 000">
                                <span class="form__error"><?= $error; ?></span>
                            </p>
                            <button type="submit" class="button">Сделать ставку</button>
                        </form>

                    </div>
                <?php
                endif; ?>
                <?php
                if (!empty($history)): ?>
                <div class="history">
                    <h3>История ставок (<span>10</span>)</h3>
                    <table class="history__list">
                        <?php
                        foreach ($history as $bet): ?>
                            <tr class="history__item">
                                <td class="history__name"><?= $bet["user_name"]; ?></td>
                                <td class="history__price"><?= format_sum($bet["sum_bet"]); ?></td>
                                <td class="history__time"><?= $bet["bet_date"]; ?></td>
                            </tr>
                        <?php
                        endforeach; ?>
                    </table>
                    <?php
                    endif; ?>
                </div>
            </div>
        </div>
    </section>
</main>

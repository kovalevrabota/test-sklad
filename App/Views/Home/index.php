<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Список заказов</title>

    <link rel="stylesheet" href="./css/home.css">
</head>

<body>
    <div class="container header">
        <span><?php echo $login ?></span>
        <a href="/logout" class="button">Выйти</a>
    </div>

    <div class="container">
        <table class="table">
            <thead>
                <tr>
                    <th>№</th>
                    <th>Время</th>
                    <th>Контрагент</th>
                    <th>Организация</th>
                    <th>Сумма</th>
                    <th>Валюта</th>
                    <th>Статус</th>
                    <th>Когда изменен</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <td colspan="8">
                        Всего заказов: <?php echo $size ?>, на сумму <?php echo $summ ?> руб.
                        <p class="success-status">Статус изменен</p>
                    </td>
                </tr>
            </tfoot>
            <tbody>
                <?php foreach($orders as $item) : ?>
                <tr>
                    <td><a href="<?php echo $item['link'] ?>" target="_blank"><?php echo $item['number'] ?></a></td>
                    <td><?php echo $item['created'] ?></td>
                    <td>
                        <a href="<?php echo $item['agent']['link'] ?>" target="_blank">
                            <?php echo $item['agent']['name'] ?>
                        </a>
                    </td>
                    <td>
                        <a href="<?php echo $item['organization']['link'] ?>" target="_blank">
                            <?php echo $item['organization']['name'] ?>
                        </a>
                    </td>
                    <td><?php echo $item['sum'] ?></td>
                    <td><?php echo $item['currency'] ?></td>
                    <td>
                        <select name="states">
                            <?php foreach($states as $state) : ?>
                            <option value="<?php echo $state['id'] ?>" data-order_id="<?php echo $item['id'] ?>"
                                data-color="<?php echo $state['color'] ?>"
                                <?php echo $item['state'] == $state['id'] ? 'selected' : '' ?>>
                                <?php echo $state['name'] ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td><?php echo $item['updated'] ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="./js/home.js"></script>
</body>

</html>
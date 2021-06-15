<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Корзина товаров</title>
    <meta name="description" content="Корзина товаров">

    <link rel="stylesheet" href="/public/css/main.css" charset="utf-8">
    <link rel="stylesheet" href="/public/css/products.css" charset="utf-8">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" crossorigin="anonymous">
</head>
<body>
    <?php require 'public/blocks/header.php' ?>

    <div class="container main">
        <h1>Корзина товаров</h1>
        <?php if(count($data['products']) == 0): ?>
            <p><?=$data['empty']?></p>
        <?php else: ?>
            <form action="/basket" method="post">
                <input type="hidden" name="clear_basket" value="clear">
                <button class="btn" id="clear_btn">Очистить корзину <i class="fas fa-shopping-basket"></i></button>
            </form>
            <div class="products">
                <?php
                    $sum = 0;
                    for($i = 0; $i < count($data['products']); $i++):
                        $sum += $data['products'][$i]['price'];
                ?>
                <div class="row">
                    <img src="/public/img/<?=$data['products'][$i]['img']?>" alt="<?=$data['products'][$i]['title']?>">
                    <h4><?=$data['products'][$i]['title']?></h4>
                    <span><?=$data['products'][$i]['price']?> рублей</span>
                    <form action="/basket" method="post">
                        <input type="hidden" name="clear_basket_item" value="<?=$data['products'][$i]['id']?>">
                        <button class="btn">Удалить товар <i class="fas fa-shopping-basket"></i></button>
                    </form>
                </div>
                <?php endfor; ?>



                <?php

                //Секретный ключ интернет-магазина
                $key = "4c4777794b5b586267503042655a367a6764324634327142544b4a";

                $fields = array();

                // Добавление полей формы в ассоциативный массив
                $fields["WMI_MERCHANT_ID"]    = "136308335199";
                $fields["WMI_PAYMENT_AMOUNT"] = $sum;
                $fields["WMI_CURRENCY_ID"]    = "643";
                $fields["WMI_PAYMENT_NO"]     = time();
                $fields["WMI_DESCRIPTION"]    = "BASE64:".base64_encode("Покупка товаров на сайте itProger");
                $fields["WMI_EXPIRED_DATE"]   = date('Y-m-d')."T23:59:59";
                $fields["WMI_SUCCESS_URL"]    = "https://itproger.com/success";
                $fields["WMI_FAIL_URL"]       = "https://itproger.com/fail.php";
                $fields["id_of_tovar"]       = "ID-234567"; // Дополнительные параметры

                //Если требуется задать только определенные способы оплаты, раскоментируйте данную строку и перечислите требуемые способы оплаты.
                // $fields["WMI_PTENABLED"]      = array("UnistreamRUB", "SberbankRUB", "RussianPostRUB");

                //Сортировка значений внутри полей
                foreach($fields as $name => $val)
                {
                    if(is_array($val))
                    {
                        usort($val, "strcasecmp");
                        $fields[$name] = $val;
                    }
                }

                // Формирование сообщения, путем объединения значений формы,
                // отсортированных по именам ключей в порядке возрастания.
                uksort($fields, "strcasecmp");
                $fieldValues = "";

                foreach($fields as $value)
                {
                    if(is_array($value))
                        foreach($value as $v)
                        {
                            //Конвертация из текущей кодировки (UTF-8)
                            //необходима только если кодировка магазина отлична от Windows-1251
                            $v = iconv("utf-8", "windows-1251", $v);
                            $fieldValues .= $v;
                        }
                    else
                    {
                        //Конвертация из текущей кодировки (UTF-8)
                        //необходима только если кодировка магазина отлична от Windows-1251
                        $value = iconv("utf-8", "windows-1251", $value);
                        $fieldValues .= $value;
                    }
                }

                // Формирование значения параметра WMI_SIGNATURE, путем
                // вычисления отпечатка, сформированного выше сообщения,
                // по алгоритму MD5 и представление его в Base64

                $signature = base64_encode(pack("H*", md5($fieldValues . $key)));

                //Добавление параметра WMI_SIGNATURE в словарь параметров формы

                $fields["WMI_SIGNATURE"] = $signature;

                // Формирование HTML-кода платежной формы

                print "<form action='https://wl.walletone.com/checkout/checkout/Index' method='POST'>";

                foreach($fields as $key => $val)
                {
                    if(is_array($val))
                        foreach($val as $value)
                        {
                            print "<input type='hidden' name='$key' value='$value'/>";
                        }
                    else
                        print "<input type='hidden' name='$key' value='$val'/>";
                }

                print "<button type='submit' class='btn'>Приоребсти (<b>".$sum." рублей</b>)</button></form>";

                ?>





            </div>
        <?php endif;?>
    </div>

    <?php require 'public/blocks/footer.php' ?>
</body>
</html>
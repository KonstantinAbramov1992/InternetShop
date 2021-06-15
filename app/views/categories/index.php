<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?=$data['title']?></title>
    <meta name="description" content="<?=$data['title']?>">

    <link rel="stylesheet" href="/public/css/main.css" charset="utf-8">
    <link rel="stylesheet" href="/public/css/categories.css" charset="utf-8">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" crossorigin="anonymous">
</head>
<body>
    <?php require 'public/blocks/header.php' ?>

    <div class="container main">
        <h1><?=$data['title']?></h1>
        <div class="products">
            <?php for($i = $data['pageNumber'] * 3 - 1; $i >= ($data['pageNumber'] * 3 - 3); $i--): ?>
                <?php if ($data['products'][$i]['title']): ?>
                    <div class="product">
                        <div class="image">
                            <img src="/public/img/<?=$data['products'][$i]['img']?>" alt="<?=$data['products'][$i]['title']?>">
                        </div>
                        <h3><?=$data['products'][$i]['title']?></h3>
                        <p><?=$data['products'][$i]['anons']?></p>
                        <a href="/product/<?=$data['products'][$i]['id']?>"><button class="btn">Детальнее</button></a>
                    </div>
                <?php endif; ?>
            <?php endfor; ?>
        </div>
        <div class="items-panel">
            <?php for($i = 1; $i < (count($data['products'])/3 + 1); $i ++): ?>
                <a href="/categories/<?=$i?>"><div class="paginate"><?=$i?></div></a>
            <?php endfor; ?>
        </div>
    </div>

    <?php require 'public/blocks/footer.php' ?>
</body>
</html>
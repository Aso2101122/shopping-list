<?php
require_once './dbmanager.php';
$stms = getDb();

if(isset($_GET['place-id'])){
    //場所で条件検索
    //完了
    $sql = $stms->prepare('select * from shopping_list s left outer join category c on s.category_id=c.category_id left outer join place p on s.place_id = p.place_id where complete_flag = 0 AND s.place_id=? order by registration_date desc');
    $sql->bindValue(1,$_GET['place-id']);
    $sql->execute();
    $result = $sql->fetchAll(PDO::FETCH_ASSOC);
    //未完了
    $sql = $stms->prepare('select * from shopping_list s left outer join category c on s.category_id=c.category_id left outer join place p on s.place_id = p.place_id where complete_flag = 1 AND s.place_id=? order by registration_date desc');
    $sql->bindValue(1,$_GET['place-id']);
    $sql->execute();
    $complete_result = $sql->fetchAll(PDO::FETCH_ASSOC);
}else{
    $sql = $stms->query('select * from shopping_list s left outer join category c on s.category_id=c.category_id left outer join place p on s.place_id = p.place_id where complete_flag = 0 order by registration_date desc');
    $result = $sql->fetchAll(PDO::FETCH_ASSOC);
    $sql = $stms->query('select * from shopping_list s left outer join category c on s.category_id=c.category_id left outer join place p on s.place_id = p.place_id where complete_flag = 1 order by registration_date desc');
    $complete_result = $sql->fetchAll(PDO::FETCH_ASSOC);
}


//追加ボタンが押されたら
if(isset($_POST['add'])){
    //入力チェック
    if(isset($_POST['name']) && !empty($_POST['name'])){
        $sql = $stms->prepare('insert into shopping_list(name,category_id,place_id) values(?,?,?)');
        $sql->bindValue(1,$_POST['name']);
        $sql->bindValue(2,$_POST['category']);
        $sql->bindValue(3,$_POST['place']);
        $sql->execute();
        $stmt = null;
    }
}

//カテゴリーテーブル
$sql = $stms->query('select * from category');
$category_result = $sql->fetchAll(PDO::FETCH_ASSOC);

//場所テーブル
$sql = $stms->query('select * from place');
$place_result = $sql->fetchAll(PDO::FETCH_ASSOC);

//完了ボタンが押されたら
if(isset($_POST['complete'])){
    $sql = $stms->prepare('update shopping_list set complete_flag = 1 where id=?');
    $sql->bindValue(1,$_POST['complete']);
    $sql->execute();
    $stmt = null;
    header('Location: ./');
    exit;
}

//完了取り消しボタンが押されたら
if(isset($_POST['uncomplete'])){
    $sql = $stms->prepare('update shopping_list set complete_flag = 0 where id=?');
    $sql->bindValue(1,$_POST['uncomplete']);
    $sql->execute();
    $stmt = null;
    header('Location: ./');
    exit;
}

//削除ボタンが押されたら
if(isset($_POST['delete'])){
    $sql = $stms->prepare('delete from shopping_list where id=?');
    $sql->bindValue(1,$_POST['delete']);
    $sql->execute();
    $stmt = null;
    header('Location: ./');
    exit;
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>買い物リスト</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="main">
    <h1>買い物リスト</h1>
    <h2>入力</h2>
    <div class="input-area">
        <form action="index.php" method="post">
            <div class="name-area">
                <span>品目名：</span>
                <input type="text" name="name" class="name" placeholder="例：たまねぎ"/>
            </div>

            <div class="select-area">
                <span>カテゴリー：</span>
                <select name="category" class="category-select-box" id="category-select" onChange="categoryAdd()">
                    <?php foreach($category_result as $row):?>
                        <option value="<?= $row['category_id']?>"><?= $row['category_name'] ?></option>
                    <?php endforeach; ?>
                    <option value="category-add.php">カテゴリを追加</option>
                </select>
                <span>場所：</span>
                <select name="place" id="place-select" onChange="placeAdd()">
                    <?php foreach($place_result as $row):?>
                        <option value="<?= $row['place_id']?>"><?= $row['place_name'] ?></option>
                    <?php endforeach; ?>
                    <option value="place-add.php">場所を追加</option>
                </select>
            </div>

            <button type="submit" name="add">追加</button>
        </form>
    </div>

    <div class="refinement-area">
        <span>絞込み</span>
        <select name="refinement-select" class="refinement-select" id="refinement-select" onChange="location.href=value;">
            <option value="./index.php">指定なし</option>
            <?php foreach($place_result as $row):?>
                <?php
                $place_selected = '';
                if($row['place_id'] == $_GET['place-id']){
                    $place_selected = 'selected';
                }
                ?>
                <option value="./index.php?place-id=<?= $row['place_id']?>"<?= $place_selected ?>><?= $row['place_name'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <h2>未完了</h2>
    <form action="index.php" method="post">
        <table>
            <tr>
                <th></th>
                <th></th>
                <th>カテゴリ</th>
                <th>場所</th>
                <th></th>
                <th></th>
            </tr>
        <?php
        foreach($result as $row):?>
            <tr>
                <div class="item-row">
                    <td class="check">
                        <button type="submit" name="complete" class="check-button" value="<?= $row['id'] ?>"><img src="./img/checkbox_0.png" width="18px" class="complete-img"/></button>
                    </td>
                    <td class="name">
                        <span class="item-text"><?= $row['name']?></span>
                    </td>
                    <td class="category">
                        <span class="item-text"><?= $row['category_name']?></span>
                    </td>
                    <td class="place">
                        <span class="item-text"><?= $row['place_name']?></span>
                    </td>
                    <td class="edit">
                        <button type="button" onclick="location.href='./edit.php?id=<?= $row['id'] ?>'">編集</button>
                    </td>
                    <td class="delete">
                        <button type="submit" name="delete" value="<?= $row['id'] ?>">削除</button>
                    </td>
                </div>
            </tr>
        <?php endforeach; ?>
        </table>
    </form>
    <h2>完了</h2>
    <div class="complete-row">
        <form action="index.php" method="post">
            <table>
                <tr>
                    <th></th>
                    <th></th>
                    <th>カテゴリ</th>
                    <th>場所</th>
                    <th></th>
                    <th></th>
                </tr>
        <?php foreach($complete_result as $row):?>
                <tr>
                <div class="item-row">
                    <td class="check">
                        <button type="submit" name="uncomplete" class="check-button" value="<?= $row['id'] ?>"><img src="./img/checkbox_1.png" width="18px" class="complete-img"/></button>
                    </td>
                    <td class="name">
                        <span class="item-text"><?= $row['name']?></span>
                    </td>
                    <td class="category">
                        <span class="item-text"><?= $row['category_name']?></span>
                    </td>
                    <td class="place">
                        <span class="item-text"><?= $row['place_name']?></span>
                    </td>
                    <td class="edit">
                        <button type="button" onclick="location.href='./edit.php?id=<?= $row['id'] ?>'">編集</button>
                    </td>
                    <td class="delete">
                        <button type="submit" name="delete" value="<?= $row['id'] ?>">削除</button>
                    </td>
                </div>
            </tr>
        <?php endforeach; ?>
            </table>
        </form>
    </div>
</div>
<input type="checkbox">
<script src="script.js"></script>
</body>
</html>

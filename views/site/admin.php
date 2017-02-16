<?
/**
 * @var $data array
 */
$category = [
    ['id'=>'0', 'title'=>''                    ],
    ['id'=>'1', 'title'=>'Зимовий одяг'        ],
    ['id'=>'2', 'title'=>'Св\'ятковi вбрання'  ],
    ['id'=>'3', 'title'=>'Футболки, шорти'     ],
    ['id'=>'4', 'title'=>'Для хлопчиків'       ],
    ['id'=>'5', 'title'=>'Для дівчаток'        ],
    ['id'=>'6', 'title'=>'Інше'                ]
];
?>
<style>
    input, textarea, select{ display: block; width: 100%;}
    textarea{ height: 100px; margin-bottom: 20px;}
    .col{ float: left; width: 20%; padding: 10px; box-sizing: border-box;}
    .col-40{width: 40%;}
    .col-10{width: 10%;}
</style>
<?php if(isset($data)): ?>
    <div class="row">
        <div class="col col-10"><p>ID</p></div>
        <div class="col"><p>Заголовок</p></div>
        <div class="col col-40"><p>Описание</p></div>
        <div class="col col-10"><p>Цена</p></div>
        <div class="col"><p>Категория, Пол</p></div>
    </div>
    <?php foreach($data as $product): ?>
        <form action="/product-edit/<?= $product['product_id']?>" method="post" class="row">
            <div class="col col-10"><p><?= $product['product_id']?></p></div>
            <div class="col">
                <input name="title" value="<?= $product['title']?>">
            </div>
            <div class="col col-40">
                <textarea name="description"><?= $product['description'] ?></textarea>
            </div>
            <div class="col col col-10">
                <input name="price" value="<?= $product['price']?>">
            </div>
            <div class="col">
                <select name="category">
                    <?php foreach($category as $cat): ?>
                    <option value="<?=$cat['id']?>"  <?= $cat['id']==$product['category']?'selected':''?>><?=$cat['title']?></option>
                    <?php endforeach; ?>
                </select> <br>
                <select name="gender">
                    <option value="0"  <?= 0==$product['gender']?'selected':''?>>Унисекс</option>
                    <option value="1"  <?= 1==$product['gender']?'selected':''?>>Для мальчиков</option>
                    <option value="2"  <?= 2==$product['gender']?'selected':''?>>Для девочек</option>
                </select>
            </div>
        </form>
<!--    --><?php //foreach( json_decode($product['images'])  as $img): ?>
<!--        <img src="--><?//= $img ?><!--">-->
<!--    --><?php //endforeach; ?>
    <?php endforeach; ?>
<?php endif; ?>

<script>
    $('input, select, textarea').change(function(){
        $(this).closest('form').submit();
    });
</script>

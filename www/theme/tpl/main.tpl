<!--// hello -->
        <div style="text-align:center">
            <h2><?=$hello; ?></h2>
            <a href="<?=href('main/index/id/15'); ?>">Локальная ссылка</a>
            <br />
            <a href="<?=href('main/index/id/15', true); ?>">Абсолютная ссылка</a>
            <br />
            <?=linkTo('main/index/id/15', 
                      'Синтетическая ссылка (<strong>https</strong>)', 
                      'style="active"', 
                      ['absolute' => true, 'https' => true]); ?>
            <br />
            <?=linkTo('http://abc-framework.ru', 'Внешняя ссылка'); ?>
            
        </div>
<!--// hello end -->
<!--// hello -->
        <div style="text-align:center">
            <h2><?=$hello; ?></h2>
            <a href="<?=href('?controller=second&id=3'); ?>">Локальная ссылка GET</a>
            <br />    
            <a href="<?=href('index/15'); ?>">Локальная ссылка ЧПУ</a>
            <br />
            <a href="<?=href('second/21/www/eee', true); ?>">Абсолютная ссылка</a>
            <br />
            <?=linkTo('main/index/id/15', 
                      'Синтетическая ссылка (<strong>https</strong>)', 
                      'style="active"', 
                      ['absolute' => true, 'https' => true]); ?>
            <br />
            <?=linkTo('http://abc-framework.ru', 'Внешняя ссылка'); ?>
            
        </div>
<!--// hello end -->
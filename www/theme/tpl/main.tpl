<!--// hello -->

        <div style="text-align:center">
            <h2><?=$hello; ?></h2>
<?php var_dump($_GET); ?>
             <br /> 

            <br />
            <?=href('/?controller=main&action=index&edit=3&id=5', ['absolute' => false, 'pretty' => true]) ?>
            <br />
            <?=href('/?controller=main&action=index&edit=3&id=5', ['absolute' => false, 'pretty' => false]) ?>
            <br />
            <br />
            <br />
            <?=href('main/index/edit/3/id/5', ['absolute' => false, 'pretty' => true]) ?>
             <br /> 
            <?=href('main/index/edit/3/id/5', ['absolute' => false, 'pretty' => false]) ?>
            <br /> 
            <a href="/main/3/5">Test</a>
            <br />

            
        </div>
<!--// hello end -->
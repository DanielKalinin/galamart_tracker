<?php if (($desk->status != 'inactive' ||
        $_SESSION['user']['type'] == 'project_manager' || $_SESSION['user']['type'] == 'development_manager')
        && $desk->isAvail($_SESSION['user']['type'])): ?>
    <h2><?php echo $desk->name; ?></h2>
    <?php foreach ($desk->cardsid as $cardid): ?>
    <?php if ($desk->isAvail($_SESSION['user']['type'])): ?>
        <?php $card = Dejavu::getObject('Card', $cardid); ?>
        <?php CardVis::makeForm($card); ?>
    <?php endif; ?>
    <?php endforeach; ?>
<?php else: ?>
    Недоступно
<?php endif; ?>
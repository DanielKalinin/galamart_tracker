<?php if (($stage->status != 'inactive' ||
        $_SESSION['user']['type'] == 'project_manager' || $_SESSION['user']['type'] == 'development_manager')
        && $stage->isAvail()): ?>
    <h2><?php echo $stage->name; ?></h2>
    <table>
        <tr>
            <?php foreach ($stage->desksid as $deskid): ?>
                <?php $desk = Dejavu::getObject('Desc', $deskid); ?>
                <td>
                    <?php deskVis::makeForm($desk); ?>
                </td>
            <?php endforeach; ?>
        </tr>
    </table>
<?php else: ?>
    Недоступно
<?php endif; ?>
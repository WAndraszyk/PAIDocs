<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Resource $resource
 * @var string[]|\Cake\Collection\CollectionInterface $users
 */
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8" />
    <script src="../../webroot/js/form_check.js" defer></script>
    <title></title>
</head>
<body>
    <div class="row">
        <aside class="column">
            <div class="side-nav">
                <h4 class="heading"><?= __('Actions') ?></h4>
                <?= $this->Form->postLink(
                    __('Delete'),
                    ['action' => 'delete', $resource->id],
                    ['confirm' => __('Are you sure you want to delete # {0}?', $resource->id), 'class' => 'side-nav-item']
                ) ?>
                <?= $this->Html->link(__('List Resources'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            </div>
        </aside>
        <div class="column-responsive column-80">
            <div class="resources form content">
                <?= $this->Form->create($resource, ['type'=>'file']) ?>
                <fieldset>
                    <legend><?= __('Edit Resource') ?></legend>

                    <span id="in"><?php echo $this->Form->control('title'); ?></span>
                    <span id="errors"></span>
                    <?php
                        echo $this->Form->control('body');
                        echo $this->Form->control('image_file',['type'=>'file']);
                        echo $this->Form->control('tags');
                    ?>
                    Separate tags with spaces
                </fieldset>
                <?= $this->Form->button(__('Submit')) ?>
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</body>
</html>

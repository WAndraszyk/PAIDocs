<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Resource[]|\Cake\Collection\CollectionInterface $resources
 */
?>
<div class="resources index content">
    <?= $this->Html->link(__('New Resource'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Resources') ?></h3>

    <table>
        <tr>
            <td>
                <?= $this->Form->create(null, ['type'=>'get']) ?>
                <?= $this->Form->control('titleKey',['label'=>'Search by title','value'=>$this->request->getQuery('titleKey')]) ?>
                <?= $this->Form->submit('Search') ?>
                <?= $this->Form->end() ?>
            </td>
            <td>
                <?= $this->Form->create(null, ['type'=>'get']) ?>
                <?= $this->Form->control('bodyKey',['label'=>'Search by body','value'=>$this->request->getQuery('bodyKey')]) ?>
                <?= $this->Form->submit('Search') ?>
                <?= $this->Form->end() ?>
            </td>
            <td>
                <?= $this->Form->create(null, ['type'=>'get']) ?>
                <?= $this->Form->control('tag',['label'=>'Filter with tags','value'=>$this->request->getQuery('tag')]) ?>
                <?= $this->Form->submit('Filter') ?>
                <?= $this->Form->end() ?>
            </td>
        </tr>
    </table>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('image') ?></th>
                    <th><?= $this->Paginator->sort('title') ?></th>
                    <th><?= $this->Paginator->sort('user_id') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th><?= $this->Paginator->sort('modified') ?></th>
                    <th><?= $this->Paginator->sort('tags') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($resources as $resource):
                if ($username == $resource->user->name):?>
                <tr>
                    <td><?= $this->Html->image($resource->image, ['style' => 'max-width:50px;height:50px;border-radius:50%;']) ?></td>
                    <td><?= h($resource->title) ?></td>
                    <td><?= $resource->has('user') ? $this->Html->link($resource->user->name, ['controller' => 'Users', 'action' => 'view', $resource->user->id]) : '' ?></td>
                    <td><?= h($resource->created) ?></td>
                    <td><?= h($resource->modified) ?></td>
                    <td><?= h($resource->tags) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $resource->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $resource->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $resource->id], ['confirm' => __('Are you sure you want to delete # {0}?', $resource->id)]) ?>
                    </td>
                </tr>
                <?php endif;
                endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?></p>
    </div>
</div>

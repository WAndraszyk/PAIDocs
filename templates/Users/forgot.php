<?php
/**
 * @var AppView $this
 * @var User[]|CollectionInterface $users
 */

use App\Model\Entity\User;
use App\View\AppView;
use Cake\Collection\CollectionInterface;

?>
<div>
    <h2>Password reset</h2>
    <?= $this->Form->create(null, ['type'=>'post','url' => ['controller'=>'Users','action' => 'forgot']]); ?>
        <?= $this->Form->control('name', array('required'=>true)); ?>
        <?= $this->Form->control('answer', array('label'=>'Safety question answer:','type'=>'password','required'=>true)); ?>
        <?= $this->Form->control('password', array('label'=>'New password:','type'=>'password','required'=>true)); ?>
        <?= $this->Form->submit('Reset', array('class' => 'button')); ?>
    <?= $this->Form->end(); ?>
</div>

<?php
/**
 * @var AppView $this
 * @var User[]|CollectionInterface $users
 */

use App\Model\Entity\User;
use App\View\AppView;
use Cake\Collection\CollectionInterface;

?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8" />
    <script src="../webroot/js/form_check.js" defer></script>
    <title></title>
</head>
<body>
    <div>
        <h2>Register as a new user</h2>
        <?= $this->Form->create($user); ?>
        <span id="in"><?= $this->Form->control('name', array('required'=>true)); ?></span>
        <span id="errors"></span>
        <?= $this->Form->control('email', array('required'=>true)); ?>
        <?= $this->Form->control('answer', array('label'=>'Safety question: Your mother\'s maiden name','required'=>true,'type' => 'password')); ?>
        <?= $this->Form->control('password', array('required'=>true,'type' => 'password')); ?>
        <?= $this->Form->submit('Register', array('class' => 'button')); ?>
        <?= $this->Form->end(); ?>
    </div>
</body>
</html>

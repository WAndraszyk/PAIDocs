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
    <p>Welcome to PAIDocs - the best text data management application. Logged in users can create,
        read, modify and remove their text resources. Logged in users can also share their text resources
        to other users of the system or guests in read-only mode.</p><br>
    <h2>Log In</h2>
    <?= $this->Form->create(); ?>
        <?= $this->Form->control('name', array('required'=>true)); ?>
        <?= $this->Form->control('password', array('required'=>true,'type' => 'password')); ?>
        <div id="loginButtons">
            <?= $this->Form->submit('Log In', array('class' => 'button')); ?>
            <?= $this->Form->end(); ?>
            <a href="http://localhost/PAIDocs/users/forgot"><button type="button">I forgot my password</button></a>
        </div>
</div>

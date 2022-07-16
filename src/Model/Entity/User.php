<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\Entity;

/**
 * User Entity
 *
 * @property int $id
 * @property string $name
 * @property string $password
 * @property string $answer
 * @property string $email
 *
 * @property \App\Model\Entity\Resource[] $resources
 */
class User extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected $_accessible = [
        'name' => true,
        'password' => true,
        'answer' => true,
        'email' => true,
        'resources' => true,
        'id' => true
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array<string>
     */
    protected $_hidden = [
    ];

    protected function _setPassword($password){
        return (new DefaultPasswordHasher)->hash($password);
    }

    protected function _setAnswer($answer){
        return (new DefaultPasswordHasher)->hash($answer);
    }
}

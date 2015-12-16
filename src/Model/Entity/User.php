<?php

namespace UsersManager\Model\Entity;

use Cake\ORM\Entity;
use Cake\Auth\DefaultPasswordHasher;

/**
 * User Entity.
 *
 * @property int $id
 * @property string $firstname
 * @property string $lastname
 * @property string $email
 * @property string $password
 * @property bool $active
 * @property int $role_id
 * @property \UsersManager\Model\Entity\Role $role
 */
class User extends Entity {

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false,
    ];
    
    protected $_virtual = ['fullname'];

    protected function _setPassword($password) {
        return (new DefaultPasswordHasher)->hash($password);
    }

    protected function _getFullname() {
        return $this->firstname . " " . $this->lastname;
    }

}

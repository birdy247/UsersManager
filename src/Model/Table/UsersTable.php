<?php

namespace UsersManager\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use UsersManager\Model\Entity\User;

/**
 * Users Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Roles
 */
class UsersTable extends Table {

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->table('users');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsTo('Roles', [
            'foreignKey' => 'role_id',
            'joinType' => 'INNER',
            'className' => 'UsersManager.Roles'
        ]);

        $this->addBehavior('Proffer.Proffer', [
            'photo' => [// The name of your upload field
                'root' => WWW_ROOT . 'files', // Customise the root upload folder here, or omit to use the default
                'dir' => 'photo_dir', // The name of the field to store the folder
                'thumbnailSizes' => [// Declare your thumbnails
                    'square' => [// Define the prefix of your thumbnail
                        'w' => 500, // Width
                        'h' => 500, // Height
                        'crop' => true, // Crop will crop the image as well as resize it
                        'jpeg_quality' => 100,
                        'png_compression_level' => 9
                    ]
                ],
                'thumbnailMethod' => 'imagick'  // Options are Imagick, Gd or Gmagick
            ]
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator) {
        $validator
                ->add('id', 'valid', ['rule' => 'numeric'])
                ->allowEmpty('id', 'create');

        $validator
                ->requirePresence('firstname', 'create')
                ->notEmpty('firstname');

        $validator
                ->requirePresence('lastname', 'create')
                ->notEmpty('lastname');

        $validator
                ->add('email', 'valid', ['rule' => 'email'])
                ->requirePresence('email', 'create')
                ->notEmpty('email', 'This field is required', function ($context) {
                    return isset($context['data']['role_id']) && ($context['data']['role_id'] != 10);
                });

        $validator
                ->requirePresence('password', 'create')
                ->notEmpty('password', 'This field is required', function ($context) {
                    return isset($context['data']['role_id']) && ($context['data']['role_id'] != 10);
                });

        $validator
                ->requirePresence('photo', 'create')
                ->allowEmpty('photo', 'update');

        $validator
                ->add('active', 'valid', ['rule' => 'boolean'])
                ->requirePresence('active', 'create')
                ->notEmpty('active');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules) {
        // Add a rule that is applied for create and update operations
        $rules->add(function ($entity, $options) {

            if (!$entity->email && $entity->role_id == 10) {
                return true;
            }

            //If the email has been changed
            if ($entity->dirty('email')) {
                if ($this->exists(['Users.email' => $entity->email])) {
                    return false;
                }
                return true;
            }

            return true;
        }, [
            'uniqueName',
            'errorField' => 'email',
            'message' => 'You must provide an email address'
        ]);
        $rules->add($rules->existsIn(['role_id'], 'Roles'));
        return $rules;
    }

}

<?php
declare(strict_types=1);

namespace DataCenter\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Users Model
 *
 * @method \DataCenter\Model\Entity\User newEmptyEntity()
 * @method \DataCenter\Model\Entity\User newEntity(array $data, array $options = [])
 * @method \DataCenter\Model\Entity\User[] newEntities(array $data, array $options = [])
 * @method \DataCenter\Model\Entity\User get($primaryKey, $options = [])
 * @method \DataCenter\Model\Entity\User findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \DataCenter\Model\Entity\User patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \DataCenter\Model\Entity\User[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \DataCenter\Model\Entity\User|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \DataCenter\Model\Entity\User saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \DataCenter\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \DataCenter\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \DataCenter\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \DataCenter\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 * @method \DataCenter\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface findByEmail(string $email)
 * @method \DataCenter\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface findByToken(string $token)
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UsersTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('users');
        $this->setDisplayField(
            $this->hasField('name') ? 'name' : 'email'
        );
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        if ($this->hasField('name')) {
            $validator
                ->scalar('name')
                ->maxLength('name', 100)
                ->requirePresence('name', 'create')
                ->notEmptyString('name');
        }

        $validator
            ->email('email')
            ->requirePresence('email', 'create')
            ->notEmptyString('email');

        $validator
            ->scalar('password')
            ->maxLength('password', 64)
            ->requirePresence('password', 'create')
            ->notEmptyString('password');

        $validator = $this->validationPasswordConfirm($validator);

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->isUnique(['email']), ['errorField' => 'email']);

        return $rules;
    }

    /**
     * Adds rules for confirming a password
     *
     * @param \Cake\Validation\Validator $validator Cake validator object.
     * @return \Cake\Validation\Validator
     */
    public function validationPasswordConfirm(Validator $validator): Validator
    {
        $validator
            ->requirePresence('password_confirm', 'create')
            ->notBlank('password_confirm');

        $validator
            ->requirePresence('new_password', 'create')
            ->notBlank('new_password')
            ->add('new_password', [
                'password_confirm_check' => [
                    'rule' => ['compareWith', 'password_confirm'],
                    'message' => 'Those two passwords did not match',
                    'allowEmpty' => false,
                ]]);

        return $validator;
    }
}

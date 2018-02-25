<?php

namespace App\Repositories;

use App\ActionSequence;

/**
 * Class ActionSequenceRepository
 * @package App\Repositories
 */
class ActionSequenceRepository extends Repository {

    /**
     * ActionSequenceRepository constructor.
     * @param ActionSequence|null $scope
     */
    public function __construct(ActionSequence $scope = null)
    {

        $this->scope = $scope ? : new ActionSequence();
        $this->addCiliatusSpecificFields();

    }

    /**
     * @return ActionSequence
     */
    public function show()
    {
        return $this->scope;
    }

}

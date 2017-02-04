<?php

namespace App\Repositories;

use App\ActionSequenceIntention;

/**
 * Class ActionSequenceIntentionRepository
 * @package App\Repositories
 */
class ActionSequenceIntentionRepository extends Repository {

    /**
     * ActionSequenceIntentionRepository constructor.
     * @param null $scope
     */
    public function __construct(ActionSequenceIntention $scope = null)
    {

        $this->scope = $scope ? : new ActionSequenceIntention();

    }

    /**
     * @return ActionSequenceIntention
     */
    public function show()
    {
        $this->scope->running = $this->scope->running();
        $this->scope->intention_active = $this->scope->intention_active();

        $this->scope->icon = $this->scope->icon();
        $this->scope->url = $this->scope->url();

        return $this->scope;
    }

}

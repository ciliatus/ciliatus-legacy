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
     * @param ActionSequenceIntention|null $scope
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
        $this->scope->should_be_started = $this->scope->shouldBeStarted();

        $this->scope->icon = $this->scope->icon();
        $this->scope->url = $this->scope->url();

        return $this->scope;
    }

}

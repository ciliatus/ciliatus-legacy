<?php

namespace App\Traits;


use App\CiliatusModel;
use App\CriticalState;

/**
 * Trait HasCriticalStates
 * @package App\Traits
 */
trait HasCriticalStates
{

    /**
     * @return mixed
     */
    abstract public function getStateDetails();

    /**
     * @param $state
     * @param bool $soft
     * @return $this|\App\CiliatusModel|CriticalState|\Illuminate\Database\Eloquent\Model
     */
    public function createCriticalState($state, $soft = true)
    {
        return CriticalState::create([
            'belongsTo_type' => explode("\\",__CLASS__)[1],
            'belongsTo_id'   => $this->id,
            'is_soft_state'  => $soft,
            'state_details'  => $state
        ]);
    }

    /**
     * @param $state_details
     * @return mixed
     */
    public function getActiveCriticalStates($state_details)
    {
        return $this->critical_states()
                    ->whereNull('recovered_at')
                    ->where('state_details', $state_details)
                    ->get();
    }

    /**
     *
     */
    public function evaluate()
    {
        if (!$this->stateOk()) {
            foreach ($this->getStateDetails() as $state) {
                $existing_cs = $this->getActiveCriticalStates($state);

                if ($existing_cs->count() < 1) {
                    $this->createCriticalState($state, true);
                }
                else {
                    /**
                     * @var CriticalState $cs
                     */
                    foreach ($existing_cs as $cs) {
                        $cs->notifyIfNecessary($this);
                    }
                }
            }
        }
    }

    /**
     *
     */
    public static function evaluateCriticalStates()
    {
        /**
         * @var CiliatusModel $class
         */
        $class = __CLASS__;
        foreach ($class::get() as $component) {
            $component->evaluate();
        }
    }

}
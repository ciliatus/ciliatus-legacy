<?php

namespace App\Traits;


use App\CiliatusModel;
use App\CriticalState;
use Carbon\Carbon;

/**
 * Trait HasCriticalStates
 * @package App\Traits
 */
trait HasCriticalStates
{

    /**
     * @param bool $soft
     * @return $this|\App\CiliatusModel|CriticalState|\Illuminate\Database\Eloquent\Model
     */
    public function createCriticalState($soft = true)
    {
        return CriticalState::create([
            'belongsTo_type' => explode("\\",__CLASS__)[1],
            'belongsTo_id'   => $this->id,
            'is_soft_state'  => $soft
        ]);
    }

    /**
     * @return mixed
     */
    public function getActiveCriticalStates()
    {
        return $this->critical_states()->whereNull('recovered_at')->get();
    }

    /**
     *
     */
    public function evaluate()
    {
        if (!$this->stateOk()) {
            $existing_cs = $this->getActiveCriticalStates();

            if ($existing_cs->count() < 1) {
                $this->createCriticalState(true);
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
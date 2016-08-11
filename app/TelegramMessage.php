<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TelegramMessage
 * @package App
 */
class TelegramMessage extends Message
{
    use Traits\Uuids;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */

    public $incrementing = false;

    /**
     * @var string
     */
    protected $table = 'messages';

    /**
     * @return string
     */
    public function icon()
    {
        // TODO: Implement icon() method.
    }

    /**
     *
     */
    public function url()
    {
        // TODO: Implement url() method.
    }

    /**
     * @return mixed
     */
    public function send()
    {
        $this->state = 'sending';
        $this->save(['silent']);


    }
}

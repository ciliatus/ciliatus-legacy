<?php

namespace App;

/**
 * Class Component
 * @package App
 */
abstract class Component extends CiliatusModel
{
    use Traits\Uuids;

    /**
     * Is used to select the matching translation string and user setting for notifications
     *
     * @var string
     */
    protected $notification_type_name = 'generic';

    /**
     * @param $type
     */
    public function sendNotifications($type)
    {
        $users = User::getWhereNotificationsEnabled($this->notification_type_name)->get();

        foreach ($users as $user) {
            $user->message($this->getCriticalStateNotificationsText($type, $user->locale));
        }
    }

    /**
     * @param $type
     * @param $locale
     * @return array|\Illuminate\Contracts\Translation\Translator|null|string
     */
    abstract protected function getCriticalStateNotificationsText($type, $locale);
}

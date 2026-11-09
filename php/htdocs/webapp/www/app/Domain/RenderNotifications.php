<?php

namespace App\Domain;

use App\Data\Notification;

class RenderNotifications {

    private $notifications;

    public function __construct($notifications = []) {
        $this->notifications = $notifications;
    }

    public function setNotifications($notifications) {
        $this->notifications = $notifications;
    }

    public function render(): array {
        $notifications = [];

        foreach ($this->notifications as $item) {
            $notification = new Notification($item);

            $url = $notification->getUrl();
            $anchor = $notification->getAnchor();
            $mode = $notification->getMode();
            $text = $notification->getText();
            $type = $notification->getType();

            $link = "<a class='notifa' href='$url' data-anchor='$anchor' data-type='$type' target='_blank'>";
            $link .= "<li class='$mode'>$text</li>";
            $link .= "</a>";

            $notifications[] = $link;
        }

        return $notifications;
    }

    public function renderDemo(): array {
        $notifications = [];

        foreach ($this->notifications as $item) {
            $notification = new Notification($item);

            $url = $notification->getDemoUrl();
            $mode = $notification->getMode();
            $text = $notification->getText();

            $link = "<a class='notifa' href='$url' target='_blank'>";
            $link .= "<li class='$mode'>$text</li>";
            $link .= "</a>";

            $notifications[] = $link;
        }

        return $notifications;
    }
}
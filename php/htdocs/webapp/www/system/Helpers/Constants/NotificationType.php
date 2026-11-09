<?php

namespace Helpers\Constants;

class NotificationType {
    const STARTED = 'started'; // A drafter is ready to be checked
    const READY   = 'ready'; // A checker is ready for checking
    const DONE    = 'done'; // A checker has approved the draft
}
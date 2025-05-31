<?php
// Prevent direct access
if (!defined('SECURE_ACCESS')) {
    die('Direct access not permitted');
}

return [
    'whatsapp' => [
        'instanceId' => '',
        'token' => '',
        'endpoint_base' => 'https://waapi.app/api/v1/instances/{instance_id}/client/action/send-message'
    ]
];
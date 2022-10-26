<?php

return [

    'create' => [
        'error'   => 'The host was not created. The application encountered an internal error, if the problem persist, contact your administrator.',
        'success' => 'The host \':hostname\' was created successfully.',
    ],
    'edit' => [
        'error'   => 'The host was not updated. The application encountered an internal error, if the problem persist, contact your administrator.',
        'success' => 'The host \':hostname\' was updated successfully.',
    ],
    'delete' => [
        'error'   => 'The host was not deleted. The application encountered an internal error, if the problem persist, contact your administrator.',
        'success' => 'The host \':hostname\' was deleted successfully.',
    ],

    'groups_help'   => 'Please select the groups where this host belongs to',
    'groups_empty'  => 'This host does not belong to any group.',
    'never_rotated' => 'Never',

    'custom-port-check' => 'Use custom port',
    'custom-port-help'  => 'Overrides the value configured in <a href=":url">settings</a> (default value is <code>:default-value</code>).',
    'custom-path-check' => 'Use custom path',
    'custom-path-help'  => 'Overrides the value configured in <a href=":url">settings</a> (default value is <code>:default-value</code>).',

    'danger_zone_section'         => 'Danger Zone',
    'delete_button'               => 'Delete this host',
    'delete_host_help'            => 'Once you delete a host, there is no going back. Please be certain.',
    'delete_confirmation_warning' => 'This action <strong>cannot</strong> be undone. This will permanently delete the <strong>:hostname</strong> host and remove all group associations.',
    'delete_confirmation_button'  => 'I understand the consequences, delete this host',
    'delete_avoided'              => 'You are not allowed to delete this host.',
];

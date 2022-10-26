<?php

return [

    'username_help'       => 'For security reasons, username cannot be changed once added.',
    'roles_help'          => 'Select a role to assign to the user, remember that a user takes on the permissions of the role they are assigned.',
    'edit_password_help'  => 'If you would like to change the password type a new one. Otherwise leave it blank.',
    'edit_status_avoided' => 'You are not allowed to disable this user.',
    'delete_avoided'      => 'You are not allowed to delete this user.',

    'create' => [
        'error'   => 'User was not created, please try again.',
        'success' => 'The user \':name\' was created successfully.',
    ],
    'edit' => [
        'incorrect_password'          => 'Your current password does not match with the stored one.',
        'disabled_status_not_allowed' => 'You cannot disable your own account.',
        'role_change_not_allowed'     => 'You cannot change your own role.',
        'error'                       => 'There was an issue editing the user. Please try again.',
        'success'                     => 'The user \':name\' was edited successfully.',
    ],
    'delete' => [
        'impossible' => 'You cannot delete yourself.',
        'error'      => 'There was an issue deleting the user. Please try again.',
        'success'    => 'The user \':name\' was deleted successfully.',
    ],

    'danger_zone_section'         => 'Danger Zone',
    'delete_button'               => 'Delete this user',
    'delete_help'                 => 'Once you delete a user, there is no going back. Please be certain.',
    'delete_confirmation_warning' => 'This action <strong>cannot</strong> be undone. This will permanently delete the <strong>:username</strong> user.',
    'delete_confirmation_button'  => 'I understand the consequences, delete this user',

];

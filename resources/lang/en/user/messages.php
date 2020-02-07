<?php

return [

    'username_help' => 'For security reasons, username cannot be changed once added.',
    'roles_help' => 'Select a role to assign to the user, remember that a user takes on the permissions of the role they are assigned.',
    'edit_password_help' => 'If you would like to change the password type a new one. Otherwise leave it blank.',

    'create' => [
        'error' => 'User was not created, please try again.',
        'success' => 'The user \':name\' was created successfully.',
    ],
    'edit' => [
        'impossible' => 'You cannot edit yourself.',
        'error' => 'There was an issue editing the user. Please try again.',
        'success' => 'The user \':name\' was edited successfully.',
    ],
    'delete' => [
        'impossible' => 'You cannot delete yourself.',
        'error' => 'There was an issue deleting the user. Please try again.',
        'success' => 'The user \':name\' was deleted successfully.'
    ],

];

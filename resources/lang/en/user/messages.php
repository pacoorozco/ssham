<?php

return [

    'roles_help' => 'Select a role to assign to the user, remember that a user takes on the permissions of the role they are assigned.',
    'groups_help' => 'Please select the groups where this user belongs to',
    'create_rsa_key' => 'Create a new RSA private / public key',
    'create_rsa_key_help' => "A new RSA key pair will be generated. The private key <strong>must be downloaded and delivered securely</strong> to the user",
    'create_rsa_key_help_notice' => '<i class="fa fa-warning"></i> The current public key will be revoked. Please act with caution',
    'import_rsa_key' => 'Import / edit RSA public key',
    'import_rsa_key_help' => 'Please, copy RSA public key content',

    'create' => [
        'error' => 'User was not created, please try again.',
        'success' => 'User created successfully.',
        'success_private' => 'User created successfully. You must download RSA private key using :url'
    ],
    'edit' => [
        'impossible' => 'You cannot edit yourself.',
        'error' => 'There was an issue editing the user. Please try again.',
        'success' => 'The user was edited successfully.',
        'success_private' => 'User edited successfully. You must download RSA private key using :url'
    ],
    'delete' => [
        'impossible' => 'You cannot delete yourself.',
        'error' => 'There was an issue deleting the user. Please try again.',
        'success' => 'The user was deleted successfully.'
    ],

];

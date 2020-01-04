<?php

return [

    'username_help' => 'For security reasons, username cannot be changed once added.',

    'roles_help' => 'Select a role to assign to the user, remember that a user takes on the permissions of the role they are assigned.',
    'groups_help' => 'Please select the groups where this user belongs to',
    'create_public_key' => 'Create a new RSA private / public key',
    'create_public_key_help' => 'A new RSA key pair will be generated. The private key <strong>must be downloaded and delivered securely</strong> to the user.',
    'change_public_key_help_notice' => 'The current public key will be revoked. Please act with caution',
    'maintain_public_key' => 'Maintain current RSA public key',
    'import_public_key' => 'Import RSA public key',
    'import_public_key_help' => 'Do not paste the private part of the SSH key. Paste the public part, which is usually beginning with `ssh-rsa`.',

    'edit_password_help' => 'If you would like to change the password type a new one. Otherwise leave it blank.',

    'create' => [
        'error' => 'User was not created, please try again.',
        'success' => 'User created successfully.',
        'success_private' => 'User created successfully. You must download RSA private key using <a href=":url">this link</a>.'
    ],
    'edit' => [
        'impossible' => 'You cannot edit yourself.',
        'error' => 'There was an issue editing the user. Please try again.',
        'success' => 'The user was edited successfully.',
        'success_private' => 'User edited successfully. You must download RSA private key using <a href=":url">this link</a>.'
    ],
    'delete' => [
        'impossible' => 'You cannot delete yourself.',
        'error' => 'There was an issue deleting the user. Please try again.',
        'success' => 'The user was deleted successfully.'
    ],

];

<?php
/**
 * SSH Access Manager - SSH keys management solution.
 *
 * Copyright (c) 2017 - 2020 by Paco Orozco <paco@pacoorozco.info>
 *
 *  This file is part of some open source application.
 *
 *  Licensed under GNU General Public License 3.0.
 *  Some rights reserved. See LICENSE, AUTHORS.
 *
 * @author      Paco Orozco <paco@pacoorozco.info>
 * @copyright   2017 - 2020 Paco Orozco
 * @license     GPL-3.0 <http://spdx.org/licenses/GPL-3.0>
 * @link        https://github.com/pacoorozco/ssham
 */

return [

    'username_help' => 'For security reasons, username cannot be changed once added.',

    'groups_help' => 'Please select the groups where this user belongs to',
    'create_public_key' => 'Create a new RSA private / public key',
    'create_public_key_help' => 'A new RSA key pair will be generated. The private key <strong>must be downloaded and delivered securely</strong> to the user.',
    'change_public_key_help_notice' => 'The current public key will be revoked. Please act with caution',
    'maintain_public_key' => 'Maintain current RSA public key',
    'import_public_key' => 'Import RSA public key',
    'import_public_key_help' => 'Do not paste the private part of the SSH key. Paste the public part, which is usually beginning with `ssh-rsa`.',

    'create' => [
        'error' => 'Key was not created, please try again.',
        'success' => 'Key created successfully.',
        'success_private' => 'Key created successfully. You must download RSA private key using <a href=":url">this link</a>.'
    ],
    'edit' => [
        'error' => 'There was an issue editing the key. Please try again.',
        'success' => 'The key was edited successfully.',
        'success_private' => 'Key edited successfully. You must download RSA private key using <a href=":url">this link</a>.'
    ],
    'delete' => [
        'error' => 'There was an issue deleting the key. Please try again.',
        'success' => 'The key was deleted successfully.'
    ],

];

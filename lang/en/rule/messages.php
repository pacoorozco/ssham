<?php

return [
    'create' => [
        'error'   => 'Rule was not created, please try again.',
        'success' => 'The Rule \'#:rule\' was created successfully.',
    ],
    'edit' => [
        'error'   => 'There was an issue editing the rule. Please try again.',
        'success' => 'The rule \'#:rule\' was edited successfully.',
    ],
    'delete' => [
        'error'   => 'There was an issue deleting the rule. Please try again.',
        'success' => 'The rule \'#:rule\' was deleted successfully.',
    ],

    'confirmation_title'          => 'Are you absolutely sure?',
    'confirmation_help'           => 'Please type <strong>:confirmationText</strong> to confirm.',
    'delete_confirmation_warning' => 'This action <strong>cannot</strong> be undone. This will delete the rule and mark all related hosts as pending to be synced.',
    'delete_button'               => 'Delete this rule',
];

<?php

return [
    'create' => [
        'error' => 'Group was not created, please try again.',
        'success' => 'The group \':name\' was created successfully.',
    ],
    'edit' => [
        'error' => 'There was an issue editing the group. Please try again.',
        'success' => 'The group \':name\' was edited successfully.',
    ],
    'delete' => [
        'error' => 'There was an issue deleting the group. Please try again.',
        'success' => 'The group \':name\' was deleted successfully.',
    ],

    'basic_information_section' => 'Basic information',
    'group_members_section' => 'Group members',

    'name_help' => 'Short name of the group. It will be shown in the rules.',
    'description_help' => 'What does define this group? It will help others to understand what this group is for.',
    'group_help' => 'Select which SSH keys belongs to this group.',

    'available_keys_section' => 'Available keys',
    'selected_keys_section' => 'Selected keys',

    'danger_zone_section' => 'Danger Zone',
    'delete_button' => 'Delete this group',
    'delete_help' => 'Once you delete a group, there is no going back. Please be certain.',
    'delete_confirmation_warning' => 'This action <strong>cannot</strong> be undone. This will permanently delete the <strong>:name</strong> group and remove all related rules.',
    'delete_confirmation_button' => 'I understand the consequences, delete this group',

    'keys_count' => '{0} :count keys|{1} :count key|[2,*] :count keys',

    'delete_avoided' => 'You are not allowed to delete this group.',
];

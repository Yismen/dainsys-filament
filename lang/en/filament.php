<?php

return [
    /*
    |--------------------------------------------------------------------------
    | All Translations - Flat Structure (No Prefixes)
    |--------------------------------------------------------------------------
    */
    'id' => 'ID',
    'name' => 'Name',
    'description' => 'Description',
    'email' => 'Email',
    'password' => 'Password',
    'phone' => 'Phone',
    'cellphone' => 'Cellphone',
    'address' => 'Address',
    'date' => 'Date',
    'start_date' => 'Start Date',
    'end_date' => 'End Date',
    'created_at' => 'Created At',
    'updated_at' => 'Updated At',
    'deleted_at' => 'Deleted At',
    'status' => 'Status',
    'type' => 'Type',
    'comment' => 'Comment',
    'gender' => 'Gender',
    'is_active' => 'Is Active',
    'notes' => 'Notes',

    // Employee fields
    'first_name' => 'First Name',
    'second_first_name' => 'Second First Name',
    'last_name' => 'Last Name',
    'second_last_name' => 'Second Last Name',
    'personal_id_type' => 'Personal ID Type',
    'personal_id' => 'Personal ID',
    'date_of_birth' => 'Date of Birth',
    'secondary_phone' => 'Secondary Phone',
    'has_kids' => 'Has Kids',
    'citizenship' => 'Citizenship',
    'profile_photo' => 'Photo',
    'internal_id' => 'Internal ID',
    'full_name' => 'Full Name',

    // Relations
    'site' => 'Site',
    'project' => 'Project',
    'supervisor' => 'Supervisor',
    'position' => 'Position',
    'employee' => 'Employee',
    'user' => 'User',
    'department' => 'Department',

    // Bank account
    'bank_account_information' => 'Bank Account Information',
    'bank' => 'Bank',
    'account' => 'Account Number',

    // Social security
    'social_security_information' => 'Social Security Information',
    'afp' => 'AFP',
    'ars' => 'ARS',
    'tss_number' => 'TSS Number',
    'is_universal' => 'Is Universal Employee',

    // Hiring
    'hiring_information' => 'Hiring Information',
    'job_information' => 'Job Information',
    'hired_at' => 'Hired Date',
    'date_since' => 'Date Since',

    // History sections
    'suspensions_history' => 'Suspensions History',
    'hires_history' => 'Hires History',
    'terminations_history' => 'Terminations History',
    'last_30_days_absences' => 'Last 30 Days Absences',

    // History columns
    'starts_at' => 'Starts At',
    'ends_at' => 'Ends At',
    'duration_days' => 'Duration (Days)',
    'suspension_type' => 'Suspension Type',
    'termination_type' => 'Termination Type',
    'absence_type' => 'Absence Type',
    'is_rehirable' => 'Is Rehirable',

    // HR-specific
    'salary_type' => 'Salary Type',
    'salary' => 'Salary',
    'person_of_contact' => 'Person of Contact',
    'geolocation' => 'Geolocation',
    'activity_type' => 'Activity Type',
    'requested_at' => 'Requested At',
    'reported_by' => 'Reported By',

    // Additional
    'date_range' => 'Date Range',
    'date_from' => 'Date from',
    'date_until' => 'Date until',
    'rehireable' => 'Rehireable',
    'not_rehireable' => 'Not rehireable',

    // Additional table columns
    'is_active' => 'Active',
    'number' => 'Number',
    'action' => 'Action',
    'date_of_birth' => 'Date of Birth',
    'employees' => 'Employees',
    'completed_at' => 'Completed At',
    'roles' => 'Roles',
    'email_verified' => 'Email Verified',
    'verified' => 'Verified',
    'not_verified' => 'Not Verified',
    'has_employee_id' => 'Has Employee ID',
    'no_employee_id' => 'No Employee ID',

    /*
    |--------------------------------------------------------------------------
    | App Configuration
    |--------------------------------------------------------------------------
    */
    'app' => [
        'name' => 'Dainsys',
        'description' => 'Human Resource Management System',
    ],

    'navigation' => [
        'dashboard' => 'Dashboard',
        'admin' => 'Admin',
        'clients' => 'Clients',
        'employees' => 'Employees',
        'human_resources' => 'Human Resources',
        'invoicing' => 'Invoicing',
        'support' => 'Support',
        'settings' => 'Settings',
    ],

    'resources' => [
        'User' => ['label' => 'User', 'plural_label' => 'Users'],
        'Employee' => ['label' => 'Employee', 'plural_label' => 'Employees'],
        'Role' => ['label' => 'Role', 'plural_label' => 'Roles'],
        'Permission' => ['label' => 'Permission', 'plural_label' => 'Permissions'],
    ],

    'buttons' => [
        'save' => 'Save',
        'create' => 'Create',
        'update' => 'Update',
        'delete' => 'Delete',
        'cancel' => 'Cancel',
        'confirm' => 'Confirm',
        'submit' => 'Submit',
        'back' => 'Back',
        'next' => 'Next',
        'previous' => 'Previous',
        'search' => 'Search',
        'filter' => 'Filter',
        'export' => 'Export',
        'import' => 'Import',
        'refresh' => 'Refresh',
    ],

    'messages' => [
        'saved' => 'Saved successfully',
        'deleted' => 'Deleted successfully',
        'created' => 'Created successfully',
        'updated' => 'Updated successfully',
        'error' => 'An error occurred',
        'loading' => 'Loading...',
        'no_results' => 'No results found',
        'confirm_delete' => 'Are you sure you want to delete this?',
        'required' => 'This field is required',
    ],

    'actions' => [
        'view' => 'View',
        'edit' => 'Edit',
        'delete' => 'Delete',
        'create' => 'Create',
        'replicate' => 'Replicate',
        'restore' => 'Restore',
        'force_delete' => 'Force Delete',
    ],

    'filters' => [
        'all' => 'All',
        'active' => 'Active',
        'inactive' => 'Inactive',
        'trashed' => 'Trashed',
    ],

    'statuses' => [
        'active' => 'Active',
        'inactive' => 'Inactive',
        'pending' => 'Pending',
        'suspended' => 'Suspended',
        'terminated' => 'Terminated',
    ],
];

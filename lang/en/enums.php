<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Enum Translations
    |--------------------------------------------------------------------------
    */
    'termination' => [
        'resignation' => 'Resignation',
        'termination' => 'Termination (without cause)',
        'firing' => 'Firing',
        'abandonment' => 'Abandonment',
        'dismissing' => 'Dismissing',
        'resignation_description' => 'The employee communicated their desire to terminate the contract.',
        'termination_description' => 'The company exercised the termination of the contract without cause.',
        'firing_description' => 'The employee committed a heavy fault, causing their termination.',
        'abandonment_description' => 'Multiple unjustified absences.',
        'dismissing_description' => 'The employee sued the company.',
    ],

    'employee_status' => [
        'created' => 'Created',
        'hired' => 'Hired',
        'suspended' => 'Suspended',
        'terminated' => 'Terminated',
    ],

    'downtime_status' => [
        'pending' => 'Pending',
        'approved' => 'Approved',
        'rejected' => 'Rejected',
    ],

    'absence_type' => [
        'justified' => 'Justified',
        'unjustified' => 'Unjustified',
    ],

    'absence_status' => [
        'created' => 'Created',
        'reported' => 'Reported',
    ],

    'suspension_status' => [
        'pending' => 'Pending',
        'current' => 'Current',
        'completed' => 'Completed',
    ],

    'gender' => [
        'male' => 'Male',
        'female' => 'Female',
    ],

    'personal_id_type' => [
        'dominican_id' => 'Dominican ID',
        'passport' => 'Passport',
    ],

    'salary_type' => [
        'salary' => 'Salary',
        'hourly' => 'Hourly',
        'by_sales' => 'By Sales',
    ],

    'revenue_type' => [
        'downtime' => 'Downtime',
        'login_time' => 'Login Time',
        'production_time' => 'Production Time',
        'talk_time' => 'Talk Time',
        'conversions' => 'Conversions',
    ],

    'campaign_source' => [
        'chat' => 'Chat',
        'email' => 'Email',
        'inbound' => 'Inbound',
        'outbound' => 'Outbound',
        'qa_review' => 'QA Review',
        'resubmissions' => 'Resubmissions',
        'training' => 'Training',
    ],

    'hr_activity_type' => [
        'vacations' => 'Vacations',
        'permission' => 'Permission',
        'employment_letter' => 'Employment Letter',
        'loan' => 'Loan',
        'uniform' => 'Uniform',
        'counseling' => 'Counseling',
        'interview' => 'Interview',
    ],

    'hr_activity_request_status' => [
        'requested' => 'Requested',
        'in_progress' => 'In Progress',
        'completed' => 'Completed',
        'cancelled' => 'Cancelled',
    ],

    'evaluation_status' => [
        'draft' => 'Draft',
        'published' => 'Published',
        'accepted_closed' => 'Accepted & Closed',
        'disputed' => 'Disputed',
        'rejected' => 'Rejected',
    ],

    'ticket_status' => [
        'pending' => 'Not Assigned',
        'pending_expired' => 'Expired Before Assignment',
        'in_progress' => 'Assigned to User',
        'in_progress_expired' => 'Expired and Assigned',
        'completed' => 'Completed in Time',
        'completed_expired' => 'Completed After Expiring',
    ],

    'ticket_priority' => [
        'normal' => 'Normal',
        'medium' => 'Medium',
        'high' => 'High',
        'emergency' => 'Emergency',
    ],

    'article_status' => [
        'draft' => 'Draft',
        'published' => 'Published',
    ],

    'qa_role' => [
        'manager' => 'Quality Assurance Manager',
        'agent' => 'Quality Assurance Agent',
    ],

    'support_role' => [
        'manager' => 'Support Manager',
        'agent' => 'Support Agent',
    ],

    'invoice_status' => [
        'pending' => 'Pending',
        'partially_paid' => 'Partially Paid',
        'paid' => 'Paid',
        'overdue' => 'Overdue',
        'cancelled' => 'Cancelled',
    ],
];

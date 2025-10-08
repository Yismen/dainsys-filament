<?php

use App\Enums\CampaignSources;

test('values method return specific values', function () {
    expect(CampaignSources::values())->toEqual([
        'Chat',
        'Email',
        'Inbound',
        'Outbound',
        'QAReview',
        'Resubmissions',
        'Training',
    ]);
});

test('all method return associative array', function () {
    expect(CampaignSources::toArray())->toEqual([
        'Chat' => 'Chat',
        'Email' => 'Email',
        'Inbound' => 'Inbound',
        'Outbound' => 'Outbound',
        'QAReview' => 'QAReview',
        'Resubmissions' => 'Resubmissions',
        'Training' => 'Training',
    ]);
});

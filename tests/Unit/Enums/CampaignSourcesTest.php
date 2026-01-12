<?php

use App\Enums\CampaignSources;

test('names method return specific names', function () {
    expect(CampaignSources::names())->toEqual([
        'Chat',
        'Email',
        'Inbound',
        'Outbound',
        'QAReview',
        'Resubmissions',
        'Training',
    ]);
});

test('values method return specific values', function () {
    expect(CampaignSources::values())->toEqual([
        'Chat',
        'Email',
        'Inbound',
        'Outbound',
        'QA Review',
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
        'QA Review' => 'QAReview',
        'Resubmissions' => 'Resubmissions',
        'Training' => 'Training',
    ]);
});

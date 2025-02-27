<?php

namespace App\Listeners;

use Illuminate\Mail\Attachment;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Mime\Part\DataPart;

class RemoveMailAttachment
{
    public function handle(MessageSent $event)
    {
        foreach ($event->message->getAttachments() as $file) {
            if ($file instanceof DataPart || $file instanceof Attachment) {
                if (method_exists($file, 'getFileName')) {
                    $filename = $file->getFilename();
                    if (Storage::exists($filename)) {
                        Storage::delete($filename);
                    }
                }
            }
        }
    }
}

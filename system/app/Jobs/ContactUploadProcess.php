<?php
namespace App\Jobs;

use App\Models\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Bus\Batchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ContactImport;

class ContactUploadProcess implements ShouldQueue
{

    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $header;
    public $data;

    public function __construct($data, $header)
    {
        $this->data = $data;
        $this->header = $header;
    }

    public function handle()
    {
        foreach ($this->data as $contact) {
            $contactData = array_combine($this->header,$contact);
            Contact::create($contactData);
        }
    }
}
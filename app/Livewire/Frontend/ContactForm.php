<?php

namespace App\Livewire\Frontend;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class ContactForm extends Component
{
    public string $name = '';

    public string $email = '';

    public string $subject = '';

    public string $message = '';

    public bool $submitted = false;

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ];
    }

    public function submit(): void
    {
        $this->validate();

        $this->submitted = true;

        $this->reset('name', 'email', 'subject', 'message');
    }

    public function render(): View
    {
        return view('livewire.frontend.contact-form');
    }
}

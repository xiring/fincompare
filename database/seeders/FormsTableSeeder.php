<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Src\Forms\Domain\Entities\Form;
use Src\Forms\Domain\Entities\FormInput;

class FormsTableSeeder extends Seeder
{
    public function run(): void
    {
        // Pre Form Example
        $preForm = Form::firstOrCreate(
            ['slug' => 'contact-pre-form'],
            [
                'name' => 'Contact Pre Form',
                'slug' => 'contact-pre-form',
                'description' => 'Pre-form fields for contact submissions',
                'status' => 'active',
                'type' => 'pre_form',
            ]
        );

        if ($preForm->wasRecentlyCreated) {
            FormInput::create([
                'form_id' => $preForm->id,
                'label' => 'Full Name',
                'name' => 'full_name',
                'type' => 'text',
                'placeholder' => 'Enter your full name',
                'is_required' => true,
                'sort_order' => 1,
            ]);

            FormInput::create([
                'form_id' => $preForm->id,
                'label' => 'Email Address',
                'name' => 'email',
                'type' => 'text',
                'placeholder' => 'Enter your email',
                'help_text' => 'We will never share your email',
                'is_required' => true,
                'validation_rules' => 'email|max:255',
                'sort_order' => 2,
            ]);

            FormInput::create([
                'form_id' => $preForm->id,
                'label' => 'Phone Number',
                'name' => 'phone',
                'type' => 'text',
                'placeholder' => 'Enter your phone number',
                'is_required' => false,
                'sort_order' => 3,
            ]);

            FormInput::create([
                'form_id' => $preForm->id,
                'label' => 'Message',
                'name' => 'message',
                'type' => 'textarea',
                'placeholder' => 'Enter your message',
                'is_required' => true,
                'sort_order' => 4,
            ]);
        }

        // Post Form Example
        $postForm = Form::firstOrCreate(
            ['slug' => 'feedback-post-form'],
            [
                'name' => 'Feedback Post Form',
                'slug' => 'feedback-post-form',
                'description' => 'Post-form feedback collection',
                'status' => 'active',
                'type' => 'post_form',
            ]
        );

        if ($postForm->wasRecentlyCreated) {
            FormInput::create([
                'form_id' => $postForm->id,
                'label' => 'How satisfied are you?',
                'name' => 'satisfaction',
                'type' => 'dropdown',
                'options' => ['Very Satisfied', 'Satisfied', 'Neutral', 'Dissatisfied', 'Very Dissatisfied'],
                'is_required' => true,
                'sort_order' => 1,
            ]);

            FormInput::create([
                'form_id' => $postForm->id,
                'label' => 'Would you recommend us?',
                'name' => 'recommend',
                'type' => 'checkbox',
                'help_text' => 'Check if you would recommend our service',
                'is_required' => false,
                'sort_order' => 2,
            ]);

            FormInput::create([
                'form_id' => $postForm->id,
                'label' => 'Additional Comments',
                'name' => 'comments',
                'type' => 'textarea',
                'placeholder' => 'Any additional feedback?',
                'is_required' => false,
                'sort_order' => 3,
            ]);
        }

        // Another Pre Form Example
        $loanPreForm = Form::firstOrCreate(
            ['slug' => 'loan-application-pre-form'],
            [
                'name' => 'Loan Application Pre Form',
                'slug' => 'loan-application-pre-form',
                'description' => 'Pre-form fields for loan applications',
                'status' => 'active',
                'type' => 'pre_form',
            ]
        );

        if ($loanPreForm->wasRecentlyCreated) {
            FormInput::create([
                'form_id' => $loanPreForm->id,
                'label' => 'Loan Amount',
                'name' => 'loan_amount',
                'type' => 'text',
                'placeholder' => 'Enter desired loan amount',
                'help_text' => 'Minimum amount: $1,000',
                'is_required' => true,
                'validation_rules' => 'numeric|min:1000',
                'sort_order' => 1,
            ]);

            FormInput::create([
                'form_id' => $loanPreForm->id,
                'label' => 'Loan Purpose',
                'name' => 'loan_purpose',
                'type' => 'dropdown',
                'options' => ['Home Improvement', 'Debt Consolidation', 'Medical Expenses', 'Education', 'Business', 'Other'],
                'is_required' => true,
                'sort_order' => 2,
            ]);

            FormInput::create([
                'form_id' => $loanPreForm->id,
                'label' => 'Employment Status',
                'name' => 'employment_status',
                'type' => 'dropdown',
                'options' => ['Employed', 'Self-Employed', 'Unemployed', 'Retired', 'Student'],
                'is_required' => true,
                'sort_order' => 3,
            ]);

            FormInput::create([
                'form_id' => $loanPreForm->id,
                'label' => 'Monthly Income',
                'name' => 'monthly_income',
                'type' => 'text',
                'placeholder' => 'Enter your monthly income',
                'is_required' => true,
                'validation_rules' => 'numeric|min:0',
                'sort_order' => 4,
            ]);
        }
    }
}


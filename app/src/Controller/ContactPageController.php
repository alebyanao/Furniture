<?php

use SilverStripe\Control\Email\Email;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\TextareaField;
use SilverStripe\Forms\EmailField;
use SilverStripe\Forms\Form;
use SilverStripe\Forms\FormAction;
use SilverStripe\Forms\RequiredFields;
use SilverStripe\SiteConfig\SiteConfig;
use SilverStripe\Security\Security;

class ContactPageController extends PageController
{
    private static $allowed_actions = [
        'ContactForm',
        'doSubmit'
    ];

    public function ContactForm()
    {
        $fields = FieldList::create(
            TextField::create('Name', 'Nama')
                ->setAttribute('placeholder', 'Masukkan nama lengkap')
                ->addExtraClass('form-control'),

            EmailField::create('Email', 'Email')
                ->setAttribute('placeholder', 'Masukkan alamat email')
                ->addExtraClass('form-control'),

            TextareaField::create('Message', 'Pesan')
                ->setAttribute('placeholder', 'Tulis pesan Anda di sini...')
                ->addExtraClass('form-control')
        );

        $actions = FieldList::create(
            FormAction::create('doSubmit', 'Kirim')
                ->addExtraClass('btn btn-primary w-100')
        );

        $required = RequiredFields::create('Name', 'Email', 'Message');

        $form = Form::create($this, 'ContactForm', $fields, $actions, $required);
        $form->addExtraClass('needs-validation');

        return $form;
    }

    public function doSubmit($data, Form $form)
    {
        $config = SiteConfig::current_site_config();
        $to = $config->CompanyEmail;

        if (!$to) {
            $form->sessionMessage('Company Email belum diatur di SiteConfig', 'bad');
            return $this->redirectBack();
        }

        // Tentukan email pengirim
        $member = Security::getCurrentUser();
        if ($member && $member->Email) {
            $from = $member->Email; // email user yang login
        } else {
            $from = $data['Email']; // email dari input form
        }

        $subject = 'Pesan Kontak dari ' . $data['Name'];
        $body = sprintf(
            "Nama: %s\nEmail: %s\n\nPesan:\n%s",
            $data['Name'],
            $data['Email'],
            $data['Message']
        );

        $email = Email::create()
            ->setTo($to)
            ->setFrom($from)
            ->setSubject($subject)
            ->setBody(nl2br($body));

        // tambahkan reply-to supaya admin bisa langsung balas
        $email->setReplyTo($data['Email']);

        $email->send();

        $form->sessionMessage('Pesan berhasil dikirim. Terima kasih sudah menghubungi kami!', 'good');
        return $this->redirectBack();
    }
}

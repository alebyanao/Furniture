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
use SilverStripe\Control\HTTPResponse;

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
        try {
            $config = SiteConfig::current_site_config();
            $to = $config->CompanyEmail;

            if (!$to) {
                return $this->redirect($this->Link() . '?error=' . urlencode('Company Email belum diatur di SiteConfig'));
            }

            // Validasi email format
            if (!filter_var($data['Email'], FILTER_VALIDATE_EMAIL)) {
                return $this->redirect($this->Link() . '?error=' . urlencode('Format email tidak valid'));
            }

            $from = $data['Email'];
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
                ->setReplyTo($data['Email'])
                ->setSubject($subject)
                ->setBody(nl2br($body));

            // Kirim email - asumsi selalu berhasil kecuali ada exception
            $email->send();

            // Jika sampai sini berarti tidak ada exception, anggap berhasil
            return $this->redirect($this->Link() . '?success=' . urlencode('Pesan berhasil dikirim. Terima kasih sudah menghubungi kami!'));

        } catch (Exception $e) {
            // Log error
            user_error('Contact form error: ' . $e->getMessage(), E_USER_WARNING);
            return $this->redirect($this->Link() . '?error=' . urlencode('Maaf, terjadi kesalahan sistem. Silakan coba lagi nanti.'));
        }
    }

    // Method untuk mengambil pesan dari URL parameter
    public function getAlertMessage()
    {
        $request = $this->getRequest();
        
        if ($success = $request->getVar('success')) {
            return urldecode($success);
        }
        
        if ($error = $request->getVar('error')) {
            return urldecode($error);
        }
        
        return null;
    }

    public function getAlertType()
    {
        $request = $this->getRequest();
        
        if ($request->getVar('success')) {
            return 'success';
        }
        
        if ($request->getVar('error')) {
            return 'danger';
        }
        
        return null;
    }
}
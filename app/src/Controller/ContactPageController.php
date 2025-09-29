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
use SilverStripe\Forms\LiteralField;

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
                ->addExtraClass('form-control'),

            // reCAPTCHA manual HTML
            LiteralField::create('RecaptchaHTML', 
                '<div class="g-recaptcha" data-sitekey="6Le_X9grAAAAAE7KZbpJHGw8EzGSsVHy8dinfgLx"></div>
                <script src="https://www.google.com/recaptcha/api.js" async defer></script>'
            )
        );

        $actions = FieldList::create(
            FormAction::create('doSubmit', 'Kirim')
                ->addExtraClass('btn btn-primary w-100')
        );

        $required = RequiredFields::create('Name', 'Email', 'Message');

        $form = Form::create($this, 'ContactForm', $fields, $actions, $required);
        $form->addExtraClass('needs-validation');
        $form->setTemplate('ContactForm'); // Opsional: gunakan template custom

        return $form;
    }

    public function doSubmit($data, Form $form)
    {
        try {
            // Validasi reCAPTCHA manual
            $recaptchaResponse = $this->getRequest()->postVar('g-recaptcha-response');
            
            if (empty($recaptchaResponse)) {
                $form->sessionMessage('Silakan centang reCAPTCHA', 'bad');
                return $this->redirectBack();
            }

            // Verifikasi dengan Google
            $secretKey = '6Le_X9grAAAAAFyMjFmGkk_YfUejzEh-yOZbfefx';
            $verifyURL = 'https://www.google.com/recaptcha/api/siteverify';
            
            $response = file_get_contents($verifyURL . '?secret=' . $secretKey . '&response=' . $recaptchaResponse . '&remoteip=' . $_SERVER['REMOTE_ADDR']);
            $responseData = json_decode($response);

            if (!$responseData->success) {
                $form->sessionMessage('Verifikasi reCAPTCHA gagal. Silakan coba lagi.', 'bad');
                return $this->redirectBack();
            }

            // Lanjutkan proses email
            $config = SiteConfig::current_site_config();
            $to = $config->CompanyEmail;

            if (!$to) {
                $form->sessionMessage('Company Email belum diatur di SiteConfig', 'bad');
                return $this->redirectBack();
            }

            if (!filter_var($data['Email'], FILTER_VALIDATE_EMAIL)) {
                $form->sessionMessage('Format email tidak valid', 'bad');
                return $this->redirectBack();
            }

            $from = 'abelyanaumioctaviani06@gmail.com'; // Ganti dengan domain Anda
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

            $email->send();

            $form->sessionMessage('Pesan berhasil dikirim. Terima kasih sudah menghubungi kami!', 'good');
            return $this->redirect($this->Link() . '?success=' . urlencode('Pesan berhasil dikirim.'));

        } catch (Exception $e) {
            user_error('Contact form error: ' . $e->getMessage(), E_USER_WARNING);
            $form->sessionMessage('Maaf, terjadi kesalahan sistem. Silakan coba lagi nanti.', 'bad');
            return $this->redirect($this->Link() . '?error=' . urlencode('Verifikasi reCAPTCHA gagal.'));

        }
    }

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
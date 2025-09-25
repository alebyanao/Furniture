<?php

use SilverStripe\Control\Director;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Core\Environment;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\ORM\ValidationException;
use SilverStripe\ORM\ValidationResult;
use SilverStripe\Security\IdentityStore;
use SilverStripe\Security\Member;
use SilverStripe\Security\MemberAuthenticator\LoginHandler;
use SilverStripe\Security\MemberAuthenticator\MemberAuthenticator;
use SilverStripe\SiteConfig\SiteConfig;
use SilverStripe\View\ArrayData;

class AuthPageController extends PageController
{
    private static $allowed_actions = [
        'login',
        'register',
        'forgotPassword',
        'resetPassword',
        'init',
    ];

    private static $url_handlers = [
        'login' => 'login',
        'register' => 'register',
        'forgot-password' => 'forgotPassword',
        'reset-password' => 'resetPassword'
    ];

    private function getCompanyEmailSafe($siteConfig)
        {
            // First try CompanyEmail from CustomSiteConfig extension
            if (isset($siteConfig->CompanyEmail) && !empty($siteConfig->CompanyEmail)) {
                $email = trim($siteConfig->CompanyEmail);
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    return $email;
                }
            }

            // Then try default SiteConfig Email field
            if (isset($siteConfig->Email) && !empty($siteConfig->Email)) {
                $emailString = trim($siteConfig->Email);
                if ($emailString !== '') {
                    $emails = explode(',', $emailString);
                    $firstEmail = trim($emails[0]);
                    if (filter_var($firstEmail, FILTER_VALIDATE_EMAIL)) {
                        return $firstEmail;
                    }
                }
            }

            // Final fallback - generate from domain
            $domain = $_SERVER['HTTP_HOST'] ?? 'localhost';
            return "noreply@{$domain}";
        }

    public function login(HTTPRequest $request)
    {
        $validationResult = null;

        if ($request->isPOST()) {
            $validationResult = $this->processLogin($request);

            if ($validationResult->isValid()) {
                $this->getRequest()->getSession()->set('FlashMessage', [
                    'Message' => 'Masuk berhasil! Selamat datang.',
                    'Type' => 'primary'
                ]);
                return $this->redirect(Director::absoluteBaseURL());
            }
        }

        if ($validationResult && !$validationResult->isValid()) {
            $this->flashMessages = ArrayData::create([
                'Message' => 'Masuk gagal. Periksa email dan password Anda.',
                'Type' => 'danger'
            ]);
        }

        $data = array_merge($this->getCommonData(), [
            'Title' => 'Login',
            'ValidationResult' => $validationResult
        ]);

        return $this->customise($data)->renderWith(['LoginPage', 'Page']);
    }

    public function register(HTTPRequest $request)
    {
        $validationResult = null;
        $flashMessage = null;
        
        if ($request->isPOST()) {
            $validationResult = $this->processRegister($request);
            if ($validationResult->isValid()) {
                // Jangan redirect, set flash message untuk ditampilkan
                $flashMessage = ArrayData::create([
                    'Message' => 'Pendaftaran berhasil! Silakan cek email untuk verifikasi akun.',
                    'Type' => 'success'
                ]);
            } else {
                // Untuk error
                $flashMessage = ArrayData::create([
                    'Message' => 'Pendaftaran gagal',
                    'Type' => 'danger'
                ]);
            }
        }
        
        $data = array_merge($this->getCommonData(), [
            'Title' => 'Register',
            'ValidationResult' => $validationResult,
            'flashMessages' => $flashMessage // pass ke template
        ]);
        
        return $this->customise($data)->renderWith(['RegisterPage', 'Page']);
    }

    public function forgotPassword(HTTPRequest $request)
    {
        $validationResult = null;

        if ($request->isPOST()) {
            $validationResult = $this->processForgotPassword($request);

            if ($validationResult->isValid()) {
                $this->flashMessages = ArrayData::create([
                    'Message' => 'Link reset password telah dikirim ke email Anda.',
                    'Type' => 'primary'
                ]);
            }
        }

        if ($validationResult && !$validationResult->isValid()) {
            $this->flashMessages = ArrayData::create([
                'Message' => 'Email tidak ditemukan atau terjadi kesalahan.',
                'Type' => 'danger'
            ]);
        }

        $data = array_merge($this->getCommonData(), [
            'Title' => 'Lupa Sandi',
            'ValidationResult' => $validationResult
        ]);

        return $this->customise($data)->renderWith(['ForgotPasswordPage', 'Page']);
    }

    public function resetPassword(HTTPRequest $request)
    {
        $token = $request->getVar('token');
        $validationResult = null;

        if (!$token) {
            return $this->redirect(Director::absoluteBaseURL() . '/auth/forgot-password');
        }

        $member = Member::get()->filter('ResetPasswordToken', $token)->first();
        if (!$member || !$member->ResetPasswordExpiry || strtotime($member->ResetPasswordExpiry) < time()) {
            $this->flashMessages = ArrayData::create([
                'Message' => 'Link reset password tidak valid atau sudah kadaluarsa.',
                'Type' => 'danger'
            ]);
            return $this->redirect(Director::absoluteBaseURL() . '/auth/forgot-password');
        }

        if ($request->isPOST()) {
            $validationResult = $this->processResetPassword($request, $member);

            if ($validationResult->isValid()) {
                $this->getRequest()->getSession()->set('FlashMessage', [
                    'Message' => 'Password berhasil direset. Silakan login dengan password baru.',
                    'Type' => 'primary'
                ]);
                return $this->redirect(Director::absoluteBaseURL() . '/auth/login');
            }
        }

        if ($validationResult && !$validationResult->isValid()) {
            $this->flashMessages = ArrayData::create([
                'Message' => 'Gagal reset password. Password tidak valid atau sama dengan sebelumnya. Periksa kembali password baru Anda.',
                'Type' => 'danger'
            ]);
        }

        $data = array_merge($this->getCommonData(), [
            'Title' => 'Reset Sandi',
            'Token' => $token,
            'ValidationResult' => $validationResult
        ]);

        return $this->customise($data)->renderWith(['ResetPasswordPage', 'Page']);
    }

    private function processLogin(HTTPRequest $request)
    {
        $email = $request->postVar('login_email');
        $password = $request->postVar('login_password');
        $rememberMe = $request->postVar('login_remember');

        $data = [
            'Email' => $email,
            'Password' => $password,
            'Remember' => $rememberMe
        ];

        $result = ValidationResult::create();
        $authenticator = new MemberAuthenticator();
        $loginHandler = new LoginHandler('auth', $authenticator);

        if ($member = $loginHandler->checkLogin($data, $request, $result)) {
            if (!$member->IsVerified) {
                $result->addError('Akun Anda belum diverifikasi. Silakan cek email.');
                return $result;
            }
            
            
            if (!$member->inGroup('site-users')) {
                Injector::inst()->get(IdentityStore::class)->logOut($request);
                $result->addError('Invalid credentials.');
            } else {
                $loginHandler->performLogin($member, $data, $request);
            }
        }
        

        return $result;

    }

    private function processRegister(HTTPRequest $request)
    {
        $baseURL = Environment::getEnv('SS_BASE_URL');
        $ngrokUrl = Environment::getEnv('NGROK_URL');

        $firstName = $request->postVar('register_first_name');
        $lastName = $request->postVar('register_last_name');
        $userEmail = $request->postVar('register_email');
        $password1 = $request->postVar('register_password_1');
        $password2 = $request->postVar('register_password_2');

        $SiteConfig = SiteConfig::current_site_config();
        $CompanyEmail = $this->getCompanyEmailSafe($SiteConfig);

        $result = ValidationResult::create();

        // Validasi input
        if (empty($firstName)) {
            $result->addError('First name is required.');
        }

        if (empty($lastName)) {
            $result->addError('Last name is required.');
        }

        if (empty($userEmail) || !filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
            $result->addError('Valid email is required.');
        }

        if ($password1 !== $password2) {
            $result->addError('Passwords do not match.');
        }

        if (strlen($password1) < 8) {
            $result->addError('Password must be at least 8 characters long.');
        }

        if (Member::get()->filter('Email', $userEmail)->exists()) {
            $result->addError('Email already exists.');
        }

        // Jika validasi gagal, return error
        if (!$result->isValid()) {
            return $result;
        }

        // Buat member baru
        try {
            $member = Member::create();
            $member->FirstName = $firstName;
            $member->Surname = $lastName;
            $member->Email = $userEmail;
            $member->VerificationToken = sha1(uniqid());
            $member->IsVerified = false;
            $member->write();
            $member->addToGroupByCode('site-users');
            $member->changePassword($password1);

            // Kirim email verifikasi
            $this->sendVerificationEmail($member, $CompanyEmail, $ngrokUrl, $SiteConfig);

            $result->addMessage('Registrasi berhasil! Silakan cek email untuk verifikasi akun.');
        } catch (Exception $e) {
            $result->addError('Registration failed: ' . $e->getMessage());
        }

        return $result;
    }

    private function processForgotPassword(HTTPRequest $request)
    {
        $email = $request->postVar('forgot_email');
        $result = ValidationResult::create();

        if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $result->addError('Valid email is required.');
            return $result;
        }

        $member = Member::get()->filter('Email', $email)->first();
        if (!$member) {
            $result->addError('Email tidak ditemukan.');
            return $result;
        }

        // Generate reset token
        $resetToken = sha1(uniqid() . time());
        $member->ResetPasswordToken = $resetToken;
        $member->ResetPasswordExpiry = date('Y-m-d H:i:s', time() + 3600); // 1 jam
        $member->write();

        // Send reset email
        $ngrokUrl = Environment::getEnv('NGROK_URL');
        $SiteConfig = SiteConfig::current_site_config();
        $CompanyEmail = $this->getCompanyEmailSafe($SiteConfig);

        $this->sendResetPasswordEmail($member, $CompanyEmail, $resetToken, $ngrokUrl, $SiteConfig);

        $result->addMessage('Link reset password telah dikirim ke email Anda.');
        return $result;
    }

    private function processResetPassword(HTTPRequest $request, Member $member)
    {
        $password1 = $request->postVar('new_password_1');
        $password2 = $request->postVar('new_password_2');

        $result = ValidationResult::create();

        if (!$password1 || !$password2) {
            $result->addError('Password harus diisi.');
            return $result;
        }

        if ($password1 !== $password2) {
            $result->addError('Password tidak cocok.');
            return $result;
        }

        if (strlen($password1) < 8) {
            $result->addError('Password minimal 8 karakter.');
            return $result;
        }

        // Update password dan hapus token
        try {
            $member->changePassword($password1);
            $member->ResetPasswordToken = null;
            $member->ResetPasswordExpiry = null;
            $member->write();
            $result->addMessage('Password berhasil direset.');
        } catch (ValidationException $e) {
            $result->addError('Password tidak valid atau sama dengan sebelumnya. Periksa kembali password baru Anda.');
        }

        return $result;
    }

    /**
     * Send verification email to new member
     */
    private function sendVerificationEmail($member, $fromEmail, $ngrokUrl, $siteConfig)
    {
        if (!$fromEmail || !filter_var($fromEmail, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        try {
            $verifyLink = rtrim($ngrokUrl, '/') . '/verify?token=' . $member->VerificationToken;

            $emailObj = \SilverStripe\Control\Email\Email::create()
                ->setTo($member->Email)
                ->setFrom($fromEmail)
                ->setSubject('Verifikasi Email Anda')
                ->setHTMLTemplate('CustomEmail')
                ->setData([
                    'Name' => $member->FirstName,
                    'SenderEmail' => $member->Email,
                    'MessageContent' => "
                        Terima kasih telah mendaftar. Silakan klik link di bawah untuk memverifikasi akun Anda:
                        <br><br>
                        <a href='{$verifyLink}' style='background-color: #b78b5c; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Verifikasi Akun</a>
                        <br><br>
                        Atau salin link ini: {$verifyLink}",
                    'SiteName' => $siteConfig->Title,
                ]);

            return $emailObj->send();
        } catch (Exception $e) {
            error_log("Verification email failed: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send reset password email
     */
    private function sendResetPasswordEmail($member, $fromEmail, $resetToken, $ngrokUrl, $siteConfig)
    {
        if (!$fromEmail || !filter_var($fromEmail, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        try {
            $resetLink = rtrim($ngrokUrl, '/') . '/auth/reset-password?token=' . $resetToken;

            $emailObj = \SilverStripe\Control\Email\Email::create()
                ->setTo($member->Email)
                ->setFrom($fromEmail)
                ->setSubject('Reset Password Anda')
                ->setHTMLTemplate('CustomEmail')
                ->setData([
                    'Name' => $member->FirstName,
                    'SenderEmail' => $member->Email,
                    'MessageContent' => "
                        Kami menerima permintaan untuk reset password akun Anda.
                        <br><br>
                        <a href='{$resetLink}' style='background-color: #b78b5c; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Reset Password</a>
                        <br><br>
                        Atau salin link ini: {$resetLink}
                        <br><br>
                        Link ini berlaku selama 1 jam. Jika Anda tidak meminta reset password, abaikan email ini.",
                    'SiteName' => $siteConfig->Title,
                ]);

            return $emailObj->send();
        } catch (Exception $e) {
            error_log("Reset password email failed: " . $e->getMessage());
            return false;
        }
    }
}
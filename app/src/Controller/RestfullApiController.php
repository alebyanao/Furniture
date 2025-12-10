<?php

use SilverStripe\Control\Controller;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Control\HTTPResponse;
use SilverStripe\Control\Email\Email;
use SilverStripe\Core\Environment;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\ORM\ValidationException;
use SilverStripe\Security\IdentityStore;
use SilverStripe\Security\Member;
use SilverStripe\Security\Security;
use SilverStripe\SiteConfig\SiteConfig;
use SilverStripe\Security\Permission;

class RestfullApiController extends Controller
{
    private static $url_segment = 'api';

    private static $allowed_actions = [
        //login
        'index',
        'login',
        'register',
        'logout',
        'googleAuth',
        'forgotpassword',
        'updatePassword',
        'resetpassword',
        'member',
        'siteconfig',
    ];

    private static $url_handlers = [
        'login' => 'login',
        'register' => 'register',
        'logout' => 'logout',
        'google-auth' => 'googleAuth',
        'forgotpassword' => 'forgotpassword',
        'member/password' => 'updatePassword',
        'resetpassword' => 'resetpassword',
        'member' => 'member',
        'siteconfig' => 'siteconfig',
        '' => 'index',
    ];

    /* INDEX */
    public function index(HTTPRequest $request)
    {
        return $this->jsonResponse([
            'message' => 'SilverStripe Furniture API',
            'status' => 'running',
            'endpoints' => [
                'authentication' => [
                    'POST /api/login' => 'Login user',
                    'POST /api/register' => 'Register user',
                    'POST /api/logout' => 'Logout user',
                    'POST /api/google-auth' => 'Google authentication',
                    'POST /api/forgotpassword' => 'Forgot password',
                    'GET /api/siteconfig' => 'Get site configuration',
                ],
                'member' => [
                    'GET /api/member' => 'Get current member profile',
                    'PUT /api/member' => 'Update member profile',
                    'PUT /api/member/password' => 'Update member password',
                ],
            ]
        ]);
    }
    private function getCompanyEmailSafe(?SiteConfig $siteConfig = null): string
    {
        $siteConfig = $siteConfig ?: SiteConfig::current_site_config();

        if (!empty($siteConfig->CompanyEmail) && filter_var($siteConfig->CompanyEmail, FILTER_VALIDATE_EMAIL)) {
            return $siteConfig->CompanyEmail;
        }
        if (!empty($siteConfig->Email) && filter_var($siteConfig->Email, FILTER_VALIDATE_EMAIL)) {
            return $siteConfig->Email;
        }

        $host = $_SERVER['HTTP_HOST'] ?? 'example.com';
        return 'noreply@' . preg_replace('/^www\./', '', $host);
    }
    public function login(HTTPRequest $request)
    {
        if (!$request->isPOST()) {
            return $this->jsonResponse(['error' => 'Only POST allowed'], 405);
        }

        $data = json_decode($request->getBody(), true);
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';

        if (!$email || !$password) {
            return $this->jsonResponse(['error' => 'Email dan password wajib diisi'], 400);
        }

        $member = Member::get()->filter('Email', $email)->first();
        if (!$member || !password_verify($password, $member->Password)) {
            return $this->jsonResponse(['error' => 'Email atau password salah'], 401);
        }

        if (!$member->IsVerified) {
            return $this->jsonResponse(['error' => 'Akun belum diverifikasi'], 403);
        }

        Injector::inst()->get(IdentityStore::class)->logIn($member, true);

        return $this->jsonResponse([
            'success' => true,
            'message' => 'Login berhasil',
            'user' => [
                'id' => $member->ID,
                'email' => $member->Email,
                'first_name' => $member->FirstName,
                'surname' => $member->Surname,
                'is_verified' => $member->IsVerified
            ]
        ]);
    }
    public function register(HTTPRequest $request)
    {
        if (!$request->isPOST()) {
            return $this->jsonResponse(['error' => 'Only POST allowed'], 405);
        }

        $data = json_decode($request->getBody(), true);
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';
        $firstName = $data['first_name'] ?? '';
        $surname = $data['surname'] ?? '';

        if (!$email || !$password || !$firstName) {
            return $this->jsonResponse(['error' => 'Email, password, dan nama depan wajib diisi'], 400);
        }

        if (strlen($password) < 8) {
            return $this->jsonResponse(['error' => 'Password minimal 8 karakter'], 400);
        }

        if (Member::get()->filter('Email', $email)->exists()) {
            return $this->jsonResponse(['error' => 'Email sudah terdaftar'], 400);
        }

        $member = Member::create();
        $member->FirstName = $firstName;
        $member->Surname = $surname;
        $member->Email = $email;
        $member->VerificationToken = bin2hex(random_bytes(32));
        $member->IsVerified = false;
        $member->write();
        $member->addToGroupByCode('site-users');
        $member->changePassword($password);

        $ngrokURL = Environment::getEnv('NGROK_URL');
        $verifyLink = rtrim($ngrokURL, '/') . '/verify?token=' . $member->VerificationToken;
        $siteConfig = SiteConfig::current_site_config();
        $companyEmail = $this->getCompanyEmailSafe($siteConfig);

        Email::create()
            ->setTo($member->Email)
            ->setFrom($companyEmail)
            ->setSubject('Verifikasi Akun Anda')
            ->setBody("
                <p>Halo {$member->FirstName},</p>
                <p>Klik tautan di bawah ini untuk memverifikasi akun Anda:</p>
                <p><a href='{$verifyLink}'>{$verifyLink}</a></p>
                <p>Terima kasih,<br>{$siteConfig->Title}</p>
            ")
            ->send();

        return $this->jsonResponse([
            'success' => true,
            'message' => 'Pendaftaran berhasil. Silakan cek email untuk verifikasi.'
        ], 201);
    }
    public function googleAuth(HTTPRequest $request)
    {
        if (!$request->isPOST()) {
            return $this->jsonResponse(['error' => 'Only POST allowed'], 405);
        }

        $data = json_decode($request->getBody(), true);
        $email = $data['email'] ?? '';
        $displayName = $data['display_name'] ?? '';

        if (!$email) {
            return $this->jsonResponse(['error' => 'Email wajib diisi'], 400);
        }

        $nameParts = explode(' ', $displayName, 2);
        $firstName = $nameParts[0] ?? '';
        $surname = $nameParts[1] ?? '';

        $member = Member::get()->filter('Email', $email)->first();

        if (!$member) {
            $member = Member::create();
            $member->FirstName = $firstName;
            $member->Surname = $surname;
            $member->Email = $email;
            $member->IsVerified = true;
            $member->write();
            $member->addToGroupByCode('site-users');
            $member->changePassword(bin2hex(random_bytes(16)));
        }

        Injector::inst()->get(IdentityStore::class)->logIn($member, false);

        return $this->jsonResponse([
            'success' => true,
            'message' => 'Google login berhasil',
            'user' => [
                'id' => $member->ID,
                'email' => $member->Email,
                'first_name' => $member->FirstName,
                'surname' => $member->Surname,
            ]
        ]);
    }
    public function logout(HTTPRequest $request)
    {
        $member = Security::getCurrentUser();
        if ($member) {
            Injector::inst()->get(IdentityStore::class)->logOut($request);
        }

        return $this->jsonResponse(['success' => true, 'message' => 'Logout berhasil']);
    }
    public function forgotpassword(HTTPRequest $request)

    {
        if (!$request->isPOST()) {
            return $this->jsonResponse(['error' => 'Only POST allowed'], 405);
        }

        $data = json_decode($request->getBody(), true);
        $email = trim($data['email'] ?? '');

        if (!$email) {
            return $this->jsonResponse(['error' => 'Email wajib diisi'], 422);
        }

        $member = Member::get()->filter('Email', $email)->first();
        if (!$member) {
            return $this->jsonResponse(['error' => 'Email tidak ditemukan'], 404);
        }

        $token = bin2hex(random_bytes(32));
        $member->ResetPasswordToken = $token;
        $member->ResetPasswordExpiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
        $member->write();

        $ngrokURL = Environment::getEnv('NGROK_URL');
        $siteConfig = SiteConfig::current_site_config();
        $fromEmail = $this->getCompanyEmailSafe($siteConfig);
        $resetLink = rtrim($ngrokURL, '/') . '/auth/resetpassword?token=' . $token;

        Email::create()
            ->setTo($member->Email)
            ->setFrom($fromEmail)
            ->setSubject('Reset Password Akun Anda')
            ->setBody("
                <p>Halo {$member->FirstName},</p>
                <p>Klik tautan berikut untuk mengatur ulang password Anda:</p>
                <p><a href='{$resetLink}'>{$resetLink}</a></p>
                <p>Tautan ini berlaku selama 1 jam.</p>
            ")
            ->send();

        return $this->jsonResponse([
            'success' => true,
            'message' => 'Link reset password telah dikirim ke email Anda.'
        ]);
    }

    public function resetpassword(HTTPRequest $request)
    {
        if (!$request->isPOST()) {
            return $this->jsonResponse(['error' => 'Only POST allowed'], 405);
        }

        $data = json_decode($request->getBody(), true);
        $token = $data['token'] ?? '';
        $newPassword = $data['new_password'] ?? '';

        if (!$token || !$newPassword) {
            return $this->jsonResponse(['error' => 'Token dan password wajib diisi'], 400);
        }

        if (strlen($newPassword) < 8) {
            return $this->jsonResponse(['error' => 'Password minimal 8 karakter'], 400);
        }

        $member = Member::get()->filter('ResetPasswordToken', $token)->first();
        
        if (!$member) {
            return $this->jsonResponse(['error' => 'Token tidak valid'], 404);
        }

        if (strtotime($member->ResetPasswordExpiry) < time()) {
            return $this->jsonResponse(['error' => 'Token sudah kadaluarsa'], 400);
        }

        $member->changePassword($newPassword);
        $member->ResetPasswordToken = null;
        $member->ResetPasswordExpiry = null;
        $member->write();

        return $this->jsonResponse([
            'success' => true,
            'message' => 'Password berhasil direset'
        ]);
    }

    public function updatePassword(HTTPRequest $request)
    {
        $member = Security::getCurrentUser();
        if (!$member) {
            return $this->jsonResponse(['error' => 'Unauthorized'], 401);
        }

        $data = json_decode($request->getBody(), true);
        $oldPassword = $data['old_password'] ?? '';
        $newPassword = $data['new_password'] ?? '';

        if (!$oldPassword || !$newPassword) {
            return $this->jsonResponse(['error' => 'Password lama dan baru wajib diisi'], 400);
        }

        if (!password_verify($oldPassword, $member->Password)) {
            return $this->jsonResponse(['error' => 'Password lama salah'], 403);
        }

        if (strlen($newPassword) < 8) {
            return $this->jsonResponse(['error' => 'Password baru minimal 8 karakter'], 400);
        }

        $member->changePassword($newPassword);

        return $this->jsonResponse(['success' => true, 'message' => 'Password berhasil diperbarui']);
    }
    public function member(HTTPRequest $request)
    {
        $member = Security::getCurrentUser();
        if (!$member) {
            return $this->jsonResponse(['error' => 'Unauthorized'], 401);
        }

        return $this->jsonResponse([
            'id' => $member->ID,
            'email' => $member->Email,
            'first_name' => $member->FirstName,
            'surname' => $member->Surname,
            'is_verified' => $member->IsVerified,
            'membership_tier' => $member->MembershipTierName,
            'total_transactions' => $member->getFormattedTotalTransactions()
        ]);
    }
    public function siteconfig(HTTPRequest $request)
    {
        $config = SiteConfig::current_site_config();

        return $this->jsonResponse([
            'title' => $config->Title,
            'tagline' => $config->Tagline,
            'company_email' => $this->getCompanyEmailSafe($config),
            'phone' => $config->Phone,
            'address' => $config->Address,
        ]);
    }
    private function jsonResponse($data, $status = 200)
    {
        $response = new HTTPResponse(json_encode($data, JSON_UNESCAPED_UNICODE), $status);
        $response->addHeader('Content-Type', 'application/json; charset=utf-8');
        $response->addHeader('Access-Control-Allow-Origin', '*');
        $response->addHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        $response->addHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization');
        $response->addHeader('Access-Control-Allow-Credentials', 'true');
        return $response;
    }
}
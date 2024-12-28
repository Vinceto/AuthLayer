<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use PragmaRX\Google2FA\Google2FA;

class Google2faComponent extends Component
{
    protected $google2fa;

    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->google2fa = new Google2FA();
    }

    public function generateSecretKey(): string
    {
        return $this->google2fa->generateSecretKey();
    }

    public function getQRCodeUrl(string $companyName, string $companyEmail, string $secretKey): string
    {
        return $this->google2fa->getQRCodeUrl($companyName, $companyEmail, $secretKey);
    }

    public function verifyKey(string $secretKey, string $oneTimePassword): bool
    {
        return $this->google2fa->verifyKey($secretKey, $oneTimePassword);
    }
}
<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\Utility\Security;
use Cake\Http\Response;
use Cake\ORM\TableRegistry;
use PragmaRX\Google2FA\Google2FA;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Label\Label;


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

    public function getQRCodeImage(string $companyName, string $companyEmail, string $secretKey): string
    {
        $qrCodeUrl = $this->getQRCodeUrl($companyName, $companyEmail, $secretKey);

        $result = Builder::create()
            ->writer(new PngWriter())
            ->data($qrCodeUrl)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(ErrorCorrectionLevel::High)
            ->size(300)
            ->margin(10)
            ->build();

        return $result->getDataUri();
    }

    public function enable2fa()
    {
        $this->Google2fa->enable2fa($this);
    }

    public function verify2fa()
    {
        $result = $this->Google2fa->verify2fa($this);
        if ($result instanceof \Cake\Http\Response) {
            return $result; // redirige si es necesario
        }
    }
}
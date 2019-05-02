<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Scheb\TwoFactorBundle\Model\BackupCodeInterface;
use Scheb\TwoFactorBundle\Model\Email\TwoFactorInterface as EmailTwoFactorInterface;
use Scheb\TwoFactorBundle\Model\Google\TwoFactorInterface as GoogleTwoFactorInterface;
use Scheb\TwoFactorBundle\Model\Totp\TotpConfiguration;
use Scheb\TwoFactorBundle\Model\Totp\TotpConfigurationInterface;
use Scheb\TwoFactorBundle\Model\Totp\TwoFactorInterface as TotpTwoFactorInterface;
use Scheb\TwoFactorBundle\Model\TrustedDeviceInterface;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User implements AdvancedUserInterface, \Serializable, EmailTwoFactorInterface, GoogleTwoFactorInterface, TotpTwoFactorInterface, TrustedDeviceInterface, BackupCodeInterface
{
    private const BACKUP_CODES = [111, 222];

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=60, unique=true)
     */
    private $email;

    /**
     * @var string $emailAuthenticationCode
     * @ORM\Column(type="integer")
     */
    private $emailAuthenticationCode;

    /**
     * @var string $googleAuthenticatorSecret
     * @ORM\Column(type="string")
     */
    private $googleAuthenticatorSecret;

    /**
     * @var string $isActive
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    public function getId()
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getSalt()
    {
        return null;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getRoles()
    {
        return array('ROLE_USER');
    }

    public function eraseCredentials()
    {
    }

    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt,
            $this->isActive,
        ));
    }

    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt
            $this->isActive,
            ) = unserialize($serialized);
    }

    public function getEmailAuthRecipient(): string
    {
        return $this->email;
    }

    public function isEmailAuthEnabled(): bool
    {
        return true;
    }

    public function getEmailAuthCode(): string
    {
        return $this->emailAuthenticationCode;
    }

    public function setEmailAuthCode(string $authCode): void
    {
        $this->emailAuthenticationCode = $authCode;
    }

    public function isGoogleAuthenticatorEnabled(): bool
    {
        return !!$this->googleAuthenticatorSecret;
    }

    public function getGoogleAuthenticatorUsername(): string
    {
        return $this->username;
    }

    public function getGoogleAuthenticatorSecret(): string
    {
        return $this->googleAuthenticatorSecret;
    }

    public function setGoogleAuthenticatorSecret(?string $googleAuthenticatorSecret): void
    {
        $this->googleAuthenticatorSecret = $googleAuthenticatorSecret;
    }

    public function isTotpAuthenticationEnabled(): bool
    {
        return $this->googleAuthenticatorSecret ? true : false;
    }

    public function getTotpAuthenticationUsername(): string
    {
        return $this->username;
    }

    public function getTotpAuthenticationConfiguration(): ?TotpConfigurationInterface
    {
        return new TotpConfiguration($this->googleAuthenticatorSecret, TotpConfiguration::ALGORITHM_SHA1, 30, 6);
    }

    public function isBackupCode(string $code): bool
    {
        return in_array($code, self::BACKUP_CODES);
    }

    public function invalidateBackupCode(string $code): void
    {
    }

    public function getTrustedTokenVersion(): int
    {
        return 1;
    }

    public function isAccountNonExpired()
    {
        return true;
    }

    public function isAccountNonLocked()
    {
        return true;
    }

    public function isCredentialsNonExpired()
    {
        return true;
    }

    public function isEnabled()
    {
        return $this->isActive;
    }
}

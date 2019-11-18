<?php

namespace App\Controller;

use App\Entity\User;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Google\GoogleAuthenticatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="_security_login")
     */
    public function login(AuthenticationUtils $authUtils)
    {
        // get the login error if there is one
        $error = $authUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authUtils->getLastUsername();

        return $this->render('security/login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    }

    /**
     * @Route("/password", name="_security_password")
     */
    public function register(UserPasswordEncoderInterface $encoder)
    {
        $user = new User();
        $plainPassword = 'test';
        $encoded = $encoder->encodePassword($user, $plainPassword);
        return new Response($encoded);
    }

    /**
     * @Route("/googleSecret", name="generate_google_secret")
     */
    public function googleSecret(GoogleAuthenticatorInterface $googleAuthenticator)
    {
        return new Response($googleAuthenticator->generateSecret());
    }

    /**
     * @Route("/2fa/accessible", name="2fa_accessible_route")
     */
    public function accessibleDuring2fa()
    {
        return new Response("It works!");
    }
}

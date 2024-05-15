<?php

namespace App\Controller;
use Google\Client;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;
use Symfony\Component\Security\Http\Logout\LogoutHandlerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Http\Logout\DefaultLogoutSuccessHandler;
class SecurityController extends AbstractController


{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_blog');
         }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
public function logout(): void
{

}

    #[Route(path: '/login/google', name: 'app_login_google')]
    public function googleLogin(Request $request): Response
    {
        // Redirect the user to Google OAuth authorization URL
        $googleAuthUrl = $this->generateGoogleAuthUrl($request);
        return new RedirectResponse($googleAuthUrl);
    }

    private function generateGoogleAuthUrl(Request $request): string
    {
        // Create a new instance of Google_Client
        $googleClient = new \Google\Client();
        $googleClient->setClientId('522047736624-hd0garjoo0n5j9mk4vcqvle4tkl5qgnt.apps.googleusercontent.com');
        $googleClient->setClientSecret('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');

        // Generate an absolute redirect URI
        $redirectUri = $this->generateUrl('app_login_google_check', [], UrlGeneratorInterface::ABSOLUTE_URL);
        $googleClient->setRedirectUri($redirectUri);

        $googleClient->setAccessType('offline'); // Optional: request offline access
        $googleClient->setIncludeGrantedScopes(true); // Optional: include previously granted scopes
        $googleClient->setPrompt('consent'); // Optional: force the consent screen every time
        $googleClient->setScopes(['email', 'profile']); // Add required scopes here

        return $googleClient->createAuthUrl();
    }

    #[Route(path: '/login/google/check', name: 'app_login_google_check')]
    public function googleCheck(): Response
    {
        // Your logic here

        // Example: Redirect to the homepage after successful authentication
        return $this->redirectToRoute('app_blog');
    }



    // Implementation of LogoutSuccessHandlerInterface
    public function onLogoutSuccess(Request $request)
    {
        // Redirect the user after logout
        return new RedirectResponse($this->generateUrl('app_login'));
    }

    // Implementation of LogoutHandlerInterface



}
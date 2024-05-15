<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class AdminAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {
    }

    public function authenticate(Request $request): Passport
    {
        // Check if the request contains an access token from Google OAuth
        if ($request->query->has('code')) {
            // Handle Google OAuth authentication
            // Example: return a Passport with custom credentials
            return new Passport(
                new UserBadge('google'), // You can use a placeholder user identifier for Google OAuth
                new CustomCredentials(function ($credentials) {
                    // Here, you can perform additional logic to retrieve user information from Google using the access token
                    // Example: $userInfo = $googleService->userinfo->get();
                    // You can then return the user information as an array
                    return [
                        'email' => 'google@example.com',
                        'roles' => ['ROLE_USER'], // Example role for Google authenticated users
                    ];
                })
            );
        } else {
            // Handle traditional login form authentication
            $email = $request->request->get('email', '');
            $password = $request->request->get('password', '');
            $request->getSession()->set(Security::LAST_USERNAME, $email);

            return new Passport(
                new UserBadge($email),
                new PasswordCredentials($password), // Pass the password as PasswordCredentials
                [
                    new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
                    new RememberMeBadge(),
                ]
            );
        }
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        // For example:
        // return new RedirectResponse($this->urlGenerator->generate('some_route'));
        if ($token->getUser()->getRoles() === ['ROLE_ADMIN']) {
            return new RedirectResponse($this->urlGenerator->generate('app_back'));
        }
        else
        {return new RedirectResponse($this->urlGenerator->generate('app_blog'));
    }

    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}

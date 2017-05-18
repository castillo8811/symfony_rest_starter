<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Controller used to manage the application security.
 * See http://symfony.com/doc/current/cookbook/security/form_login_setup.html.
 *
 * @author Ryan Weaver <weaverryan@gmail.com>
 * @author Javier Eguiluz <javier.eguiluz@gmail.com>
 */
class SecurityController extends Controller
{
    /**
     * @Route("/login", name="security_login")
     */
    public function loginAction()
    {

        $helper = $this->get('security.authentication_utils');

        return $this->render('security/login.html.twig', [
            // last username entered by the user (if any)
            'last_username' => $helper->getLastUsername(),
            // last authentication error (if any)
            'error' => $helper->getLastAuthenticationError(),
        ]);
    }

    /**
     * This is the route the user can use to logout.
     *
     * But, this will never be executed. Symfony will intercept this first
     * and handle the logout automatically. See logout in app/config/security.yml
     *
     * @Route("/logout", name="security_logout")
     */
    public function logoutAction()
    {
        throw new \Exception('This should never be reached!');
    }

    /**
     *@Route("/api/login-check", name="security_token_authentication")
     * @Method({"POST"})
     */

    public function tokenAuthenticationAction(Request $request)
    {
        $username = $request->request->get('username','alex');
        $password = $request->request->get('password');

        $user = $this->getDoctrine()->getRepository('AppBundle:User')
            ->findOneBy(['username' => $username]);


        if(!$user) {
            throw $this->createNotFoundException();
        }

        // password check
        if(!$this->get('security.password_encoder')->isPasswordValid($user, $password)) {
            throw $this->createAccessDeniedException();
        }

        // Use LexikJWTAuthenticationBundle to create JWT token that hold only information about user name
        $token = $this->get('lexik_jwt_authentication.encoder')
            ->encode(['username' => $user->getUsername(),'email'=>$user->getEmail(),'roles'=>$user->getRoles()]);

        // Return genereted tocken
        return new JsonResponse(['token' => $token]);
    }

    /**
     * @Route(path="/api/secure-resource", name="secure_resource")
     */
    public function secureResource(){
        $data = [
            'test' => 'test',
            'test2' => 'test2'
        ];

        return new JsonResponse($data);
    }
}
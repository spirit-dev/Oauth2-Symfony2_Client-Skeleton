<?php

namespace CB\ClientBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Buzz\Browser;

class DefaultController extends Controller
{
    public function indexAction() {
        
        $ue = $this->container->get('cb_client.auth_user_entity');
        $oar = $this->container->get('cb_client.oauthrequestor');
        // $ug = $this->container->get('cb_client.oauth_user_grants');
        // $ug->hasExpired();
        $userEnity = $ue->getUserEntity();

        if ($userEnity['user_id'] === 'session_error' || 
            $userEnity['user_username'] === 'session_error' || 
            $userEnity['user_email'] === 'session_error' || 
            $userEnity['user_role'] === 'session_error') {

            return $this->redirect($this->generateUrl('cb_client_login'));

        }

        return $this->render('CBClientBundle:Default:index.html.twig', 
            array(
                'user' => $ue->getUserEntity(),
                'access_token' => $oar->getAccessToken(),
                'env' => $this->container->get('kernel')->getEnvironment()
            )
        );
    }

    public function logoutAction() {

        return 0;
    }

    public function loginAction() {

    	return $this->render('CBClientBundle:Default:sign-in.html.twig');
    }

    public function passwordGrantAction(Request $request) {

        $username = $request->get('username');
        $password = $request->get('password');

        $oar = $this->container->get('cb_client.oauthrequestor');
        $ue = $this->container->get('cb_client.auth_user_entity');

        $req = $oar->getUserGrants($username, $password);

        if ($req == 200) {
            // REDIRECT RESPONSE
            return new JsonResponse(array(
                "response_header" => "authentification",
                "response_type" => "redirection",
                "response_text" => "User authentification accorded. Need to redirect",
                "response_data" => array(
                        "redirect_value" => $oar->getRedirectUri(),
                        "access_token" => $oar->getAccessToken(),
                        "user" => $ue->getUserEntity()
                    )
            ), 200);   
        }
        if ($req == 206) {
            return new JsonResponse(array(
                "response_header" => "authentification nok",
                "response_type" => "explanation",
                "response_text" => "User authentification failed."
            ), 206);
        }
        return new JsonResponse($req, 200);
    }

    public function checkTokenAction() {

        $oar = $this->container->get('cb_client.oauthrequestor');

        $req = $oar->checkStatus();

        return new JsonResponse($req, 200);  
    }

    public function checkUserAction() {
        $ue = $this->container->get('cb_client.auth_user_entity');
        // $req = $ue->setUserEntity("1", "Roger", "roger@paul.fr", "USER");
        $req = $ue->getUserEntity();
        
        // $oar = $this->container->get('cb_client.oauthrequestor');
        // $req = $oar->getTokenDateOut();
        // $req = $oar->getRemoteUser("test");

        return new JsonResponse($req, 200); 
    }

    public function deleteUserAction() {

        $ue = $this->container->get('cb_client.auth_user_entity');
        $req = $ue->deleteSessionVars();
        return new JsonResponse($req, 200); 
    }
}

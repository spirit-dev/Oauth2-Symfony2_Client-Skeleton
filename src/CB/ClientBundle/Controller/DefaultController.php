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
    public function indexAction()
    {
        // return $this->render('CBClientBundle:Default:sign-in.html.twig', array('name' => $name));
		if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_ANONYMOUSLY')) {
        	return $this->render('CBClientBundle:Default:index.html.twig');
		}
		else {
			return $this->redirect($this->generateUrl('cb_client_login'));
		}
    }

    public function loginAction() {
    	return $this->render('CBClientBundle:Default:sign-in.html.twig');
    }

    public function passwordGrantAction(Request $request) {

        $username = $request->get('username');
        $password = $request->get('password');

        $oar = $this->container->get('cb_client.oauthrequestor');

        $req = $oar->getUserGrants($username, $password);

        if ($req == 200) {
            // REDIRECT RESPONSE
            return new JsonResponse(array(
                "response_header" => "authentification",
                "response_type" => "redirection",
                "response_text" => "User authentification accorded. Need to redirect",
                "response_data" => array(
                        "redirect_value" => $oar->getRedirectUri(),
                        "access_token" => $oar->getAccessToken()
                    )
            ), 200);   
        }
        if ($req == 206)
            return new JsonResponse(array(
                "response_header" => "authentification nok",
                "response_type" => "explanation",
                "response_text" => "User authentification failed."
            ), 206);
    }

    public function enttestAction() {

        $oar = $this->container->get('cb_client.oauthrequestor');

        $req = $oar->checkStatus();

        return new JsonResponse($req, 200);  
    }
}

<?php

namespace CB\ClientBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
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

        $browser = $this->container->get('buzz');
        $serverResponse = $browser->get('http://cubbyholeapi.com/oauth/v2/token?grant_type=password&client_id=2_3nuzi79oo084g4ko44cs4s488sw4wsgggcow0gwk8swswwgcsg&client_secret=1q92cxj87ssgsc4ow4osgs804s8kcwwwss4gc4wkc40so00wsk&username='.$username.'&password='.$password.'&redirect_uri=http://cubbyholeclient.com/index');

        $response = json_decode($serverResponse->getContent(), true);

        if ($response == null) {
            return new Response("A server error occured.", 503);
        }
        elseif(array_key_exists('error', $response)) {
            return new Response("User authentification failed.");
        }
        elseif (array_key_exists('access_token', $response) && 
                array_key_exists('refresh_token', $response) &&
                array_key_exists('scope', $response) &&
                array_key_exists('expires_in', $response) &&
                array_key_exists('token_type', $response)) {

            $access_token = $response['access_token'];
            $refresh_token = $response['refresh_token'];
            $scope = $response['scope'];
            $expires_in = $response['expires_in'];
            $token_type = $response['token_type']; 

            return new Response("User authentification granted.");
        }
            
        return new Response(500, "A server error occured.");
    }
}

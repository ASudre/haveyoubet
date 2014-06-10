<?php

namespace asudre\CDM14Bundle\Services;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

class ServiceMails
{
	
	private $mailService;
	private $templatingService;
	
	/**
	 * Constructeur
	 */
	public function __construct(\Swift_Mailer $mailer, EngineInterface $templating)
	{
		$this->mailService = $mailer;
		$this->templateService = $templating;
	}

	/**
	 * Envoi le mail d'inscription au site
	 * @param unknown $codesInvitations
	 * @param unknown $url
	 * @param unknown $invite Pseudo de l'utilisateur qui invite
	 */
	public function envoiMailsInvitations($codesInvitations, $url, $invite) {
		$objet = "Inscription";
		
		foreach ($codesInvitations as $courriel => $code) {
			$corps = $this->templateService->render(
				'asudreCDM14Bundle:Mails:invitations.html.twig',
				array(
					'lien' => $url . "?codeInscription=" . $code,
					'invite' => $invite
				)
			);
			
			$message = \Swift_Message::newInstance()
			->setSubject($objet)
			->setFrom('betoncdm14@gmail.com')
			->setTo($courriel)
			->setBody($corps, 'text/html');
	
			$this->mailService->send($message);
		}	
	}
}
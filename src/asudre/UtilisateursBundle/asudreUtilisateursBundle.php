<?php

namespace asudre\UtilisateursBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class asudreUtilisateursBundle extends Bundle
{
	public function getParent()
	{
		return 'FOSUserBundle';
	}
}

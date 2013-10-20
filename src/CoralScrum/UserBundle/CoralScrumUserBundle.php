<?php

namespace CoralScrum\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class CoralScrumUserBundle extends Bundle
{
	public function getParent()
	{
		return 'FOSUserBundle';
	}
}

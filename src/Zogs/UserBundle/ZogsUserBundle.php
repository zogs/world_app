<?php

namespace Zogs\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class ZogsUserBundle extends Bundle
{
	public function getParent(){

		return 'FOSUserBundle';
	}
}

<?php
class UserLoginTest extends Tests{
    protected function RunTest() {
        $user = new \Model\Users();
        return $user->UserLogin("system", "sd15kl20");
    }

}

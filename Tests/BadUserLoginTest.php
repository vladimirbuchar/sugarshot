<?php
class BadUserLoginTest extends Tests{
    protected function RunTest() {
        $user = new \Model\Users();
        return $user->UserLogin("ssd", "sd15kl20",true);
    }

}

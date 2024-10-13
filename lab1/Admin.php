<?php
class AdminUser extends User
{
    private $permissions;

    public function __construct($username, $email, $password, $permissions)
    {
        parent::__construct($username, $email, $password);
        $this->permissions = $permissions;
    }

    public function getPermissions()
    {
        return $this->permissions;
    }
}

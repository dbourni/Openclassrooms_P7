<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;
    public const USER_ADMIN = 'admin@admin.com';

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'admin'));
        $user->setEmail('admin@admin.com');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setName('Admin');
//        $user->setClient($this->getReference(ClientFixtures::CLIENT_SFR));
        $manager->persist($user);

        foreach ($this->getUserDataSfr() as [$password, $email, $roles, $name]) {
            $user = new User();
            $user->setPassword($this->passwordEncoder->encodePassword($user, $password));
            $user->setEmail($email);
            $user->setRoles($roles);
            $user->setName($name);
            $user->setClient($this->getReference(ClientFixtures::CLIENT_SFR));
            $manager->persist($user);
        }

        foreach ($this->getUserDataOrange() as [$password, $email, $roles, $name]) {
            $user = new User();
            $user->setPassword($this->passwordEncoder->encodePassword($user, $password));
            $user->setEmail($email);
            $user->setRoles($roles);
            $user->setName($name);
            $user->setClient($this->getReference(ClientFixtures::CLIENT_ORANGE));
            $manager->persist($user);
        }

        foreach ($this->getUserDataFree() as [$password, $email, $roles, $name]) {
            $user = new User();
            $user->setPassword($this->passwordEncoder->encodePassword($user, $password));
            $user->setEmail($email);
            $user->setRoles($roles);
            $user->setName($name);
            $user->setClient($this->getReference(ClientFixtures::CLIENT_FREE));
            $manager->persist($user);
        }

        foreach ($this->getUserDataBouygues() as [$password, $email, $roles, $name]) {
            $user = new User();
            $user->setPassword($this->passwordEncoder->encodePassword($user, $password));
            $user->setEmail($email);
            $user->setRoles($roles);
            $user->setName($name);
            $user->setClient($this->getReference(ClientFixtures::CLIENT_BOUYGUES));
            $manager->persist($user);
        }

        $manager->flush();
    }

    private function getUserDataSfr(): array
    {
        return [
            // [$password, $email, $roles, $name];
            ['usersfr', 'usersfr@user.com', ['ROLE_USER'], 'UserSfr'],
            ['clientsfr', 'clientsfr@client.com', ['ROLE_CLIENT'], 'ClientSfr']
        ];
    }

    private function getUserDataOrange(): array
    {
        return [
            // [$password, $email, $roles, $name];
            ['userorange', 'userorange@user.com', ['ROLE_USER'], 'UserOrange'],
            ['clientorange', 'clientorange@client.com', ['ROLE_CLIENT'], 'ClientOrange']
        ];
    }

    private function getUserDataFree(): array
    {
        return [
            // [$password, $email, $roles, $name];
            ['userfree', 'userfree@user.com', ['ROLE_USER'], 'UserFree'],
            ['clientfree', 'clientfree@client.com', ['ROLE_CLIENT'], 'ClientFree']
        ];
    }

    private function getUserDataBouygues(): array
    {
        return [
            // [$password, $email, $roles, $name];
            ['userbouygues', 'userbouygues@user.com', ['ROLE_USER'], 'UserBouygues'],
            ['clientbouygues', 'clientbouygues@client.com', ['ROLE_CLIENT'], 'ClientBouygues']
        ];
    }

    public function getDependencies()
    {
        return array(
            ClientFixtures::class,
        );
    }
}
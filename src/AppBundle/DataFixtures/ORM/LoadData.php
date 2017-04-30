<?php
/**
 * (c) Ismael Shop <i.Shop@gmail.com>
 *
 * @link        http://www.ismaelShop.com
 * @copyright   Copyright (c) Ismael Shop. (http://www.ismaelShop.com)
 * @license     MIT License - http://en.wikipedia.org/wiki/MIT_License
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\Article;
use AppBundle\Entity\Comment;
use AppBundle\Entity\Tag;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Shop\UserBundle\Entity\User;

class LoadData implements FixtureInterface
{
    public function load(ObjectManager $m)
    {
        $user1 = new User();
        $user1
            ->setUsername('iShop')
            ->setPlainPassword('1234')
            ->setEmail('i.Shop@gmail.com')
            ->setRoles(['ROLE_USER'])
            ->setEnabled(true)
        ;
        $m->persist($user1);

        $user2 = new User();
        $user2
            ->setUsername('admin')
            ->setPlainPassword('1234')
            ->setEmail('admin@email.com')
            ->setRoles(['ROLE_ADMIN'])
            ->setEnabled(true)
        ;
        $m->persist($user2);

        $m->flush();
    }

}
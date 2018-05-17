<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Eccube\Application;
use Eccube\Common\Constant;
use Eccube\Entity\BlockPosition;
use Eccube\Entity\Master\DeviceType;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180517113915 extends AbstractMigration
{
    const BLOCK_NAME = '最新商品';

    const BLOCK_FILE_NAME = 'latest_product';

    public function __construct()
    {
        $this->app = Application::getInstance();
    }

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->createBlock();
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->deleteBlock();
    }

    /**
     * ブロック情報登録
     */
    protected function createBlock()
    {
        $em = $this->app['orm.em'];
        $em->getConnection()->beginTransaction();
        try {
            $DeviceType = $this->app['eccube.repository.master.device_type']->find(DeviceType::DEVICE_TYPE_PC);
            $Block = $this->app['eccube.repository.block']->findOrCreate(null, $DeviceType);
            $Block->setName(self::BLOCK_NAME);
            $Block->setFileName(self::BLOCK_FILE_NAME);
            $Block->setDeletableFlg(Constant::DISABLED);
            $Block->setLogicFlg(1);
            $em->persist($Block);
            $em->flush($Block);
            $em->getConnection()->commit();
        } catch (\Exception $e) {
            $em->getConnection()->rollback();
            throw $e;
        }
    }

    /**
     * ブロック情報削除
     */
    protected function deleteBlock()
    {
        $Block = $this->app['eccube.repository.block']->findOneBy(array('file_name' => self::BLOCK_FILE_NAME));
        if ($Block) {
            $em = $this->app['orm.em'];
            $em->getConnection()->beginTransaction();

            try {
                $blockPositions = $Block->getBlockPositions();
                foreach ($blockPositions as $BlockPosition) {
                    $Block->removeBlockPosition($BlockPosition);
                    $em->remove($BlockPosition);
                }
                $em->remove($Block);
                $em->flush();
                $em->getConnection()->commit();
            } catch (\Exception $e) {
                $em->getConnection()->rollback();
                throw $e;
            }
        }
    }
}

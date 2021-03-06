<?php

namespace Model;

class ModelBase {

    public function save() {
        static::em()->persist($this);
    }

    public function remove()
    {
        static::em()->remove($this);
    }
    

    static public function flush(){
        static::em()->flush();
    }

    static public function find($id) {
        return static::em()->find(get_called_class(), $id);
    }

    static public function findOneBy($array) {
        return static::query()->findOneBy($array);   
    }

    static public function query()
    {
        return static::em()->getRepository(get_called_class());
    }

    static public function em() {
        return ORMManager::getEntityManager();
    }

    static public function getList($page=1, $pagesize=20, $asc=false) {
        $dql = sprintf(
            'SELECT n FROM %s n '.
            'ORDER BY n.id %s', 
            get_called_class(),
            $asc ? 'ASC' : 'DESC'
        );
        $query = static::em()->createQuery($dql)->setMaxResults($pagesize)->setFirstResult($pagesize*($page-1));
        return $query->useQueryCache(false)->getResult();
    }
}


class ORMManager {

    static public $entityManager = null;

    public static function init() {
        $db_params = require(APPROOT . 'config/database.conf.php');

        $config = new \Doctrine\ORM\Configuration();
        $eventManager = new \Doctrine\Common\EventManager();

        $driver = $config->newDefaultAnnotationDriver(array(APPROOT . "Model/"));

        $config->setMetadataDriverImpl($driver);
        $config->setProxyDir(APPROOT. 'cache/');
        $config->setProxyNamespace("nhpccProxy");

        if (extension_loaded('wincache')) {
            $config->setMetadataCacheImpl(new \Doctrine\Common\Cache\WinCache());
            $config->setQueryCacheImpl(new \Doctrine\Common\Cache\WinCache());
            $config->setResultCacheImpl(new \Doctrine\Common\Cache\WinCache());
        } else if (extension_loaded('apc')) {
            $config->setMetadataCacheImpl(new \Doctrine\Common\Cache\ApcCache());
            $config->setQueryCacheImpl(new \Doctrine\Common\Cache\ApcCache());
            $config->setResultCacheImpl(new \Doctrine\Common\Cache\ApcCache());
        } else {
            $config->setMetadataCacheImpl(new \Doctrine\Common\Cache\ArrayCache());
        }
        self::$entityManager = \Doctrine\ORM\EntityManager::create($db_params, $config, $eventManager);
    }

    static public function getEntityManager() {
        return self::$entityManager;
    }

} ORMManager::init();
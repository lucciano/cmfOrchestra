<?php
/**
 * This file is part of the <Admin> project.
 *
 * @category   Admin_Repositories
 * @package    Repository
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2012-01-06
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PiApp\AdminBundle\Repository;

use Doctrine\ORM\EntityRepository;
use BootStrap\TranslationBundle\Repository\TranslationRepository;

/**
 * TranslationWidget Repository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 * 
 * @category   Admin_Repositories
 * @package    Repository
 * 
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class TranslationWidgetRepository extends TranslationRepository
{
    /**
     * Return a translation widget by the widget params
     *
     * @param int        $enabled    
     * @param string    $plugin        plugin name of the widget
     * @param string    $action        action name of the widget
     * @param string    $lang        langue of the translation needed.
     *
     * @return \PiApp\AdminBundle\Entity\Page
     * @access public
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since 2012-01-23
     */
    public function getTranslationByParams($enabled, $plugin, $action, $lang)
    {
        $query = $this->createQueryBuilder('t')
        ->select('t, w')
        ->leftJoin('t.widget', 'w')
        ->where('t.enabled = :enabled')
        ->andWhere('w.plugin = :plugin')
        ->andWhere('w.action = :action')
        ->andWhere('t.langCode = :lang')
        ->setParameters(array(
                'enabled'    => $enabled,
                'plugin'    => strtolower($plugin),
                'action'    => strtolower($action),
                'lang'        => $lang,
        ));
        return $query->getQuery()->getOneOrNullResult();
    }
    
    /**
     * Return a translation widget by id and lang widget
     *
     * @param int        $idWidget    id widget
     * @param string    $lang        langue of the translation needed.
     *
     * @return \PiApp\AdminBundle\Entity\Page
     * @access public
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since 2012-01-23
     */
    public function getTranslationById($idWidget, $lang)
    {
        $query = $this->createQueryBuilder('t')
        ->select('t, w')
        ->leftJoin('t.widget', 'w')
        ->where('t.enabled = :enabled')
        ->andWhere('w.id = :widgetID')
        ->andWhere('t.langCode = :lang')
        ->setParameters(array(
                'enabled'    => 1,
                'widgetID'    => (int) $idWidget,
                'lang'        => $lang,
        ));
        return $query->getQuery()->getOneOrNullResult();
    }
    
}
<?php
/**
 * This file is part of the <Database> project.
 *
 * @category   BootStrap_Manager
 * @package    Database
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2012-06-28
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace BootStrap\DatabaseBundle\Manager\Database;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Output\OutputInterface;
use BootStrap\DatabaseBundle\Manager\Database\AbstractManager;

/**
 * Database factory for backup database with the PostgreSql platform.
 *
 * @category   BootStrap_Manager
 * @package    Database
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class BackupPostgreSqlPlatform extends AbstractManager
{
    /**
     * Constructor.
     *
     * @param \Doctrine\DBAL\Connection $connection
     * @param \Symfony\Component\DependencyInjection\ContainerInterface;
     * 
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function __construct(Connection $connection, ContainerInterface $container)
    {
        parent::__construct($connection, $container);
    }
    
    /**
     * Run the backup database.
     *
     * @param array $options
     *
     * @return \Symfony\Component\Console\Output\OutputInterface
     * @access public
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since 2012-02-03
     */
    public function run(OutputInterface $output, Array $options = null)
    {
        $this->setOutputWriter($output);
        $this->setDatabase($this->getConnection()->getDatabase());
        $this->setPath($options);
    
        // we print the start of the file
        $this->_setHead();
        // we print all select of all table of the database.
        $list_tables = $this->getSchemaManager()->listTableNames();
        foreach($list_tables as $k_table => $table){
            $this->_writeSelectTable($table);
        }
        // we print the end of the file
        $this->_setFooter();
    
        try {
            file_put_contents($this->getPath(), $this->contentFile);
            $this->getOutputWriter()->writeln(sprintf('<comment>></comment> <info>Backup of the databasewas successfully created in "%s".</info>', $this->getPath()));
        } catch (\Exception $e) {
            $this->getOutputWriter()->writeln(sprintf('<comment>></comment> <info>Backup of the database failed with the file "%s".</info>', $options['filename']));
        }
    
        return $this->getOutputWriter();
    }    
    
    /**
     * Print in the content file the query for disable all table constraints in PostgreSql
     *
     * @link http://www.postgresql.org/docs/8.2/static/sql-set-constraints.html
     * @return void
     * @access protected
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since 2012-06-28
     */
    protected function disableForeignKeys(){
        $this->contentFile  .= sprintf("SET CONSTRAINTS ALL DEFERRED; \r\n");
    }
    
    /**
     * Print in the content file the query for enabled all table constraints in PostgreSql
     *
     * @return void
     * @access protected
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since 2012-06-28
     */
    protected function EnabledForeignKeys(){
        $this->contentFile  .= sprintf("SET CONSTRAINTS ALL IMMEDIATE; \r\n");
    }

}
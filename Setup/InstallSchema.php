<?php
/**
 * MageHelper Create custom table Magento 2 with the use of
 * InstallSchema, InstallData, UpgradeSchema and UpgradeData
 *
 * @package      MageHelper_CreateCustomTable
 * @author       Kishan Savaliya <kishansavaliyakb@gmail.com>
 */

namespace MageHelper\CreateCustomTable\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
          /**
          * Create table 'magehelper_employees'
          */
          $table = $setup->getConnection()
              ->newTable($setup->getTable('magehelper_employees'))
              ->addColumn(
                  'employee_id',
                  \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                  null,
                  ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                  'Employee ID'
              )
              ->addColumn(
                  'employee_name',
                  \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                  255,
                  ['nullable' => false, 'default' => ''],
                    'Employee Name'
              )
              ->addColumn(
                  'position',
                  \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                  255,
                  ['nullable' => false, 'default' => ''],
                    'Employee Position'
              )->setComment("MageHelper Employee Data table");
          $setup->getConnection()->createTable($table);
      }
}
# MageHelper Create custom table Magento 2 with the use of InstallSchema, InstallData, UpgradeSchema and UpgradeData

We will learn here, how to create custom table in Magento 2 step by step with the use of InstallSchema, InstallData, UpgradeSchema and UpgradeData.

We can create new module in `app/code/` directory, previously in Magento 1 there were three code pools which are local, community and core but that has been removed now.

In this blog post we will see how to create custom table in Magento 2, you can download this module as well for practice.

### Step - 1 - Create a directory for the module

- In Magento 2, module name divided into two parts i.e Vendor_Module (for e.g Magento_Theme, Magento_Catalog)
- We will create `MageHelper_CreateCustomTable` here, So `MageHelper` is vendor name and `CreateCustomTable` is name of this module.
- So first create your namespace directory (`MageHelper`) and move into that directory.
- Then create module name directory (`CreateCustomTable`)

Now Go to : `app/code/MageHelper/CreateCustomTable`

### Step - 2 - Create module.xml file to declare new module.

- Magento 2 looks for configuration information for each module in that module’s etc directory. so we need to add module.xml file here in our module `app/code/MageHelper/CreateCustomTable/etc/module.xml` and it's content for our module is :

~~~ xml
<?xml version="1.0"?>
<!--
/**
 * MageHelper Create custom table Magento 2 with the use of
 * InstallSchema, InstallData, UpgradeSchema and UpgradeData
 *
 * @package      MageHelper_CreateCustomTable
 * @author       Kishan Savaliya <kishansavaliyakb@gmail.com>
 */
-->
<config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="urn:magento:framework:Module/etc/module.xsd">
	<module name="MageHelper_CreateCustomTable" setup_version="1.0.0" />
</config>
~~~

In this file, we register a module with name `MageHelper_CreateCustomTable` and the version is `1.0.0`.

### Step - 3 - create registration.php

- All Magento 2 module must be registered in the Magento system through the magento `ComponentRegistrar` class. This file will be placed in module's root directory.

In this step, we need to create this file:

~~~
app/code/MageHelper/CreateCustomTable/registration.php
~~~

And it’s content for our module is:

~~~ php
<?php
/**
 * MageHelper Create custom table Magento 2 with the use of
 * InstallSchema, InstallData, UpgradeSchema and UpgradeData
 *
 * @package      MageHelper_CreateCustomTable
 * @author       Kishan Savaliya <kishansavaliyakb@gmail.com>
 */
\Magento\Framework\Component\ComponentRegistrar::register(
    \Magento\Framework\Component\ComponentRegistrar::MODULE,
    'MageHelper_CreateCustomTable',
    __DIR__
);
~~~

### Step - 4 - Enable `MageHelper_CreateCustomTable` module.

- By finish above step, you have created an empty module. Now we will enable it in Magento environment.
- Before enable the module, we must check to make sure Magento has recognize our module or not by enter the following at the command line:

~~~ 
php bin/magento module:status
~~~

If you follow above step, you will see this in the result:

~~~
List of disabled modules:
MageHelper_CreateCustomTable
~~~

This means the module has recognized by the system but it is still disabled. Run this command to enable it:

~~~
php bin/magento module:enable MageHelper_CreateCustomTable
~~~

The module has enabled successfully if you saw this result:

~~~
The following modules has been enabled:
- MageHelper_CreateCustomTable
~~~

- We will not run `php bin/magento setup:upgrade` this command here we will run this command later in next step.

### Step - 5 - Create InstallSchema and InstallData

- Magento 2 has special mechanism to create/modify database tables. You can add data into that tables as well (like if you want to add some sample data while anyone install your module then you can do that).

- The key concept is that, instead of doing manual SQL operations we can create one script, that allows us to create table, modify any fields/data into table while installing or upgrading any module.

- InstallSchema and InstallData both scripts run only once when we install module first time.

- Here in this step, we will use **InstallSchema** to create new custom table and use **InstallData** to add some sample data when we will install this module first time

- We will create `magehelper_employees` table using **InstallSchema**, and we will add 2 sample records there using **InstallData**. So let's create `InstallSchema.php` file first. We can add this file here in our module.

~~~
app/code/MageHelper/CreateCustomTable/Setup/InstallSchema.php
~~~

Content for this file is :

~~~ php
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
~~~

- After creating this file we will create one another file to install sample data with this table initially. So we will create `InstallData.php` file there. We will add this file on same location here.

~~~
app/code/MageHelper/CreateCustomTable/Setup/InstallData.php
~~~

Content for this file is :

~~~ php
<?php
/**
 * MageHelper Create custom table Magento 2 with the use of
 * InstallSchema, InstallData, UpgradeSchema and UpgradeData
 *
 * @package      MageHelper_CreateCustomTable
 * @author       Kishan Savaliya <kishansavaliyakb@gmail.com>
 */

namespace MageHelper\CreateCustomTable\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface
{
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
          /**
           * Install employee data
           */
          $data = [
              ['employee_name' => 'John Doe', 'position' => 'Accountant'],
              ['employee_name' => 'Cara Stevens', 'position' => 'Software Engineer ']
          ];
          foreach ($data as $bind) {
              $setup->getConnection()
                ->insertForce($setup->getTable('magehelper_employees'), $bind);
          }
    }
}
~~~

- We will now run `php bin/magento setup:upgrade` command in console. Once this command will completed then we can check our custom table `magehelper_employees` in database with 2 records.

**Output : InstallSchema and InstallData**

![MageHelper Create custom table Magento 2 with the use of InstallSchema, InstallData output](https://github.com/KishanSavaliya/magehelper-magento2-create-custom-table/blob/master/MageHelper/Magento2-Use-Of-InstallSchema-InstallData-Custom-Table.png)


### Step - 6 - Create UpgradeSchema

- In this step, we will create `UpgradeSchema.php` file in our module's `Setup` directory where we have added `InstallSchema.php` and `InstallData.php` scripts.

- For e.g. after creating `magehelper_employees` table I remember that I forgot to add `employee_salary` field in that table. So now I need to add that new field using MySQL only? No, We can add that new field using **UpgradeSchema** script. We will create this file on this location.

~~~
app/code/MageHelper/CreateCustomTable/Setup/UpgradeSchema.php
~~~

Content for this file is :

~~~ php
<?php
/**
 * MageHelper Create custom table Magento 2 with the use of
 * InstallSchema, InstallData, UpgradeSchema and UpgradeData
 *
 * @package      MageHelper_CreateCustomTable
 * @author       Kishan Savaliya <kishansavaliyakb@gmail.com>
 */

namespace MageHelper\CreateCustomTable\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        if (version_compare($context->getVersion(), '1.0.1', '<')) {
            $setup->getConnection()->addColumn(
                $setup->getTable('magehelper_employees'),
                'employee_salary',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 16,
                    'nullable' => false,
                    'default' => '',
                    'comment' => 'Employee Salary'
                ]
            );
        }
        $setup->endSetup();
    }
}
~~~

- After creating this file we need to change this module's version in `app/code/MageHelper/CreateCustomTable/etc/module.xml` file.

- Now onwards, if anytime we want to add, modify or remove anything in table we need to update module's version because InstallSchema and InstallData only works first time when we install module after that we can use UpgradeSchema whenever we want to update anything.

- When we update our module's version in `module.xml` file and after that we need to run `php bin/magento setup:upgrade` command in console. Magento will update that version in `setup_module` table. So let's update version from `1.0.0` to `1.0.1` in this file

~~~
app/code/MageHelper/CreateCustomTable/etc/module.xml
~~~

Now content for this file is :

~~~ xml
<?xml version="1.0"?>
<!--
/**
 * MageHelper Create custom table Magento 2 with the use of
 * InstallSchema, InstallData, UpgradeSchema and UpgradeData
 *
 * @package      MageHelper_CreateCustomTable
 * @author       Kishan Savaliya <kishansavaliyakb@gmail.com>
 */
-->
<config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="urn:magento:framework:Module/etc/module.xsd">
    <module name="MageHelper_CreateCustomTable" setup_version="1.0.1" />
</config>
~~~

- After creating this file we need to run `php bin/magento setup:upgrade` this command. So let's do that and check database for output.

**Output : UpgradeSchema**

- New field `employee_salary` now created in database.

![MageHelper Create custom table Magento 2 with the use of UpgradeSchema output](https://github.com/KishanSavaliya/magehelper-magento2-create-custom-table/blob/master/MageHelper/Magento2-Use-Of-UpgradeSchema-Custom-Table.png)

### Step - 7 - Create UpgradeData

- In this step, we will use **UpgradeData** script to update employee's salary and we will update this customer's ("John Doe") position from "Accountant" to "Designer". We will create `UpgradeData.php` file in our module's `Setup` directory where we have added `InstallSchema.php`, `InstallData.php` and `UpgradeSchema.php` scripts. We will create this file on this location.

~~~
app/code/MageHelper/CreateCustomTable/Setup/UpgradeData.php
~~~

Content for this file is :

~~~ php
<?php
/**
 * MageHelper Create custom table Magento 2 with the use of
 * InstallSchema, InstallData, UpgradeSchema and UpgradeData
 *
 * @package      MageHelper_CreateCustomTable
 * @author       Kishan Savaliya <kishansavaliyakb@gmail.com>
 */

namespace MageHelper\CreateCustomTable\Setup;

use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class UpgradeData implements UpgradeDataInterface
{
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        if ($context->getVersion()
            && version_compare($context->getVersion(), '1.0.2') < 0
        ) {
            $table = $setup->getTable('magehelper_employees');

            $setup->getConnection()
                ->update($table, ['employee_salary' => '10000', 'position' => 'Designer'], 'employee_id = 1');

            $setup->getConnection()
                ->update($table, ['employee_salary' => '20000'], 'employee_id = 2');
        }
        $setup->endSetup();
    }
}
~~~

- After creating this file we need to change this module's version same as we did for `UpgradeSchema.php` file.

- We need to change version in `UpgradeSchema` and `UpgradeData` both scripts, whenever we want to add any field, attribute or any sample data.

- Now onwards, if anytime we want to add, modify or remove anything in table we need to update module's version because InstallSchema and InstallData only works first time when we install module after that we can use UpgradeSchema whenever we want to update anything. So let's update version from `1.0.1` to `1.0.2` in this file.

~~~
app/code/MageHelper/CreateCustomTable/etc/module.xml
~~~

Now content for this file is :

~~~ xml
<?xml version="1.0"?>
<!--
/**
 * MageHelper Create custom table Magento 2 with the use of
 * InstallSchema, InstallData, UpgradeSchema and UpgradeData
 *
 * @package      MageHelper_CreateCustomTable
 * @author       Kishan Savaliya <kishansavaliyakb@gmail.com>
 */
-->
<config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="urn:magento:framework:Module/etc/module.xsd">
    <module name="MageHelper_CreateCustomTable" setup_version="1.0.2" />
</config>
~~~

- After creating this file we need to run `php bin/magento setup:upgrade` this command. So let's do that and check database for output.

**Output : UpgradeSchema**

- Employee's salary field updated and also first employee's position updated here.

![MageHelper Create custom table Magento 2 with the use of UpgradeData output](https://github.com/KishanSavaliya/magehelper-magento2-create-custom-table/blob/master/MageHelper/Magento2-Use-Of-UpgradeData-Custom-Table.png)
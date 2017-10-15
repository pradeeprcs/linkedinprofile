<?php
namespace RedboxDigital\Linkedin\Setup;

use Magento\Framework\Module\Setup\Migration;
use Magento\Eav\Model\Entity\Attribute\Set as AttributeSet;
use Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
 * Customer Custom Attribute
 *
 * @author Pradeep Kumar <pradeep.kumarrcs67@gmail.com>
 */

class InstallData implements InstallDataInterface
{

    /**
     * Customer setup factory
     *
     * @var \Magento\Customer\Setup\CustomerSetupFactory
     */
    private $_customerSetupFactory;

    /**
     * Attribute Set
     * 
     * @var AttributeSetFactory
     */
    private $_attributeSetFactory;

    /**
     * Init
     *
     * @param CustomerSetupFactory $customerSetupFactory CustomerSetupFactory.
     * @param AttributeSetFactory  $attributeSetFactory  AttributeSetFactory.
     */
    public function __construct(
        \Magento\Customer\Setup\CustomerSetupFactory $customerSetupFactory,
        AttributeSetFactory $attributeSetFactory
    ) {
        $this->_customerSetupFactory = $customerSetupFactory;
        $this->_attributeSetFactory = $attributeSetFactory;
    }

    /**
     * Installs DB schema for a module
     *
     * @param ModuleDataSetupInterface $setup   Setup.
     * @param ModuleContextInterface   $context Context.
     * 
     * @return void
     */
    public function install(
        ModuleDataSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $installer = $setup;
        $installer->startSetup();

        $customerSetup = $this->_customerSetupFactory->create(
            ['setup' => $setup]
        );
        $entityTypeId = $customerSetup->getEntityTypeId(
            \Magento\Customer\Model\Customer::ENTITY
        );

        $customerEntity = $customerSetup->getEavConfig()->getEntityType('customer');
        $attributeSetId = $customerEntity->getDefaultAttributeSetId();

        $attributeSet = $this->_attributeSetFactory->create();
        $attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);
        $customerSetup->removeAttribute(
            \Magento\Customer\Model\Customer::ENTITY, "linkedin_profile"
        );

        $customerSetup->addAttribute(
            \Magento\Customer\Model\Customer::ENTITY,
            "linkedin_profile",
            array(
                "type" => "varchar",
                "backend" => "\RedboxDigital\Linkedin\Model\Backend\LinkedinProfile",
                "label" => "Linkedin Profile",
                "input" => "text",
                "source" => "",
                "visible" => true,
                "required" => false,
                "default" => "",
                "frontend" => "",
                "unique" => false,
                "frontend_class" => 'validate-url '
                . 'validate-length maximum-length-250 ',
                "note" => ""
            )
        );

        $linkedin_profile = $customerSetup->getAttribute(
            \Magento\Customer\Model\Customer::ENTITY,
            "linkedin_profile"
        );
        $linkedin_profile = $customerSetup->getEavConfig()->getAttribute(
            \Magento\Customer\Model\Customer::ENTITY,
            'linkedin_profile'
        );

        $used_in_forms[] = "adminhtml_customer";
        $used_in_forms[] = "customer_account_create";
        $used_in_forms[] = "customer_account_edit";

        $linkedin_profile->setData("used_in_forms", $used_in_forms)
            ->setData("attribute_set_id", $attributeSetId)
            ->setData("attribute_group_id", $attributeGroupId)
            ->setData("is_used_in_grid", 1)
            ->setData("is_visible_in_grid", 1)
            ->setData("is_filterable_in_grid", 1)
            ->setData("is_system", 0)
            ->setData("is_user_defined", 1)
            ->setData("is_visible", 1);

        $linkedin_profile->save();

        $installer->endSetup();
    }

}

<?php
namespace RedboxDigital\Linkedin\Block;

use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;

/**
 * Customer Custom Attribute
 *
 * @author Pradeep Kumar <pradeep.kumarrcs67@gmail.com>
 */
class CustomAttribute extends \Magento\Framework\View\Element\Template
{
    /**
     * Customer Session.
     * 
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;
    
    /**
     * Customer Repository.
     * 
     * @var CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * Constructor
     *
     * @param Element\Template\Context    $context            Context
     * @param Model\Session               $customerSession    Customer Session.
     * @param CustomerRepositoryInterface $customerRepository Customer.
     * @param array                       $data               Data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        CustomerRepositoryInterface $customerRepository,
        array $data = []
    ) {
        $this->customerSession = $customerSession;
        $this->customerRepository = $customerRepository;
        parent::__construct($context, $data);
    }

    /**
     * Return the Customer given the customer Id stored in the session.
     *
     * @return \Magento\Customer\Api\Data\CustomerInterface
     */
    public function getCustomer()
    {
        if ($this->customerSession->getCustomerId()) {
            return $this->customerRepository->getById(
                $this->customerSession->getCustomerId()
            );
        }
        return false;
    }
}

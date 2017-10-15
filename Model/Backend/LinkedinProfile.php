<?php
namespace RedboxDigital\Linkedin\Model\Backend;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Url\Validator as UrlValidator;

/**
 * Customer Custom Linkedin attribute backend
 * 
 * @author Pradeep Kumar <pradeep.kumarrcs67@gmail.com>
 */
class LinkedinProfile extends
    \Magento\Eav\Model\Entity\Attribute\Backend\AbstractBackend
{
    /**
     * MAX length
     */
    const MAX_LENGTH = 250;

    /**
     * Magento string lib
     *
     * @var \Magento\Framework\Stdlib\StringUtils
     */
    protected $string;

    /**
     * UrlValidator
     * 
     * @var UrlValidator
     */
    protected $urlValidator;


    /**
     * Construct.
     * 
     * @param \Magento\Framework\Stdlib\StringUtils $string       String.
     * @param UrlValidator                          $urlValidator UrlValidator.
     */
    public function __construct(
        \Magento\Framework\Stdlib\StringUtils $string,
        UrlValidator $urlValidator
    ) {
        $this->string = $string;
        $this->urlValidator = $urlValidator;
    }

    /**
     * Special processing before attribute save:
     * a) check Max Length
     * b) Valid URL
     *
     * @param \Magento\Framework\DataObject $object DataObject.
     * 
     * @return void
     */
    public function beforeSave($object)
    {
        $value = $object->getLinkedinProfile();

        $length = $this->string->strlen($value);
        if ($length > 0) {
            if ($length > self::MAX_LENGTH) {
                throw new LocalizedException(
                    __(
                        'Maximum length of Linkedin Profile must be '
                            . 'equal or less than  %1 characters.',
                        self::MAX_LENGTH
                    )
                );
            }

            if (!$this->urlValidator->isValid($value)) {
                throw new LocalizedException(__('Invalid URL'));
            }

            return parent::beforeSave($object);
        }
        return parent::beforeSave($object);
    }
}

<?php

namespace App\Validator;

use App\Entity\Trade;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;


class TradeClassValidator extends ConstraintValidator
{
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @param mixed $protocol
     * @param Constraint $constraint
     */
    public function validate($protocol, Constraint $constraint)
    {
        if (!$constraint instanceof TradeClass) {
            throw new UnexpectedTypeException($constraint, TradeClass::class);
        }

        /** @var Trade $protocol */
        if (true === $protocol->getStatus() && null === $protocol->getDodoCode() ) {
            $this->context->buildViolation('')
                ->addViolation();

            $this->session->getFlashBag()->add('danger', 'Si vous voulez ouvrir votre île, un dodocode est requis !!!');
        }
    }
}

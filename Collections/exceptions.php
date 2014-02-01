<?php

namespace stekycz\collections;

class InvalidArgumentException extends \InvalidArgumentException
{

}



class KeyNotFoundException extends InvalidArgumentException
{

}



class InvalidStateException extends \LogicException
{

}

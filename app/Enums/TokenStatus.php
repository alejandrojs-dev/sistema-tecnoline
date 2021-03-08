<?php

namespace App\Enums;

abstract class TokenStatus
{
  const INVALID = 1;
  const EXPIRED = 2;
  const NOT_FOUND = 3;
}

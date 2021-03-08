<?php

namespace App\Enums;

abstract class HttpStatusCode
{
  const OK = 200;
  const CREATED = 201;
  const NO_CONTENT = 204;
  const BAD_REQUEST = 400;
  const UNAUTHORIZED = 401;
  const FORBIDDEN = 403;
  const NOT_FOUND = 404;
  const REQUEST_TIMEOUT = 408;
  const UNPROCESSABLE_ENTITY = 422;
  const INTERNAL_SERVER_ERROR = 500;
  const CONFLICT = 409;
}

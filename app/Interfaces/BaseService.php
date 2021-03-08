<?php

namespace App\Interfaces;

interface IBaseService
{
  public function all();
  public function get($entity);
  public function save($request);
  public function update($request, $entity);
  public function delete($entity);
}

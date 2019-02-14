<?php

namespace Sys;

class JsonResponse implements Interfaces\ResponseInterface
{
  public $response = ['status' => 'success'];

  /**
   * Проверка на успешность
   * @return boolean
   */
  public function isSuccess()
  {
    return $this->response['status'] == 'success';
  }

  /**
   * Проверка на ошибки
   * @return bool
   */
  public function isError()
  {
    return $this->response['status'] != 'success';
  }

  /**
   * Установить ошибку
   * @param string $message
   * @return $this
   */
  public function error($message)
  {
    $this->response['status'] = 'error';
    if(!isset($this->response['message']))
      $this->response['message'] = [];
    $this->response['message'][] = $message;
    return $this;
  }

  /**
   * Установить значение
   * @param string $key
   * @param mixed $value
   * @return $this
   */
  public function setValue($key, $value)
  {
    $this->response[$key] = $value;
    return $this;
  }

  /**
   * Отправка ответа
   */
  public function send()
  {
    echo json_encode($this->response);
  }
}
<?php
namespace App\Exceptions;
/**
 * AppException — Base exception for all app-level errors.
 *
 * Every custom exception in this project extends this class.
 * That way you can catch all app errors with:
 *   catch (AppException $e)   ? catches any app error
 * or catch specific ones:
 *   catch (NotFoundException $e)   ? only catches not-found errors
 */
class AppException extends \Exception {}

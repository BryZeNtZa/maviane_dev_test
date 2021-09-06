<?php

namespace Maviance\Core\Repository;

use Maviance\Core\Model\Model;

/**
 * The generic repository interface
 *
 * This interface exposes methods for all
 * DAO classes to implement
 *
 * @author     Maviance, PLC.
 * @version    Release: @package_version@
 * @since      Class available since Release 1.0.0
 */
interface RepositoryInterface
{
    /**
     * Create an empty model interface for the repository.
     *
     * @return  Model
     * @access  public
     * @since   Method available since Release 1.0.0
     */
    public function create(): Model;


    /**
     * Save model object (Insert/Update)
     * 
     * @param   Model $model
     * @return  void
     * @access  public
     * @since   Method available since Release 1.0.0
     */
    public function save(Model $model): void;
}
